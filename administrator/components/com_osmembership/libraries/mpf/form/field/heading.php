<?php
/**
 * @package     MPF
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2016 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class MPFFormFieldHeading extends MPFFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Heading';

	/**
	 * Method to get the field input markup.
	 *
	 * @param OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return string The field input markup.
	 */
	protected function getInput($bootstrapHelper = null)
	{
		$controlGroupAttributes = 'id="field_' . $this->name . '" ';

		if (!$this->visible)
		{
			$controlGroupAttributes .= ' style="display:none;" ';
		}

		$data = [
			'controlGroupAttributes' => $controlGroupAttributes,
			'title'                  => $this->title,
			'row'                    => $this->row,
		];

		return OSMembershipHelperHtml::loadCommonLayout('fieldlayout/heading.php', $data);
	}

	/**
	 * Get control group used to display on form
	 *
	 * @param OSMembershipHelperBootstrap $bootstrapHelper
	 *
	 * @return string
	 */
	public function getControlGroup($bootstrapHelper = null)
	{
		return $this->getInput($bootstrapHelper);
	}
}
