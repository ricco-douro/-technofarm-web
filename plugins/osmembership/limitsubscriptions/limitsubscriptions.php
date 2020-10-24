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

class plgOSMembershipLimitSubscriptions extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Render setting form
	 *
	 * @param PlanOSMembership $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_MAX_SUBCRIPTIONS_SETTING'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database, in this case, use params field of plans table
	 *
	 * @param PlanOsMembership $row
	 * @param bool             $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$params = new Registry($row->params);
		$params->set('max_subscriptions', $data['max_subscriptions']);
		$row->params = $params->toString();
		$row->store();
	}

	/**
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipActive($row)
	{
		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params           = new Registry($plan->params);
		$maxSubscriptions = (int) $params->get('max_subscriptions', 0);

		if (!$maxSubscriptions)
		{
			return;
		}

		$query = $this->db->getQuery(true);
		$query->select('COUNT(id)')
			->from('#__osmembership_subscribers')
			->where('plan_id = '. (int) $row->plan_id)
			->where('published IN (1,2)');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();
		if ($total >= $maxSubscriptions)
		{
			$plan->published = 0;
			$plan->store();
		}
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$params = new Registry($row->params);
		$maxSubscriptions = $params->get('max_subscriptions', '')
		?>
		<table class="admintable adminform" style="width: 90%;">
			<tr>
				<td width="220" class="key">
					<?php echo JText::_('PLG_OSMEMBERSHIP_MAX_SUBSCRIPTIONS'); ?>
				</td>
				<td>
					<input type="text" class="input-small" name="max_subscriptions" value="<?php echo $maxSubscriptions; ?>" />
				</td>
				<td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_MAX_SUBSCRIPTIONS_EXPLAIN'); ?>
				</td>
			</tr>
		</table>
		<?php
	}
}
