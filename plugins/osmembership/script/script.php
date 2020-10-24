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

class plgOSMembershipScript extends JPlugin
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
	 * @param $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_SCRIPTS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database
	 *
	 * @param PlanOsMembership $row
	 * @param Boolean          $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$params = new Registry($row->params);
		$params->set('subscription_store_script', $data['subscription_store_script']);
		$params->set('subscription_active_script', $data['subscription_active_script']);
		$params->set('subscription_expired_script', $data['subscription_expired_script']);
		$row->params = $params->toString();

		$row->store();
	}

	/**
	 * Run the PHP script when subscription is stored in database
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	public function onAfterStoreSubscription($row)
	{
		$params = $this->getPlanParams($row->plan_id);
		$script = trim($params->get('subscription_store_script'));
		if ($script)
		{
			try
			{
				eval($script);
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::_('The PHP script is wrong. Please contact Administrator'), 'error');
			}
		}

		return true;
	}

	/**
	 * Run the PHP script when membership is activated
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	public function onMembershipActive($row)
	{
		$params = $this->getPlanParams($row->plan_id);
		$script = trim($params->get('subscription_active_script'));
		if ($script)
		{
			try
			{
				eval($script);
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::_('The PHP script is wrong. Please contact Administrator'), 'error');
			}
		}

		return true;
	}

	/**
	 * Run the PHP script when membership expired
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	public function onMembershipExpire($row)
	{
		$params = $this->getPlanParams($row->plan_id);
		$script = trim($params->get('subscription_expired_script'));
		if ($script)
		{
			try
			{
				eval($script);
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::_('The PHP script is wrong. Please contact Administrator'), 'error');
			}
		}

		return true;
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$params = new Registry($row->params);
		?>
		<table class="admintable adminform" style="width: 90%;">
			<tr>
				<td colspan="2">
					<div class="text-error" style="font-size: 16px;">
						This feature is usually used by developers that know how to write PHP code. Please only use this feature if you know how to program in PHP and understand what you are doing.
					</div>
				</td>
			</tr>
			<tr>
				<td width="220" class="key">
					<?php echo JText::_('OSM_SUBSCRIPTION_STORED_SCRIPT'); ?>
				</td>
				<td>
					<textarea rows="10" cols="70" class="input-xxlarge" name="subscription_store_script"><?php echo $params->get('subscription_store_script'); ?></textarea>
				</td>
				<td>
					<?php echo JText::_('OSM_SUBSCRIPTION_STORED_SCRIPT_EXPLAIN'); ?>
				</td>
			</tr>
			<tr>
				<td width="220" class="key">
					<?php echo JText::_('OSM_SUBSCRIPTION_ACTIVE_SCRIPT'); ?>
				</td>
				<td>
					<textarea rows="10" cols="70" class="input-xxlarge" name="subscription_active_script"><?php echo $params->get('subscription_active_script'); ?></textarea>
				</td>
				<td>
					<?php echo JText::_('OSM_SUBSCRIPTION_ACTIVE_SCRIPT_EXPLAIN'); ?>
				</td>
			</tr>
			<tr>
				<td width="220" class="key">
					<?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED_SCRIPT'); ?>
				</td>
				<td>
					<textarea rows="10" cols="70" class="input-xxlarge" name="subscription_expired_script"><?php echo $params->get('subscription_expired_script'); ?></textarea>
				</td>
				<td>
					<?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED_SCRIPT_EXPLAIN'); ?>
				</td>
			</tr>
		</table>
	<?php
	}

	/**
	 * The params of the subscription plan
	 *
	 * @param $planId
	 *
	 * @return Registry
	 */
	private function getPlanParams($planId)
	{
		$query = $this->db->getQuery(true);
		$query->select('params')
			->from('#__osmembership_plans')
			->where('id = ' . $planId);
		$this->db->setQuery($query);

		return new Registry($this->db->loadResult());
	}
}
