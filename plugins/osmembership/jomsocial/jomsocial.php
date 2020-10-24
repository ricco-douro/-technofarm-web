<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class plgOSMembershipJomSocial extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Whether the plugin should be run when events are triggered
	 *
	 * @var bool
	 */
	protected $canRun;

	/**
	 * Constructor
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		$this->canRun = file_exists(JPATH_ROOT . '/components/com_community/community.php');
	}

	/**
	 * Method to get list of custom fields in Jomsocial used to map with fields in Membership Pro
	 *
	 * Method is called on custom field add / edit page from backend of Membership Pro
	 *
	 * @return mixed
	 */
	public function onGetFields()
	{
		if (!$this->canRun)
		{
			return;
		}

		$sql = 'SELECT fieldcode AS `value`, fieldcode AS `text` FROM #__community_fields WHERE published=1 AND fieldcode != ""';
		$this->db->setQuery($sql);

		return $this->db->loadObjectList();
	}

	/**
	 * Method to get data stored in Jomsocial profile of the given user
	 *
	 * @param int   $userId
	 * @param array $mappings
	 *
	 * @return array
	 */
	public function onGetProfileData($userId, $mappings = array())
	{
		if (!$this->canRun)
		{
			return;
		}

		$synchronizer = new MPFSynchronizerJomsocial();

		return $synchronizer->getData($userId, $mappings);
	}

	/**
	 * Render settings form allows admin to choose what Jomsocial groups subscribers will be assigned to when they sign up for this plan
	 *
	 * Method is called on plan add/edit page
	 *
	 * @param OSMembershipTablePlan $row The plan record
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		if (!$this->canRun)
		{
			return;
		}

		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_JOMSOCIAL_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Method to store settings into database
	 *
	 * @param OSMembershipTablePlan $row   The plan record
	 * @param array                 $data  The form post data
	 * @param bool                  $isNew True if new plan is created, false if updating the plan
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		if (!$this->canRun)
		{
			return;
		}

		$params = new Registry($row->params);
		$params->set('jomsocial_group_ids', implode(',', $data['jomsocial_group_ids']));
		$params->set('jomsocial_expried_group_ids', implode(',', $data['jomsocial_expried_group_ids']));
		$row->params = $params->toString();

		$row->store();
	}

	/**
	 * Method to create Jomsocial account for subscriber and assign him to selected Jomsocial groups when subscription is active
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @return bool
	 */
	public function onMembershipActive($row)
	{
		if (!$this->canRun || !$row->user_id)
		{
			return;
		}


		$sql = 'SELECT COUNT(*) FROM #__community_users WHERE userid = ' . $row->user_id;
		$this->db->setQuery($sql);
		$count = $this->db->loadResult();

		if (!$count)
		{
			$sql = 'INSERT INTO #__community_users(userid) VALUES(' . $row->user_id . ')';
			$this->db->setQuery($sql);
			$this->db->execute();
		}

		$sql = 'SELECT id, fieldcode FROM #__community_fields WHERE published=1 AND fieldcode != ""';
		$this->db->setQuery($sql);
		$rowFields = $this->db->loadObjectList();
		$fieldList = array();

		foreach ($rowFields as $rowField)
		{
			$fieldList[$rowField->fieldcode] = $rowField->id;
		}

		$sql = 'SELECT name, field_mapping FROM #__osmembership_fields WHERE field_mapping != "" AND field_mapping IS NOT NULL AND is_core = 1';
		$this->db->setQuery($sql);
		$fields      = $this->db->loadObjectList();
		$fieldValues = array();

		if (count($fields))
		{
			foreach ($fields as $field)
			{
				$fieldName = $field->field_mapping;
				if ($fieldName)
				{
					$fieldValues[$fieldName] = $row->{$field->name};
				}
			}
		}

		$sql = 'SELECT a.field_mapping, b.field_value FROM #__osmembership_fields AS a '
			. ' INNER JOIN #__osmembership_field_value AS b '
			. ' ON a.id = b.field_id '
			. ' WHERE b.subscriber_id=' . $row->id;
		$this->db->setQuery($sql);
		$fields = $this->db->loadObjectList();
		if (count($fields))
		{
			foreach ($fields as $field)
			{
				if ($field->field_mapping)
				{
					$fieldValues[$field->field_mapping] = $field->field_value;
				}
			}
		}

		if (count($fieldValues))
		{
			foreach ($fieldValues as $fieldCode => $fieldValue)
			{
				if (isset($fieldList[$fieldCode]))
				{
					$fieldId = $fieldList[$fieldCode];
					if ($fieldId)
					{
						if (is_string($fieldValue) && is_array(json_decode($fieldValue)))
						{
							$fieldValue = implode(',', json_decode($fieldValue));
						}

						$fieldValue = $this->db->quote($fieldValue);
						$sql        = "INSERT INTO #__community_fields_values(user_id, field_id, `value`, `access`) VALUES($row->user_id, $fieldId, $fieldValue, 1)";
						$this->db->setQuery($sql);
						$this->db->execute();
					}
				}
			}
		}

		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params = new Registry($plan->params);
		$groups = explode(',', $params->get('jomsocial_group_ids'));
		if (count($groups))
		{
			$sql = 'REPLACE INTO `#__community_groups_members` (`memberid`,`groupid`,`approved`,`permissions`) VALUES ';

			$values = array();
			foreach ($groups as $group)
			{
				$values[] = '(' . $this->db->Quote($row->user_id) . ', ' . $this->db->Quote($group) . ', 1, 1)';
			}

			$sql .= implode(', ', $values);

			$this->db->setQuery($sql);
			$this->db->execute();
		}

		return true;
	}

	/**
	 * Run when a membership activated
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @return boolean
	 */
	public function onProfileUpdate($row)
	{
		if (!$this->canRun || !$row->user_id)
		{
			return;
		}


		$sql = 'SELECT COUNT(*) FROM #__community_users WHERE userid=' . $row->user_id;
		$this->db->setQuery($sql);
		$count = $this->db->loadResult();

		if (!$count)
		{
			$sql = 'INSERT INTO #__community_users(userid) VALUES(' . $row->user_id . ')';
			$this->db->setQuery($sql);
			$this->db->execute();
		}

		$sql = 'SELECT id, fieldcode FROM #__community_fields WHERE published=1 AND fieldcode != ""';
		$this->db->setQuery($sql);
		$rowFields = $this->db->loadObjectList();
		$fieldList = array();
		foreach ($rowFields as $rowField)
		{
			$fieldList[$rowField->fieldcode] = $rowField->id;
		}

		$sql = 'SELECT name, field_mapping FROM #__osmembership_fields WHERE field_mapping != "" AND field_mapping IS NOT NULL AND is_core = 1';
		$this->db->setQuery($sql);
		$fields      = $this->db->loadObjectList();
		$fieldValues = array();

		if (count($fields))
		{
			foreach ($fields as $field)
			{
				$fieldName = $field->field_mapping;
				if ($fieldName)
				{
					$fieldValues[$fieldName] = $row->{$field->name};
				}
			}
		}

		$sql = 'SELECT a.field_mapping, b.field_value FROM #__osmembership_fields AS a '
			. ' INNER JOIN #__osmembership_field_value AS b '
			. ' ON a.id = b.field_id '
			. ' WHERE b.subscriber_id=' . $row->id;
		$this->db->setQuery($sql);
		$fields = $this->db->loadObjectList();
		if (count($fields))
		{
			foreach ($fields as $field)
			{
				if ($field->field_mapping)
				{
					$fieldValues[$field->field_mapping] = $field->field_value;
				}
			}
		}

		if (count($fieldValues))
		{
			foreach ($fieldValues as $fieldCode => $fieldValue)
			{
				if (isset($fieldList[$fieldCode]))
				{
					$fieldId = $fieldList[$fieldCode];
					if ($fieldId)
					{
						if (is_string($fieldValue) && is_array(json_decode($fieldValue)))
						{
							$fieldValue = implode(',', json_decode($fieldValue));
						}

						$fieldValue = $this->db->quote($fieldValue);
						$sql        = "REPLACE INTO #__community_fields_values(user_id, field_id, `value`, `access`) VALUES($row->user_id, $fieldId, $fieldValue, 1)";
						$this->db->setQuery($sql);
						$this->db->execute();
					}
				}
			}
		}

		$plan =  &JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params = new Registry($plan->params);
		$groups = explode(',', $params->get('jomsocial_group_ids'));
		if (count($groups))
		{
			$sql = 'REPLACE INTO `#__community_groups_members` (`memberid`,`groupid`,`approved`,`permissions`) VALUES ';

			$values = array();
			foreach ($groups as $group)
			{
				$values[] = '(' . $this->db->Quote($row->user_id) . ', ' . $this->db->Quote($group) . ', 1, 0)';
			}

			$sql .= implode(', ', $values);

			$this->db->setQuery($sql);
			$this->db->execute();
		}

		return true;
	}

	/**
	 * Run when a membership expiried die
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipExpire($row)
	{
		if (!$this->canRun || !$row->user_id)
		{
			return;
		}

		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params = new Registry($plan->params);
		$groups = explode(',', $params->get('jomsocial_expried_group_ids'));

		if (count($groups))
		{
			foreach ($groups as $group)
			{
				$group = (int) $group;

				if ($group)
				{
					$sql = 'DELETE FROM #__community_groups_members WHERE groupid=' . $group . ' AND memberid=' . $row->user_id;
					$this->db->setQuery($sql);
					$this->db->execute();
				}
			}
		}
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param OSMembershipTablePlan $row
	 */
	private function drawSettingForm($row)
	{
		$params                      = new Registry($row->params);
		$jomsocial_group_ids         = explode(',', $params->get('jomsocial_group_ids', ''));
		$jomsocial_expried_group_ids = explode(',', $params->get('jomsocial_expried_group_ids', ''));

		$sql = 'SELECT id, name FROM #__community_groups WHERE published = 1 ORDER BY name ';
		$this->db->setQuery($sql);

		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('Choose Groups'), 'id', 'name');
		$options   = array_merge($options, $this->db->loadObjectList());
		?>
        <table class="admintable adminform" style="width: 90%;">
            <tr>
                <td width="220" class="key">
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOMSOCIAL_ASSIGN_TO_GROUPS'); ?>
                </td>
                <td>
					<?php
					echo JHtml::_('select.genericlist', $options, 'jomsocial_group_ids[]', ' multiple="multiple" size="6" ', 'id', 'name', $jomsocial_group_ids);
					?>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOMSOCIAL_ASSIGN_TO_GROUPS_EXPLAIN'); ?>
                </td>
            </tr>
            <tr>
                <td width="220" class="key">
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOMSOCIAL_REMOVE_FROM_GROUPS'); ?>
                </td>
                <td>
					<?php
					echo JHtml::_('select.genericlist', $options, 'jomsocial_expried_group_ids[]', ' multiple="multiple" size="6" ', 'id', 'name', $jomsocial_expried_group_ids);
					?>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOMSOCIAL_REMOVE_FROM_GROUPS_EXPLAIN'); ?>
                </td>
            </tr>
        </table>
		<?php
	}
}
