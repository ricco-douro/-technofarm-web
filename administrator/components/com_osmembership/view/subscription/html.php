<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewSubscriptionHtml extends MPFViewItem
{
	/**
	 * Determine whether we can cancel the recurring subscription
	 *
	 * @var bool
	 */
	protected $canCancelSubscription = false;

	/**
	 * Determine whether we can refund the subscription
	 *
	 * @var bool
	 */
	protected $canRefundSubscription = false;

	/**
	 * Prepare view data
	 *
	 * @throws Exception
	 */
	protected function prepareView()
	{
		parent::prepareView();

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$item   = $this->item;
		$lists  = &$this->lists;
		$config = OSMembershipHelper::getConfig();

		if ($item->id == 0)
		{
			$item->plan_id = $this->input->getInt('plan_id', 0);
		}

		$query->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);

		$options          = array();
		$options[]        = JHtml::_('select.option', '', JText::_('OSM_SELECT_PLAN'), 'id', 'title');
		$options          = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="input-large validate[required]" ', 'id', 'title', $item->plan_id);

		//Subscription status
		$options            = array();
		$options[]          = JHtml::_('select.option', -1, JText::_('OSM_ALL'));
		$options[]          = JHtml::_('select.option', 0, JText::_('OSM_PENDING'));
		$options[]          = JHtml::_('select.option', 1, JText::_('OSM_ACTIVE'));
		$options[]          = JHtml::_('select.option', 2, JText::_('OSM_EXPIRED'));
		$options[]          = JHtml::_('select.option', 3, JText::_('OSM_CANCELLED_PENDING'));
		$options[]          = JHtml::_('select.option', 4, JText::_('OSM_CANCELLED_REFUNDED'));
		$lists['published'] = JHtml::_('select.genericlist', $options, 'published', ' class="inputbox" ', 'value', 'text', $item->published);

		//Get list of payment methods
		$query->clear()
			->select('name, title')
			->from('#__osmembership_plugins')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);

		$options                 = array();
		$options[]               = JHtml::_('select.option', '', JText::_('OSM_PAYMENT_METHOD'), 'name', 'title');
		$options                 = array_merge($options, $db->loadObjectList());
		$lists['payment_method'] = JHtml::_('select.genericlist', $options, 'payment_method', ' class="inputbox" ', 'name', 'title',
			$item->payment_method);
		$rowFields               = OSMembershipHelper::getProfileFields($item->plan_id, true, $item->language, $item->act);
		$data                    = array();

		if ($item->id)
		{
			$data       = OSMembershipHelper::getProfileData($item, $item->plan_id, $rowFields);
			$setDefault = false;
		}
		else
		{
			$setDefault = true;
		}

		if (!isset($data['country']) || !$data['country'])
		{
			$data['country'] = $config->default_country;
		}

		$form = new MPFForm($rowFields);
		$form->setData($data)->bindData($setDefault);
		$form->buildFieldsDependency();

		//Custom fields processing goes here
		if ($item->plan_id)
		{
			$plan                         = OSMembershipHelperDatabase::getPlan($item->plan_id);
			$item->lifetime_membership    = (int) $plan->lifetime_membership;
			$item->recurring_subscription = (int) $plan->recurring_subscription;
		}
		else
		{
			$item->lifetime_membership = 0;
		}

		// Convert dates from UTC to user timezone
		if ($item->id)
		{
			$item->created_date = JHtml::_('date', $item->created_date, 'Y-m-d H:i:s');
			$item->from_date    = JHtml::_('date', $item->from_date, 'Y-m-d H:i:s');
			$item->to_date      = JHtml::_('date', $item->to_date, 'Y-m-d H:i:s');
		}

		OSMembershipHelper::addLangLinkForAjax();

		// Support cancel recurring subscription from backend if the payment gateway support it
		if ($item->id > 0 && $item->payment_method)
		{
			try
			{
				$method = OSMembershipHelper::loadPaymentMethod($item->payment_method);

				if ($item->subscription_id && !$item->recurring_subscription_cancelled && $method && method_exists($method, 'cancelSubscription'))
				{
					$this->canCancelSubscription = true;
				}

				if (OSMembershipHelper::canRefundSubscription($item))
				{
					$this->canRefundSubscription = true;
				}
			}
			catch (Exception $e)
			{
				// Payment method doesn't exist for some reasons, ignore it
			}
		}

		$this->datePickerFormat = $config->get('date_field_format', '%Y-%m-%d');
		$this->config           = $config;
		$this->form             = $form;
	}
}
