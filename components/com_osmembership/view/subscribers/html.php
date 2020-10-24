<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

class OSMembershipViewSubscribersHtml extends MPFViewList
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

		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options   = array_merge($options, $db->loadObjectList());

		$this->lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' onchange="submit();" ', 'id', 'title', $this->state->plan_id);

		$rowFields = OSMembershipHelper::getProfileFields($this->state->plan_id, true);

		$fields             = [];
		$filters            = [];
		$filterFieldsValues = $this->state->get('filter_fields', []);

		foreach ($rowFields as $rowField)
		{
			if ($rowField->filterable)
			{
				$fieldOptions = explode("\r\n", $rowField->values);

				$options   = [];
				$options[] = JHtml::_('select.option', '', $rowField->title);

				foreach ($fieldOptions as $option)
				{
					$options[] = JHtml::_('select.option', $option, $option);
				}

				$filters['field_' . $rowField->id] = JHtml::_('select.genericlist', $options, 'filter_fields[field_' . $rowField->id . ']', ' class="input-medium" onchange="submit();" ', 'value', 'text', ArrayHelper::getValue($filterFieldsValues, 'field_' . $rowField->id));
			}

			if ($rowField->show_on_subscriptions != 1 || in_array($rowField->name, ['first_name', 'last_name']))
			{
				continue;
			}

			$fields[$rowField->id] = $rowField;
		}


		if (count($fields))
		{
			$this->fieldsData = $this->model->getFieldsData(array_keys($fields));
		}

		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_SUBSCRIPTIONS'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_NEW_SUBSCRIPTION'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_SUBSCRIPTION_RENEWAL'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_SUBSCRIPTION_UPGRADE'));

		$this->lists['subscription_type'] = JHtml::_('select.genericlist', $options, 'subscription_type', ' class="inputbox" onchange="submit();" ', 'value', 'text', $this->state->subscription_type);

		$options   = array();
		$options[] = JHtml::_('select.option', -1, JText::_('OSM_ALL'));
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PENDING'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_ACTIVE'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_EXPIRED'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_CANCELLED_PENDING'));
		$options[] = JHtml::_('select.option', 4, JText::_('OSM_CANCELLED_REFUNDED'));

		$this->lists['published'] = JHtml::_('select.genericlist', $options, 'published', ' class="input-medium" onchange="submit();" ', 'value', 'text', $this->state->published);

		$this->config          = OSMembershipHelper::getConfig();
		$this->fields          = $fields;
		$this->filters         = $filters;
		$this->bootstrapHelper = OSMembershipHelperBootstrap::getInstance();

		$this->addToolbar();
	}

	/**
	 * Add Toolbar
	 */
	protected function addToolbar()
	{
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR . '/includes/toolbar.php');

		if ($this->getLayout() == 'import')
		{
			JToolbarHelper::title(JText::_('OSM_IMPORT_SUBSCRIPTIONS'));
			JToolbarHelper::save('import_subscriptions', 'OSM_IMPORT');
			JToolbarHelper::cancel('cancel');
		}
		else
		{
			parent::addToolbar();

			JToolbarHelper::custom('renew', 'plus', 'plus', 'OSM_RENEW_SUBSCRIPTION', true);
			JToolbarHelper::custom('export', 'download', 'download', 'OSM_EXPORT', false);
		}
	}
}
