<?php
/**
 * @package     MPF
 * @subpackage  UI
 *
 * @copyright   Copyright (C) 2016 - 2018 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


/**
 * Base class for a Joomla Administrator Controller. It handles add, edit, delete, publish, unpublish records....
 *
 * @package       MPF
 * @subpackage    UI
 * @since         2.0
 */
class MPFUiUikit3 extends MPFUiAbstract implements MPFUiInterface
{
	/**
	 * UIKit framework classes
	 *
	 * @var array
	 */
	protected $frameworkClasses = [
		'uk-input',
		'uk-select',
		'uk-textarea',
		'uk-radio',
		'uk-checkbox',
		'uk-legend',
		'uk-range',
		'uk-fieldset',
		'uk-legend',
	];

	/**
	 * Constructor
	 *
	 * @param array $classMaps
	 */
	public function __construct($classMaps = array())
	{
		if (empty($classMaps))
		{
			$classMaps = [
				'row-fluid'                                      => 'uk-container uk-grid',
				'span2'                                          => 'uk-width-1-6',
				'span3'                                          => 'uk-width-1-4',
				'span4'                                          => 'uk-width-1-3',
				'span5'                                          => 'uk-width-1-2',
				'span6'                                          => 'uk-width-1-2',
				'span7'                                          => 'uk-width-1-2',
				'span8'                                          => 'uk-width-2-3',
				'span9'                                          => 'uk-width-3-4',
				'span10'                                         => 'uk-width-5-6',
				'span12'                                         => 'uk-width-1-1',
				'pull-left'                                      => 'uk-float-left',
				'pull-right'                                     => 'uk-float-right',
				'clearfix'                                       => 'uk-clearfix',
				'btn'                                            => 'uk-button uk-button-default',
				'btn-primary'                                    => 'uk-button-primary',
				'btn-mini'                                       => 'uk-button uk-button-default uk-button-small',
				'btn-small'                                      => 'uk-button uk-button-default uk-button-small',
				'btn-large'                                      => 'uk-button uk-button-default uk-button-large',
				'hidden-phone'                                   => 'uk-hidden@s',
				'form form-horizontal'                           => 'uk-form-horizontal',
				'control-label'                                  => 'uk-form-label',
				'controls'                                       => 'uk-form-controls uk-form-controls-text',
				'input-tiny'                                     => 'uk-input uk-form-width-xsmall',
				'input-small'                                    => 'uk-input uk-form-width-small',
				'input-medium'                                   => 'uk-input uk-form-width-medium',
				'input-large'                                    => 'uk-input uk-form-width-large',
				'center'                                         => 'uk-text-center',
				'text-center'                                    => 'uk-text-center',
				'row-fluid clearfix'                             => 'uk-container uk-grid uk-clearfix',
				'btn btn-primary'                                => 'uk-button uk-button-default uk-button-primary',
				'table table-striped table-bordered'             => 'uk-table uk-table-striped uk-table-bordered',
				'table table-striped table-bordered table-hover' => 'uk-table uk-table-striped uk-table-bordered uk-table-hover',
			];
		}

		$this->classMaps = $classMaps;
	}

	/**
	 * Get the mapping of a given class
	 *
	 * @param string $class The input class
	 *
	 * @return string The mapped class
	 */
	public function getClassMapping($class)
	{
		// Handle icon class
		if (strpos($class, 'icon-') !== false)
		{
			$icon = substr($class, 5);

			return 'fa fa-' . $icon;
		}

		return parent::getClassMapping($class);
	}

	/**
	 * Method to render input with prepend add-on
	 *
	 * @param string $input
	 * @param string $addOn
	 *
	 * @return mixed
	 */
	public function getPrependAddon($input, $addOn)
	{
		$html   = [];
		$html[] = '<div class="uk-inline">';
		$html[] = '<span class="uk-form-icon">' . $addOn . '</span>';
		$html[] = $input;
		$html[] = '</div>';

		return implode("\n", $html);
	}

	/**
	 * Method to render input with append add-on
	 *
	 * @param string $input
	 * @param string $addOn
	 *
	 * @return string
	 */
	public function getAppendAddon($input, $addOn)
	{
		$html   = [];
		$html[] = '<div class="uk-inline">';
		$html[] = $input;
		$html[] = '<span class="uk-form-icon">' . $addOn . '</span>';
		$html[] = '</div>';

		return implode("\n", $html);
	}
}