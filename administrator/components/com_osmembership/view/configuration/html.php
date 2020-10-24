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
class OSMembershipViewConfigurationHtml extends MPFViewHtml
{
	public function display()
	{
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$config = OSMembershipHelper::getConfig();

		$options   = array();
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_VERSION_2'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_VERSION_3'));
		$options[] = JHtml::_('select.option', 4, JText::_('OSM_VERSION_4'));
		$options[] = JHtml::_('select.option', 'uikit3', JText::_('OSM_UIKIT_3'));

		$lists['twitter_bootstrap_version'] = JHtml::_('select.genericlist', $options, 'twitter_bootstrap_version', '', 'value', 'text', $config->twitter_bootstrap_version ? $config->twitter_bootstrap_version : 2);

		$currencies = require_once JPATH_ROOT . '/components/com_osmembership/helper/currencies.php';
		$options    = array();
		$options[]  = JHtml::_('select.option', '', JText::_('OSM_SELECT_CURRENCY'));

		foreach ($currencies as $code => $title)
		{
			$options[] = JHtml::_('select.option', $code, $title);
		}

		$lists['currency_code'] = JHtml::_('select.genericlist', $options, 'currency_code', '', 'value', 'text', isset($config->currency_code) ? $config->currency_code : 'USD');

		$options   = array();
		$options[] = JHtml::_('select.option', 'm', JText::_('OSM_MINUTES'));
		$options[] = JHtml::_('select.option', 'h', JText::_('OSM_HOURS'));
		$options[] = JHtml::_('select.option', 'd', JText::_('OSM_DAYS'));

		$lists['grace_period_unit'] = JHtml::_('select.genericlist', $options, 'grace_period_unit', ' class="input-small" ', 'value', 'text', $config->get('grace_period_unit', 'd'));

		$options   = array();
		$options[] = JHtml::_('select.option', 'default', JText::_('OSM_DEFAULT_LAYOUT'));
		$options[] = JHtml::_('select.option', 'columns', JText::_('OSM_COLUMNS_LAYOUT'));

		$lists['subscription_form_layout'] = JHtml::_('select.genericlist', $options, 'subscription_form_layout', 'class="input-medium"', 'value', 'text', $config->get('subscription_form_layout', ''));

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_POSITION'));
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_BEFORE_AMOUNT'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_AFTER_AMOUNT'));

		$lists['currency_position'] = JHtml::_('select.genericlist', $options, 'currency_position', ' class="inputbox"', 'value', 'text', $config->currency_position);

		// EU VAT Number field selection
		$query->select('name, title')
			->from('#__osmembership_fields')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT'), 'name', 'title');
		$options   = array_merge($options, $db->loadObjectList());

		$lists['eu_vat_number_field'] = JHtml::_('select.genericlist', $options, 'eu_vat_number_field', ' class="inputbox"', 'name', 'title', $config->eu_vat_number_field);

		//Get list of country
		$query->clear()
			->select('name AS value, name AS text')
			->from('#__osmembership_countries')
			->where('published = 1')
			->order('name');
		$db->setQuery($query);

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_DEFAULT_COUNTRY'));
		$options   = array_merge($options, $db->loadObjectList());

		$lists['country_list'] = JHtml::_('select.genericlist', $options, 'default_country', '', 'value', 'text', $config->default_country);

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_FORMAT'));
		$options[] = JHtml::_('select.option', '%Y-%m-%d', 'Y-m-d');
		$options[] = JHtml::_('select.option', '%Y/%m/%d', 'Y/m/d');
		$options[] = JHtml::_('select.option', '%Y.%m.%d', 'Y.m.d');
		$options[] = JHtml::_('select.option', '%m-%d-%Y', 'm-d-Y');
		$options[] = JHtml::_('select.option', '%m/%d/%Y', 'm/d/Y');
		$options[] = JHtml::_('select.option', '%m.%d.%Y', 'm.d.Y');
		$options[] = JHtml::_('select.option', '%d-%m-%Y', 'd-m-Y');
		$options[] = JHtml::_('select.option', '%d/%m/%Y', 'd/m/Y');
		$options[] = JHtml::_('select.option', '%d.%m.%Y', 'd.m.Y');

		$lists['date_field_format'] = JHtml::_('select.genericlist', $options, 'date_field_format', '', 'value', 'text', isset($config->date_field_format) ? $config->date_field_format : 'Y-m-d');

		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_NO'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_YES'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_ONLY_FOR_PUBLIC_USER'));

		$lists['enable_captcha'] = JHtml::_('select.genericlist', $options, 'enable_captcha', '', 'value', 'text', $config->enable_captcha);

		$options   = array();
		$options[] = JHtml::_('select.option', 'new_subscription_emails', JText::_('OSM_NEW_SUBSCRIPTION_EMAILS'));
		$options[] = JHtml::_('select.option', 'subscription_renewal_emails', JText::_('OSM_SUBSCRIPTION_RENEWAL_EMAILS'));
		$options[] = JHtml::_('select.option', 'subscription_upgrade_emails', JText::_('OSM_SUBSCRIPTION_UPGRADE_EMAILS'));
		$options[] = JHtml::_('select.option', 'subscription_approved_emails', JText::_('OSM_SUBSCRIPTION_APPROVED_EMAILS'));
		$options[] = JHtml::_('select.option', 'subscription_cancel_emails', JText::_('OSM_SUBSCRIPTION_CANCEL_EMAILS'));
		$options[] = JHtml::_('select.option', 'profile_updated_emails', JText::_('OSM_PROFILE_UPDATED_EMAILS'));
		$options[] = JHtml::_('select.option', 'first_reminder_emails', JText::_('OSM_FIRST_REMINDER_EMAILS'));
		$options[] = JHtml::_('select.option', 'second_reminder_emails', JText::_('OSM_SECOND_REMINDER_EMAILS'));
		$options[] = JHtml::_('select.option', 'third_reminder_emails', JText::_('OSM_THIRD_REMINDER_EMAILS'));
		$options[] = JHtml::_('select.option', 'subscription_end_emails', JText::_('OSM_SUBSCRIPTION_END_EMAILS'));
		$options[] = JHtml::_('select.option', 'mass_mails', JText::_('OSM_MASS_MAILS'));

		$lists['log_email_types'] = JHtml::_('select.genericlist', $options, 'log_email_types[]', ' multiple="multiple" ', 'value', 'text', empty($config->log_email_types) ? array() : explode(',', $config->log_email_types));

		$options   = array();
		$options[] = JHtml::_('select.option', 'csv', JText::_('OSM_FILE_CSV'));
		$options[] = JHtml::_('select.option', 'xls', JText::_('OSM_FILE_EXCEL_2003'));
		$options[] = JHtml::_('select.option', 'xlsx', JText::_('OSM_FILE_EXCEL_2007'));

		$lists['export_data_format'] = JHtml::_('select.genericlist', $options, 'export_data_format', '', 'value', 'text', empty($config->export_data_format) ? 'xlsx' : $config->export_data_format);

		$options   = array();
		$options[] = JHtml::_('select.option', 'create_subscription', JText::_('OSM_CREATE_NEW_SUBSCRIPTION'));
		$options[] = JHtml::_('select.option', 'update_subscription', JText::_('OSM_UPDATE_SUBSCRIPTION'));

		$lists['subscription_renew_behavior'] = JHtml::_('select.genericlist', $options, 'subscription_renew_behavior', ' class="input-xlarge" ', 'value', 'text', $config->subscription_renew_behavior ? $config->subscription_renew_behavior : 'create_subscription');

		$options   = array();
		$options[] = JHtml::_('select.option', 'P', JText::_('Portrait'));
		$options[] = JHtml::_('select.option', 'L', JText::_('Landscape'));

		$lists['card_page_orientation'] = JHtml::_('select.genericlist', $options, 'card_page_orientation', '', 'value', 'text', $config->get('card_page_orientation', 'P'));

		$options   = array();
		$options[] = JHtml::_('select.option', 'A4', JText::_('A4'));
		$options[] = JHtml::_('select.option', 'A5', JText::_('A5'));
		$options[] = JHtml::_('select.option', 'A6', JText::_('A6'));
		$options[] = JHtml::_('select.option', 'A7', JText::_('A7'));

		$lists['card_page_format'] = JHtml::_('select.genericlist', $options, 'card_page_format', '', 'value', 'text', $config->get('card_page_format', 'A4'));

		$fontsPath = JPATH_ROOT . '/components/com_osmembership/tcpdf/fonts/';

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_FONT'));
		$options[] = JHtml::_('select.option', 'courier', JText::_('Courier'));
		$options[] = JHtml::_('select.option', 'helvetica', JText::_('Helvetica'));
		$options[] = JHtml::_('select.option', 'symbol', JText::_('Symbol'));
		$options[] = JHtml::_('select.option', 'times', JText::_('Times New Roman'));
		$options[] = JHtml::_('select.option', 'zapfdingbats', JText::_('Zapf Dingbats'));

		$additionalFonts = array(
			'aealarabiya',
			'aefurat',
			'dejavusans',
			'dejavuserif',
			'freemono',
			'freesans',
			'freeserif',
			'hysmyeongjostdmedium',
			'kozgopromedium',
			'kozminproregular',
			'msungstdlight',
		);

		foreach ($additionalFonts as $fontName)
		{
			if (file_exists($fontsPath . $fontName . '.php'))
			{
				$options[] = JHtml::_('select.option', $fontName, ucfirst($fontName));
			}
		}

		$lists['pdf_font'] = JHtml::_('select.genericlist', $options, 'pdf_font', ' class="inputbox"', 'value', 'text', empty($config->pdf_font) ? 'times' : $config->pdf_font);

		$this->lists     = $lists;
		$this->config    = $config;
		$this->languages = OSMembershipHelper::getLanguages();

		parent::display();
	}
}
