<?php

/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Services helper.
 * @since 1.0
 */
class ServicesHelper {

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
    public static function addSubmenu($vName = '') {
		JHtmlSidebar::addEntry(
			JText::_('COM_SERVICES_TITLE_TOKENS'),
			'index.php?option=com_services&view=tokens',
			$vName === 'tokens'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SERVICES_TITLE_SLIMPHPFRAMEWORK'),
			'index.php?option=com_services&view=slimphpframework',
			$vName === 'slimphpframework'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_SERVICES_TITLE_SWAGGERUI'),
			'index.php?option=com_services&view=swaggerui',
			$vName === 'swaggerui'
		);

    }

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 * @param   string  $table  The table's name
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 *
	 * @since 1.3.5
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_services';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
