<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright   Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
defined('_JEXEC') or die;

JLoader::register('ServicesHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_services' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'services.php');

/**
 * Class ServicesFrontendHelper
 *
 * @since  1.6
 */
class ServicesHelpersServices
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_services/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_services/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'ServicesModel');
		}

		return $model;
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
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
	 * Gets the edit permission for an user
	 *
	 * @param   mixed  $item  The item
	 *
	 * @return  bool
	 */
	public static function canUserEdit($item)
	{
		$permission = false;
		$user       = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_services'))
		{
			$permission = true;
		}
		else
		{
			if (isset($item->created_by))
			{
				if ($user->authorise('core.edit.own', 'com_services') && $item->created_by == $user->id)
				{
					$permission = true;
				}
			}
			else
			{
				$permission = true;
			}
		}

		return $permission;
	}
}
