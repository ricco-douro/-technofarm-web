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
 * Supports a checkbox list custom field.
 *
 * @package     Joomla.MPF
 * @subpackage  Form
 */
class MPFFormFieldCheckboxes extends MPFFormField
{
	/**
	 * The form field type.
	 *
	 * @var string
	 */
	protected $type = 'Checkboxes';
	/**
	 * @var mixed
	 */
	protected $values;

	/**
	 * MPFFormFieldCheckboxes constructor.
	 *
	 * @param $row
	 * @param $value
	 * @param $fieldSuffix
	 */
	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);

		$this->values = $row->values;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @param OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return string The field input markup.
	 */
	protected function getInput($bootstrapHelper = null)
	{
		$options = (array) $this->getOptions();

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

		$selectedOptions = array_map('trim', $selectedOptions);

		// Add uk-checkbox if UIKit3 is used
		if ($bootstrapHelper && $bootstrapHelper->getBootstrapVersion() === 'uikit3')
		{
			$this->addClass('uk-checkbox');
		}

		$data = [
			'name'            => $this->name,
			'options'         => $options,
			'selectedOptions' => $selectedOptions,
			'attributes'      => $this->buildAttributes(),
			'bootstrapHelper' => $bootstrapHelper,
			'row'             => $this->row,
		];

		return OSMembershipHelperHtml::loadCommonLayout('fieldlayout/checkboxes.php', $data);
	}

	/**
	 * Get checkboxes options
	 *
	 * @return array
	 */
	protected function getOptions()
	{
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
			$values = array($this->values);
		}

		$values = array_map('trim', $values);

		$values = array_filter($values, function ($value) {
			return strlen(trim($value)) > 0;
		});

		return $values;
	}
}
