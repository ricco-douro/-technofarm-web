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

class plgOSMembershipAutoSubscribe extends JPlugin
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

		return array('title' => JText::_('PLG_AUTO_SUBSCRIBE'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database, in this case, use params field of plans table
	 *
	 * @param OSMembershipTablePlan $row
	 * @param bool                  $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$params = new Registry($row->params);

		if (isset($data['auto_subscribe_plan_ids']))
		{
			$autoSubscribePlanIds = implode(',', $data['auto_subscribe_plan_ids']);
		}
		else
		{
			$autoSubscribePlanIds = '';
		}

		$params->set('auto_subscribe_plan_ids', $autoSubscribePlanIds);
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
		if ($row->auto_subscribe_processed)
		{
			return;
		}

		/* @var OSMembershipTablePlan $rowPlan */
		$rowPlan = JTable::getInstance('Plan', 'OSMembershipTable');
		$rowPlan->load($row->plan_id);
		$params  = new Registry($rowPlan->params);
		$planIds = explode(',', $params->get('auto_subscribe_plan_ids', ''));
		$planIds = array_filter($planIds);

		if (empty($planIds))
		{
			return;
		}

		/* @var OSMembershipModelApi $model */
		$model = MPFModel::getInstance('Api', 'OSMembershipModel', ['ignore_request' => true, 'remember_states' => false]);

		// First, get details information about the subscription
		$data              = $model->getSubscriptionData($row->id);
		$data['published'] = 1;
		$data['user_id']   = $row->user_id;
		$data['parent_id'] = $row->id;

		// Reset amount data, set it to 0  for the auto-subscribed subscription
		$data['amount'] = $data['discount_amount'] = $data['tax_amount'] = $data['payment_processing_fee'] = $data['tax_rate'] = 0;

		foreach ($planIds as $planId)
		{
			$data['plan_id'] = $planId;

			try
			{
				$model->store($data);
			}
			catch (Exception $e)
			{

			}
		}
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param OSMembershipTablePlan $row
	 */
	private function drawSettingForm($row)
	{
		$params  = new Registry($row->params);
		$planIds = explode(',', $params->get('auto_subscribe_plan_ids', ''));
		$planIds = array_filter($planIds);

		$query = $this->db->getQuery(true)
			->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->order('title');

		if ($row->id > 0)
		{
			$query->where('id != ' . $row->id);
		}

		$this->db->setQuery($query);

		$options = [];

		foreach ($this->db->loadObjectList() as $plan)
		{
			$options[] = JHtml::_('select.option', $plan->id, $plan->title);
		}
		?>
        <div class="control-group">
            <div class="control-label">
				<?php echo OSMembershipHelperHtml::getFieldLabel('auto_subscribe_plan_ids', JText::_('OSM_SELECT_PLANS'), JText::_('OSM_AUTO_SUBSCRIBE_PLAN_IDS_EXPLAIN')); ?>
            </div>
            <div class="controls">
				<?php echo JHtml::_('select.genericlist', $options, 'auto_subscribe_plan_ids[]', 'class="advSelect" multiple="multiple" size="10"', 'value', 'text', $planIds) ?>
            </div>
        </div>
		<?php
	}
}
