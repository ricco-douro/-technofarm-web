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
 *
 * @property OSMembershipModelCoupon $model
 */
class OSMembershipViewCouponHtml extends MPFViewItem
{
	protected function prepareView()
	{
		parent::prepareView();

		$config = OSMembershipHelper::getConfig();
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options   = array_merge($options, $db->loadObjectList());

		if ($this->item->id)
		{
			$query->clear()
				->select('plan_id')
				->from('#__osmembership_coupon_plans')
				->where('coupon_id = ' . $this->item->id);
			$db->setQuery($query);
			$planIds = $db->loadColumn();

			if (count($planIds) == 0)
			{
				$planIds = array(0);
			}
		}
		else
		{
			$planIds = array(0);
		}

		$this->lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id[]', ' multiple="multiple" ', 'id', 'title', $planIds);

		$options                    = array();
		$options[]                  = JHtml::_('select.option', 0, JText::_('%'));
		$options[]                  = JHtml::_('select.option', 1, '$');
		$this->lists['coupon_type'] = JHtml::_('select.genericlist', $options, 'coupon_type', ' class="input-small" ', 'value', 'text', $this->item->coupon_type);

		$options                  = array();
		$options[]                = JHtml::_('select.option', 0, JText::_('OSM_ALL_PAYMENTS'));
		$options[]                = JHtml::_('select.option', 1, JText::_('OSM_ONLY_FIRST_PAYMENT'));
		$this->lists['apply_for'] = JHtml::_('select.genericlist', $options, 'apply_for', '', 'value', 'text', $this->item->apply_for);
		$this->subscriptions      = $this->model->getSubscriptions();

		$this->datePickerFormat = $config->get('date_field_format', '%Y-%m-%d');
		$this->nullDate         = $db->getNullDate();
		$this->config           = $config;

		$dateFields = ['valid_from', 'valid_to'];

		foreach ($dateFields as $dateField)
		{
			if ($this->item->{$dateField} == $this->nullDate)
			{
				$this->item->{$dateField} = '';
			}
		}
	}


	/**
	 * Override addToolbar method, only add toolbar for default layout
	 */
	protected function addToolbar()
	{
		if ($this->getLayout() == 'default')
		{
			parent::addToolbar();
		}
	}
}
