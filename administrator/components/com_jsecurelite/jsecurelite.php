<?php
/**
 * jSecure Lite components for Joomla!
 * jSecure Lite extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2012
 * @package     jSecure Lite 1.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: jsecurelite.php  $
 */
// no direct access
defined('_JEXEC') or die('Restricted Access');

if (!JFactory::getUser()->authorise('core.manage', 'com_jsecurelite')) {
 return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Require the base controller

require_once (JPATH_COMPONENT_ADMINISTRATOR.'/'.'controller.php');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base()."components/com_jsecurelite/css/jsecurelite.css");

// Create the controller
$controller    = new jsecureliteControllerjsecurelite();

// Perform the Request task
	$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>