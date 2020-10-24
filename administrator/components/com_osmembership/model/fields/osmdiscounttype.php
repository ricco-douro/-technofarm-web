<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldOSMDiscountType extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var string
	 */
	protected $type = 'osmdiscounttype';

	protected function getOptions()
	{
		$config  = OSMembershipHelper::getConfig();
		$options = [];
		$options[] = JHtml::_('select.option', 0, '%');
		$options[] = JHtml::_('select.option', 1, $config->currency_symbol);

		return $options;
	}
}
