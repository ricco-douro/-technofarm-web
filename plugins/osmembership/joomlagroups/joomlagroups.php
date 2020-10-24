<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

class plgOSMembershipJoomlagroups extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Render settings from
	 *
	 * @param OSMembershipTablePlan $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_JOOMLA_GROUPS_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database
	 *
	 * @param OSMembershipTablePlan $row
	 * @param Boolean               $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$params = new Registry($row->params);
		$params->set('joomla_group_ids', implode(',', $data['joomla_group_ids']));
		$params->set('remove_joomla_group_ids', implode(',', $data['remove_joomla_group_ids']));
		$params->set('subscription_expired_joomla_group_ids', implode(',', $data['subscription_expired_joomla_group_ids']));
		$params->set('joomla_expried_group_ids', implode(',', $data['joomla_expried_group_ids']));
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
		if ($row->user_id)
		{
			$user          = JFactory::getUser($row->user_id);
			$currentGroups = $user->get('groups');
			$plan          = JTable::getInstance('Osmembership', 'Plan');
			$plan->load($row->plan_id);
			$params                      = new Registry($plan->params);
			$groups                      = explode(',', $params->get('joomla_group_ids'));
			$removeGroupIds              = explode(',', $params->get('remove_joomla_group_ids'));
			$currentGroups               = array_unique(array_merge($currentGroups, $groups));

			if ($row->group_admin_id > 0 && JPluginHelper::isEnabled('osmembership', 'groupmembership'))
			{
				// This is group member, need to exclude from some groups if needed
				$plugin = JPluginHelper::getPlugin('osmembership', 'groupmembership');
				if ($plugin)
				{
					$params          = new Registry($plugin->params);
					$excludeGroupIds = $params->get('exclude_group_ids', '');
					if ($excludeGroupIds)
					{
						$excludeGroupIds = explode(',', $excludeGroupIds);
						$excludeGroupIds = ArrayHelper::toInteger($excludeGroupIds);
						$currentGroups = array_diff($currentGroups, $excludeGroupIds);
					}
				}
			}

			// Get Joomla group from custom fields selection
			$currentGroups = array_merge($currentGroups, $this->getJoomlaGroupsFromFields($row));

			// Remove from Joomla groups when active
			$currentGroups = array_diff($currentGroups, $removeGroupIds);

			$user->set('groups', $currentGroups);
			$user->save(true);
		}
	}

	/**
	 * Run when a membership expiried die
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipExpire($row)
	{
		if ($row->user_id)
		{
			$user          = JFactory::getUser($row->user_id);
			$currentGroups = $user->get('groups');
			$plan          = JTable::getInstance('Osmembership', 'Plan');
			$plan->load($row->plan_id);
			$params                      = new Registry($plan->params);
			$groups                      = explode(',', $params->get('joomla_expried_group_ids'));
			$subscriptionExpiredGroupIds = explode(',', $params->get('subscription_expired_joomla_group_ids'));
			$activePlans                 = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));

			// Subscribers will be assigned to this group if he has no more active subscription of this plan, haven't renewed yet
			if (!in_array($row->plan_id, $activePlans))
			{
				$currentGroups = array_merge($currentGroups, $subscriptionExpiredGroupIds);
			}

			$query = $this->db->getQuery(true);
			$query->select('params')
				->from('#__osmembership_plans')
				->where('id IN  (' . implode(',', $activePlans) . ')');
			$this->db->setQuery($query);
			$rowPlans = $this->db->loadObjectList();
			if (count($rowPlans))
			{
				foreach ($rowPlans as $rowPlan)
				{
					$planParams = new Registry($rowPlan->params);
					$planGroups = explode(',', $planParams->get('joomla_group_ids'));
					$groups     = array_diff($groups, $planGroups);
				}
			}
			$currentGroups = array_unique(array_diff($currentGroups, $groups));
			$user->set('groups', $currentGroups);
			$user->save(true);
		}
	}

	/**
	 * Get Joomla groups from custom fields which subscriber select for their subscription
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @return array
	 */
	private function getJoomlaGroupsFromFields($row)
	{
		$groups = array();

		$rowFields        = OSMembershipHelper::getProfileFields($row->plan_id, true, $row->language, $row->act);
		$subscriptionData = OSMembershipHelper::getProfileData($row, $row->plan_id, $rowFields);
		foreach ($rowFields as $field)
		{
			if (!empty($field->joomla_group_ids) && !empty($field->values))
			{
				$fieldValues    = explode("\r\n", $field->values);
				$groupIds       = explode("\r\n", $field->joomla_group_ids);
				$selectedValues = $subscriptionData[$field->name];

				if (is_string($selectedValues) && is_array(json_decode($selectedValues)))
				{
					$selectedValues = json_decode($selectedValues);
				}
				else
				{
					$selectedValues = array($selectedValues);
				}

				foreach ($selectedValues as $fieldValue)
				{
					if (!empty($fieldValue))
					{
						$valueIndex = array_search($fieldValue, $fieldValues);
						if ($valueIndex !== false)
						{
							$groupId = (int) $groupIds[$valueIndex];
							if ($groupId)
							{
								$groups[] = $groupId;
							}
						}
					}
				}
			}
		}

		return $groups;
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param OSMembershipTablePlan $row
	 */
	private function drawSettingForm($row)
	{
		$params                            = new Registry($row->params);

		$activeGroupIds        = explode(',', $params->get('joomla_group_ids', ''));
		$activeRemoveGroupIds  = explode(',', $params->get('remove_joomla_group_ids', ''));
		$expiredRemoveGroupIds = explode(',', $params->get('joomla_expried_group_ids', ''));
		$expiredGroupIds       = explode(',', $params->get('subscription_expired_joomla_group_ids', ''));
		?>
		<div class="row-fluid">
			<div class="span6 pull-left">
				<fieldset class="adminform">
					<legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_ACTIVE');?></legend>
					<table class="admintable adminform" style="width: 90%;">
						<tr>
							<td width="220" class="key">
								<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_ASSIGN_TO_JOOMLA_GROUPS'); ?>
							</td>
							<td>
								<?php echo JHtml::_('access.usergroup', 'joomla_group_ids[]', $activeGroupIds, ' multiple="multiple" size="6" ', false); ?>
							</td>
						</tr>
						<tr>
							<td width="220" class="key">
								<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_REMOVE_FROM_JOOMLA_GROUPS'); ?>
							</td>
							<td>
								<?php echo JHtml::_('access.usergroup', 'remove_joomla_group_ids[]', $activeRemoveGroupIds, ' multiple="multiple" size="6" ', false); ?>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div class="span6 pull-left">
				<fieldset class="adminform">
					<legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_EXPIRED');?></legend>
					<table class="admintable adminform" style="width: 90%;">
						<tr>
							<td width="220" class="key">
								<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_REMOVE_FROM_JOOMLA_GROUPS'); ?>
							</td>
							<td>
								<?php
								echo JHtml::_('access.usergroup', 'joomla_expried_group_ids[]', $expiredRemoveGroupIds, ' multiple="multiple" size="6" ', false);
								?>
							</td>
						</tr>
						<tr>
							<td width="220" class="key">
								<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_ASSIGN_TO_JOOMLA_GROUPS'); ?>
							</td>
							<td>
								<?php echo JHtml::_('access.usergroup', 'subscription_expired_joomla_group_ids[]', $expiredGroupIds, ' multiple="multiple" size="6" ', false); ?>
							</td>
						</tr>						
					</table>
				</fieldset>
			</div>
		</div>
		<?php
	}
}
