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
class OSMembershipViewPlanHtml extends MPFViewItem
{
	protected function prepareView()
	{
		parent::prepareView();

		JPluginHelper::importPlugin('osmembership');
		$app    = JFactory::getApplication();
		$config = OSMembershipHelper::getConfig();
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);

		$item  = $this->item;
		$lists = &$this->lists;

		//Trigger plugins
		$results                         = $app->triggerEvent('onEditSubscriptionPlan', array($item));
		$lists['enable_renewal']         = OSMembershipHelperHtml::getBooleanInput('enable_renewal', $item->enable_renewal);
		$lists['lifetime_membership']    = OSMembershipHelperHtml::getBooleanInput('lifetime_membership', $item->lifetime_membership);
		$lists['recurring_subscription'] = OSMembershipHelperHtml::getBooleanInput('recurring_subscription', $item->recurring_subscription);
		$lists['thumb']                  = JHtml::_('list.images', 'thumb', $item->thumb, ' ', '/media/com_osmembership/');

		$lists['category_id'] = OSMembershipHelperHtml::buildCategoryDropdown($item->category_id, 'category_id');

		$options                           = array();
		$options[]                         = JHtml::_('select.option', 'D', JText::_('OSM_DAYS'));
		$options[]                         = JHtml::_('select.option', 'W', JText::_('OSM_WEEKS'));
		$options[]                         = JHtml::_('select.option', 'M', JText::_('OSM_MONTHS'));
		$options[]                         = JHtml::_('select.option', 'Y', JText::_('OSM_YEARS'));
		$lists['trial_duration_unit']      = JHtml::_('select.genericlist', $options, 'trial_duration_unit', ' class="input-medium" ', 'value', 'text',
			$item->trial_duration_unit);
		$lists['subscription_length_unit'] = JHtml::_('select.genericlist', $options, 'subscription_length_unit', ' class="input-medium" ', 'value', 'text',
			$item->subscription_length_unit);

		$this->renewOptionLengthUnits = $options;

		$options                          = array();
		$options[]                        = JHtml::_('select.option', 0, JText::_('%'));
		$options[]                        = JHtml::_('select.option', 1, $config->currency_symbol);
		$this->renewalDiscountTypeOptions = $options;

		$query->clear()
			->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->where('id != ' . (int) $item->id)
			->order('ordering');
		$db->setQuery($query);
		$this->plans = $db->loadObjectList();

		//Get list of renew and upgrade options
		if ($item->id > 0)
		{
			$query->clear()
				->select('*')
				->from('#__osmembership_renewrates')
				->where('plan_id = ' . $item->id)
				->order('id');
			$db->setQuery($query);
			$prices = $db->loadObjectList();

			$query->clear()
				->select('*')
				->from('#__osmembership_upgraderules')
				->where('from_plan_id = ' . $item->id)
				->order('id');
			$db->setQuery($query);
			$upgradeRules = $db->loadObjectList();

			$query->clear()
				->select('*')
				->from('#__osmembership_renewaldiscounts')
				->where('plan_id = ' . $item->id)
				->order('id');
			$db->setQuery($query);
			$renewalDiscounts = $db->loadObjectList();
		}
		else
		{
			$prices           = array();
			$upgradeRules     = array();
			$renewalDiscounts = array();
		}

