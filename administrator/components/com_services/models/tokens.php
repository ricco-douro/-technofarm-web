<?php

/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Services records.
 *
 * @since  1.6
 */
class ServicesModelTokens extends JModelList
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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'created', 'a.`created`',
				'created_by', 'a.`created_by`',
				'last_used', 'a.`last_used`',
				'state', 'a.`state`',
				'userid', 'a.`userid`',
				'token', 'a.`token`',
				'mode', 'a.`mode`',
				'debug', 'a.`debug`',
				'log_level', 'a.`log_level`',
				'log_enabled', 'a.`log_enabled`',
				'cookies_encrypt', 'a.`cookies_encrypt`',
				'cookies_domain', 'a.`cookies_domain`',
				'cookies_secure', 'a.`cookies_secure`',
				'cookies_secret_key', 'a.`cookies_secret_key`',
				'http_version', 'a.`http_version`',
				'api_rate_limit', 'a.`api_rate_limit`',
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
	 * @since 1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering userid
		$this->setState('filter.userid', $app->getUserStateFromRequest($this->context.'.filter.userid', 'filter_userid', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_services');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
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
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__services_tokens` AS a');


		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'userid'
		$query->select('`userid`.name AS `userid`');
		$query->join('LEFT', '#__users AS `userid` ON `userid`.id = a.`userid`');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');

			}
		}


		// Filtering userid
		$filter_userid = $this->state->get("filter.userid");

		if ($filter_userid !== null && !empty($filter_userid))
		{
			$query->where("a.`userid` = '".$db->escape($filter_userid)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 *
	 * @since 1.0
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem)
		{
			$oneItem->mode = ($oneItem->mode == '') ? '' : JText::_('COM_SERVICES_TOKENS_MODE_OPTION_' . strtoupper($oneItem->mode));
			$oneItem->debug = ($oneItem->debug == '') ? '' : JText::_('COM_SERVICES_TOKENS_DEBUG_OPTION_' . strtoupper($oneItem->debug));
		}

		return $items;
	}
}
