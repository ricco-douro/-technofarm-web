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

class plgOSMembershipK2groups extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Flag determine whether the plugin should be run when events are triggered
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

		$this->canRun = file_exists(JPATH_ROOT . '/components/com_k2/k2.php');
	}

	/**
	 * Render settings from
	 *
	 * @param OSMembershipTablePlan $row
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

		return array('title' => JText::_('PLG_OSMEMBERSHIP_K2_GROUPS_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database
	 *
	 * @param string                $context
	 * @param OSMembershipTablePlan $row
	 * @param array                 $data
	 * @param bool                  $isNew
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		if (!$this->canRun)
		{
			return;
		}

		$params = new Registry($row->params);
		$params->set('k2_group_id', $data['k2_group_id']);
		$params->set('k2_expired_group_id', $data['k2_expired_group_id']);
		$row->params = $params->toString();
		$row->store();
	}

	/**
	 * Run when a membership activated
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipActive($row)
	{
		if (!$this->canRun || !$row->user_id)
		{
			return;
		}

		$params = $this->getPlanParams($row->plan_id);

		if ($k2GroupId = (int) $params->get('k2_group_id', ''))
		{
			$this->assignUserToK2Group($row->user_id, $k2GroupId);
		}
	}

	/**
	 * Run when a membership expired
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipExpire($row)
	{
		if (!$this->canRun || !$row->user_id)
		{
			return;
		}

		$activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));

		// Users has renewed their subscription before, don't process further
		if (in_array($row->plan_id, $activePlans))
		{
			return;
		}

		$params = $this->getPlanParams($row->plan_id);

		if ($k2ExpiredGroupId = (int) $params->get('k2_expired_group_id', ''))
		{
			$this->assignUserToK2Group($row->user_id, $k2ExpiredGroupId);
		}
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param OSMembershipTablePlan $row
	 */
	private function drawSettingForm($row)
	{
		$params           = new Registry($row->params);
		$k2GroupId        = $params->get('k2_group_id', '');
		$k2ExpiredGroupId = $params->get('k2_expired_group_id', '');
		$query            = $this->db->getQuery(true);
		$query->select('id AS value, name AS text')->from('#__k2_user_groups');
		$this->db->setQuery($query);
		$options = $this->db->loadObjectList();
		array_unshift($options, JHtml::_('select.option', '', JText::_('PLG_OSMEMBERSHIP_SELECT_K2_GROUP')));
		?>
        <table class="admintable adminform" style="width: 90%;">
            <tr>
                <td width="220" class="key">
					<?php echo JText::_('PLG_OSMEMBERSHIP_K2GROUP_ASSIGN_TO_GROUP'); ?>
                </td>
                <td>
					<?php echo JHtml::_('select.genericlist', $options, 'k2_group_id', '', 'value', 'text', $k2GroupId); ?>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_K2GROUP_ASSIGN_TO_GROUP_EXPLAIN'); ?>
                </td>
            </tr>
            <tr>
                <td width="220" class="key">
					<?php echo JText::_('PLG_OSMEMBERSHIP_K2GROUP_SUBSCRIPTION_EXPIRED_ASSIGN_TO_GROUPS'); ?>
                </td>
                <td>
					<?php echo JHtml::_('select.genericlist', $options, 'k2_expired_group_id', '', 'value', 'text', $k2ExpiredGroupId); ?>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_K2GROUP_SUBSCRIPTION_EXPIRED_ASSIGN_TO_GROUPS_EXPLAIN'); ?>
                </td>
            </tr>
        </table>
		<?php
	}

	/**
	 * Assign a user to selected K2 Group
	 *
	 * @param $userId
	 * @param $k2GroupId
	 */
	private function assignUserToK2Group($userId, $k2GroupId)
	{
		$query = $this->db->getQuery(true);
		$query->select('id')
			->from('#__k2_users')
			->where('userID =' . $userId);
		$this->db->setQuery($query);
		$k2UserId = $this->db->loadResult();

		if ($k2UserId)
		{
			$query->clear()->update('#__k2_users')->set('`group`=' . $k2GroupId)->where('id =' . $k2UserId);
		}
		else
		{
			$query->clear()->insert('#__k2_users')->set('`group`=' . $k2GroupId)->set('`userID`=' . $userId);
		}

		$this->db->setQuery($query)
			->execute();
	}

	/**
	 * Method to get the plan params
	 *
	 * @param int $planId
	 *
	 * @return Registry
	 */
	private function getPlanParams($planId)
	{
		$query = $this->db->getQuery(true);
		$query->select('`params`')
			->from('#__osmembership_plans')
			->where('id = ' . (int) $planId);
		$this->db->setQuery($query);

		return new Registry($this->db->loadResult());
	}
}
