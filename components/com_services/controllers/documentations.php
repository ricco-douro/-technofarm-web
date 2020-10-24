<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Documentations list controller class.
 */
class ServicesControllerDocumentations extends ServicesController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Documentations', $prefix = 'ServicesModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}