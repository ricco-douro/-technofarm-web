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
 * Supports a textarea inut.
 *
 * @package     Joomla.MPF
 * @subpackage  Form
 */
class MPFFormFieldTextarea extends MPFFormField
{
	protected $type = 'Textarea';

	/**
	 * Visable attributes, which will be displayed on field settings form
	 *
	 * @var array
	 */
	public static $visibleProperties = array('rows', 'cols', 'place_holder', 'max_length');

	/**
	 * Required properties, which will be used for js validate before the field is saved
	 *
	 * @var array
	 */
	public static $requiredProperties = array();

	/**
	 * MPFFormFieldText constructor.
	 *
	 * @param OSMembershipTableField $row
	 * @param mixed                  $value
	 * @param string                 $fieldSuffix
	 */
	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);

		if ($row->place_holder)
		{
			$this->attributes['placeholder'] = $row->place_holder;
		}

		if ($row->max_length)
		{
			$this->attributes['maxlength'] = $row->max_length;
		}

		if ($row->rows)
		{
			$this->attributes['rows'] = $row->rows;
		}

		if ($row->cols)
		{
			$this->attributes['cols'] = $row->cols;
		}
	}

	/**
	 * Get the field input markup.
	 *
	 * @param OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return string
	 */
	public function getInput($bootstrapHelper = null)
	{
		// Add uk-textarea to input elements
		if ($bootstrapHelper && $bootstrapHelper->getBootstrapVersion() === 'uikit3')
		{
			$this->addClass('uk-textarea');
		}

		$data = [
			'name'       => $this->name,
			'value'      => $this->value,
			'attributes' => $this->buildAttributes(),
			'row'        => $this->row,
		];

		return OSMembershipHelperHtml::loadCommonLayout('fieldlayout/textarea.php', $data);
	}
}
