<?php
/**
 * @package     MPF
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2016 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla MPF.
 * Supports a generic list of options.
 *
 * @package     Joomla.MPF
 * @subpackage  Form
 */
class MPFFormFieldList extends MPFFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'List';

	/**
	 * Values used to genereate list options
	 *
	 * @var mixed
	 */
	protected $values;
	/**
	 * Is multiple
	 *
	 * @var bool
	 */

	protected $multiple = false;

	/**
	 * Constructor.
	 *
	 * @param OSMembershipTableField $row
	 * @param mixed                  $value
	 * @param string                 $fieldSuffix
	 */
	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);

		if ($row->size)
		{
			$this->attributes['size'] = $row->size;
		}

		if ($row->multiple)
		{
			$this->attributes['multiple'] = true;
			$this->multiple               = true;
		}

		$this->values = $row->values;
	}

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @param OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput($bootstrapHelper = null)
	{
		// Add uk-checkbox if UIKit3 is used
		if ($bootstrapHelper && $bootstrapHelper->getBootstrapVersion() === 'uikit3' && !$this->multiple)
		{
			$this->addClass('uk-select');
		}

		// Get the field options.
		$options    = (array) $this->getOptions();
		$attributes = $this->buildAttributes();

		if ($this->multiple)
		{
			if (is_array($this->value))
			{
				$selectedOptions = $this->value;
			}
			elseif (strpos($this->value, "\r\n"))
			{
				$selectedOptions = explode("\r\n", $this->value);
			}
			elseif (is_string($this->value) && is_array(json_decode($this->value)))
			{
				$selectedOptions = json_decode($this->value);
			}
			else
			{
				$selectedOptions = array($this->value);
			}

			$selectedOptions = array('trim', $selectedOptions);
		}
		else
		{
			$selectedOptions = trim($this->value);
		}

		return JHtml::_('select.genericlist', $options, $this->name . ($this->multiple ? '[]' : ''), trim($attributes), 'value', 'text', $selectedOptions);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects
	 */
	protected function getOptions()
	{
		$options   = [];
		$options[] = JHtml::_('select.option', '', $this->row->prompt_text ?: JText::_('OSM_SELECT'));

		if (is_array($this->values))
		{
			$values = $this->values;
		}
		elseif (strpos($this->values, "\r\n") !== false)
		{
			$values = explode("\r\n", $this->values);
		}
		else
		{
			$values = explode(",", $this->values);
		}

		$values = array_map('trim', $values);

		$values = array_filter($values, function ($value) {
			return strlen(trim($value)) > 0;
		});

		foreach ($values as $value)
		{
			$options[] = JHtml::_('select.option', trim($value), $value);
		}

		return $options;
	}
}
