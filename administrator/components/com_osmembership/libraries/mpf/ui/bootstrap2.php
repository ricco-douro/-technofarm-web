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
class MPFUiBootstrap2 extends MPFUiAbstract implements MPFUiInterface
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->classMaps = [
			'row-fluid'       => 'row-fluid',
			'span1'           => 'span1',
			'span2'           => 'span2',
			'span3'           => 'span3',
			'span4'           => 'span4',
			'span5'           => 'span5',
			'span6'           => 'span6',
			'span7'           => 'span7',
			'span8'           => 'span8',
			'span9'           => 'span9',
			'span10'          => 'span10',
			'span11'          => 'span11',
			'span12'          => 'span12',
			'pull-left'       => 'pull-left',
			'pull-right'      => 'pull-right',
			'btn'             => 'btn',
			'btn-mini'        => 'btn-mini',
			'btn-small'       => 'btn-small',
			'btn-large'       => 'btn-large',
			'visible-phone'   => 'visible-phone',
			'visible-tablet'  => 'visible-tablet',
			'visible-desktop' => 'visible-desktop',
			'hidden-phone'    => 'hidden-phone',
			'hidden-tablet'   => 'hidden-tablet',
			'hidden-desktop'  => 'hidden-desktop',
			'control-group'   => 'control-group',
			'input-prepend'   => 'input-prepend',
			'input-append'    => 'input-append',
			'add-on'          => 'add-on',
			'img-polaroid'    => 'img-polaroid',
			'control-label'   => 'control-label',
			'controls'        => 'controls',
			'nav'             => 'nav',
			'nav-stacked'     => 'nav-stacked',
			'nav-tabs'        => 'nav-tabs',
		];
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
		$html[] = '<div class="input-prepend">';
		$html[] = '<span class="add-on">' . $addOn . '</span>';
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
		$html[] = '<div class="input-append">';
		$html[] = $input;
		$html[] = '<span class="add-on">' . $addOn . '</span>';
		$html[] = '</div>';

		return implode("\n", $html);
	}
}