		// Payment methods
		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_ALL_PAYMENT_METHODS'), 'id', 'title');
		$query->clear()
			->select('id, title')
			->from('#__osmembership_plugins')
			->where('published=1');
		$db->setQuery($query);
		$lists['payment_methods'] = JHtml::_('select.genericlist', array_merge($options, $db->loadObjectList()), 'payment_methods[]', ' class="inputbox" multiple="multiple" ', 'id', 'title', explode(',', $item->payment_methods));

		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PENDING'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_ACTIVE'));

		$lists['free_plan_subscription_status'] = JHtml::_('select.genericlist', $options, 'free_plan_subscription_status', '', 'value', 'text', $item->id ? $item->free_plan_subscription_status : 1);

		// Login redirect
		require_once JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php';

		$groups = array();
		$items  = MenusHelper::getMenuLinks();

		foreach ($items as $menu)
		{
			$groups[$menu->menutype] = array();

			foreach ($menu->links as $link)
			{
				$groups[$menu->menutype][] = JHtml::_('select.option', $link->value, $link->text);
			}
		}

		array_unshift($groups, array(JHtml::_('select.option', 0, JText::_('OSM_SELECT_MENU_ITEM'))));

		$lists['login_redirect_menu_id'] = JHtml::_(
			'select.groupedlist', $groups, 'login_redirect_menu_id',
			array('id'                 => 'menu_item', 'list.select' => $item->login_redirect_menu_id, 'group.items' => null, 'option.key.toHtml' => false,
			      'option.text.toHtml' => false,
			)
		);

		// Currency code
		$currencies = require_once JPATH_ROOT . '/components/com_osmembership/helper/currencies.php';
		$options    = array();
		$options[]  = JHtml::_('select.option', '', JText::_('OSM_DEFAULT_CURRENCY'));

		foreach ($currencies as $code => $title)
		{
			$options[] = JHtml::_('select.option', $code, $title);
		}

		$lists['currency'] = JHtml::_('select.genericlist', $options, 'currency', ' class="inputbox" ', 'value', 'text', $item->currency);

		$options   = array();
		$options[] = JHtml::_('select.option', '0', JText::_('OSM_NO'));
		$options[] = JHtml::_('select.option', '1', JText::_('OSM_BY_DAYS'));
		$options[] = JHtml::_('select.option', '2', JText::_('OSM_BY_MONTHS'));

		$lists['prorated_signup_cost'] = JHtml::_('select.genericlist', $options, 'prorated_signup_cost', ' class="input-large" ', 'value', 'text',
			$item->prorated_signup_cost);

		$options   = [];
		$options[] = JHtml::_('select.option', '1', JText::_('OSM_BEFORE'));
		$options[] = JHtml::_('select.option', '-1', JText::_('OSM_AFTER'));

		$lists['send_first_reminder_time']  = JHtml::_('select.genericlist', $options, 'send_first_reminder_time', ' class="input-small" ', 'value', 'text',
			$item->send_first_reminder >= 0 ? 1 : -1);
		$lists['send_second_reminder_time'] = JHtml::_('select.genericlist', $options, 'send_second_reminder_time', ' class="input-small" ', 'value', 'text',
			$item->send_second_reminder >= 0 ? 1 : -1);
		$lists['send_third_reminder_time']  = JHtml::_('select.genericlist', $options, 'send_third_reminder_time', ' class="input-small" ', 'value', 'text',
			$item->send_third_reminder >= 0 ? 1 : -1);
		$lists['send_subscription_end_time']  = JHtml::_('select.genericlist', $options, 'send_subscription_end_time', ' class="input-small" ', 'value', 'text',
			$item->send_subscription_end >= 0 ? 1 : -1);

		$item->send_first_reminder  = abs($item->send_first_reminder);
		$item->send_second_reminder = abs($item->send_second_reminder);
		$item->send_third_reminder  = abs($item->send_third_reminder);

		$this->datePickerFormat = $config->get('date_field_format', '%Y-%m-%d');
		$this->prices           = $prices;
		$this->upgradeRules     = $upgradeRules;
		$this->renewalDiscounts = $renewalDiscounts;
		$this->plugins          = $results;
		$this->nullDate         = $db->getNullDate();
		$this->config           = $config;

		$dateFields = ['expired_date', 'publish_up', 'publish_down'];

		foreach ($dateFields as $dateField)
		{
			if ($this->item->{$dateField} == $this->nullDate)
			{
				$this->item->{$dateField} = '';
			}
		}

		return true;
	}
}
