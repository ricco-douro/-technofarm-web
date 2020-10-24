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
 * @version     $Id: uninstall.jsecurelite.php  $
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$database	= & JFactory::getDBO();
jimport('joomla.filesystem.file');

// remove system plugin
	$database->setQuery( "DELETE FROM `#__extensions` WHERE `element`= 'jsecurelite';");
	$database->query();

	JFile::delete( JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite.php' );
	JFile::delete( JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite.xml' );
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'404.html'); 
	JFile::delete(JPATH_ADMINISTRATOR.'/'.'language'.'/'.'en-GB'.'/'.'en-GB.plg_system_jsecurelite.ini');

	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'jsecurelite.class.php');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css'.'/'.'jsecurelite.css');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css'.'/'.'index.html');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'index.html');


	rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css');
	rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite');
	rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite');

	echo '<h3>jSecure Lite has been succesfully uninstalled.</h3>';