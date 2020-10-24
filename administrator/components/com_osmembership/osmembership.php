<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

//Require the controller

if (!JFactory::getUser()->authorise('core.manage', 'com_osmembership'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Require library + register autoloader
require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

// Setup database to work with multilingual site if needed
if (JLanguageMultilang::isEnabled() && !OSMembershipHelper::isSyncronized())
{
	OSMembershipHelper::setupMultilingual();
}

$config = include JPATH_ADMINISTRATOR . '/components/com_osmembership/config.php';

$input = new MPFInput();
MPFController::getInstance($input->getCmd('option'), $input, $config)
	->execute()
	->redirect();
