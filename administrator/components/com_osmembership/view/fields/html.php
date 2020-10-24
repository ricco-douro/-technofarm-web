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
 * HTML View class for OS Membership Component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewFieldsHtml extends MPFViewList
{
	protected function prepareView()
	{
		parent::prepareView();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$options                = array();
		$options[]              = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options                = array_merge($options, $db->loadObjectList());
		$this->lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' onchange="submit();" ', 'id', 'title',
			$this->state->plan_id);

		$options                        = array();
		$options[]                      = JHtml::_('select.option', 1, JText::_('Show Core Fields'));
		$options[]                      = JHtml::_('select.option', 2, JText::_('Hide Core Fields'));
		$this->lists['show_core_field'] = JHtml::_('select.genericlist', $options, 'show_core_field', ' class="input-medium" onchange="submit();" ', 'value',
			'text', $this->state->show_core_field);

		$fieldTypes = array(
			'Text',
			'Url',
			'Email',
			'Number',
			'Tel',
			'Range',
			'Textarea',
			'List',
			'Checkboxes',
			'Radio',
			'Date',
			'Heading',
			'Message',
			'File',
			'Countries',
			'State',
			'SQL',
		);

		$options   = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_FIELD_TYPE'));

		foreach ($fieldTypes as $fieldType)
		{
			$options[] = JHtml::_('select.option', $fieldType, $fieldType);
		}

		$this->lists['filter_fieldtype'] = JHtml::_('select.genericlist', $options, 'filter_fieldtype', 'onchange="submit();"', 'value', 'text', $this->state->filter_fieldtype);

		$options   = array();
		$options[] = JHtml::_('select.option', -1, JText::_('OSM_FEE_FIELD'));
		$options[] = JHtml::_('select.option', 0, JText::_('JNO'));
		$options[] = JHtml::_('select.option', 1, JText::_('JYES'));

		$this->lists['filter_fee_field'] = JHtml::_('select.genericlist', $options, 'filter_fee_field', 'class="input-medium" onchange="submit();" ',
			'value', 'text', $this->state->filter_fee_field);
	}
}
