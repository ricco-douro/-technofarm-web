<?php

/**
 * @version     1.3.6
 * @package     com_services
 * @copyright   Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Services records.
 *
 * @since  1.6
 */
class ServicesModelSlimphpframework extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(

			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = Factory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) Factory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

		$app = Factory::getApplication();

		$ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
		$direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

		$this->setState('list.ordering', $ordering);
		$this->setState('list.direction', $direction);

		$start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
		$limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

		if ($limit == 0)
		{
			$limit = $app->get('list_limit', 0);
		}

		$this->setState('list.limit', $limit);
		$this->setState('list.start', $start);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{
			$item->mode = empty($item->mode) ? '' : JText::_('COM_SERVICES_TOKENS_MODE_OPTION_' . strtoupper($item->mode));
			$item->debug = empty($item->debug) ? '' : JText::_('COM_SERVICES_TOKENS_DEBUG_OPTION_' . strtoupper($item->debug));

			// Get the title of every option selected
			$options      = explode(',', $item->log_level);
			$options_text = array();

			foreach ((array) $options as $option)
			{
				$options_text[] = JText::_('COM_SERVICES_TOKENS_LOG_LEVEL_OPTION_' . strtoupper($option));
			}

			$item->log_level = !empty($options_text) ? implode(',', $options_text) : $item->log_level;
			$item->log_enabled = empty($item->log_enabled) ? '' : JText::_('COM_SERVICES_TOKENS_LOG_ENABLED_OPTION_' . strtoupper($item->log_enabled));
			$item->cookies_encrypt = empty($item->cookies_encrypt) ? '' : JText::_('COM_SERVICES_TOKENS_COOKIES_ENCRYPT_OPTION_' . strtoupper($item->cookies_encrypt));
			$item->cookies_secure = empty($item->cookies_secure) ? '' : JText::_('COM_SERVICES_TOKENS_COOKIES_SECURE_OPTION_' . strtoupper($item->cookies_secure));
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_SERVICES_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
