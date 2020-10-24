<?php
/**
 * @package     MPF
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2016 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class MPFFormFieldDate extends MPFFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Date';

	/**
	 * Method to get the field input markup.
	 *
	 * @var OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput($bootstrapHelper = null)
	{
		$config       = OSMembershipHelper::getConfig();
		$dateFormat   = $config->date_field_format ? $config->date_field_format : '%Y-%m-%d';
		$iconCalendar = $bootstrapHelper ? $bootstrapHelper->getClassMapping('icon-calendar') : 'icon-calendar';

		try
		{
			if (version_compare(JVERSION, '3.7.0', 'ge'))
			{
				return str_replace('icon-calendar', $iconCalendar, JHtml::_('calendar', $this->value, $this->name, $this->name, $dateFormat, $this->attributes));
			}
			else
			{
				$attributes = $this->buildAttributes();

				return str_replace('icon-calendar', $iconCalendar, JHtml::_('calendar', $this->value, $this->name, $this->name, $dateFormat, ".$attributes."));
			}
		}
		catch (Exception $e)
		{
			if (version_compare(JVERSION, '3.7.0', 'ge'))
			{
				return str_replace('icon-calendar', $iconCalendar, JHtml::_('calendar', '', $this->name, $this->name, $dateFormat, $this->attributes)) . ' Value <strong>' . $this->value . '</strong> is invalid. Please correct it with format YYYY-MM-DD';
			}
			else
			{
				$attributes = $this->buildAttributes();

				return str_replace('icon-calendar', $iconCalendar, JHtml::_('calendar', '', $this->name, $this->name, $dateFormat, ".$attributes.")) . ' Value <strong>' . $this->value . '</strong> is invalid. Please correct it with format YYYY-MM-DD';
			}

		}
	}
}
