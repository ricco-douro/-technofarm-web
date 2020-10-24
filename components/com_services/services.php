<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright   Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Services', JPATH_COMPONENT);
JLoader::register('ServicesController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Services');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
