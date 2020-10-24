<?php
/**
 * jSecure Lite components for Joomla!
 * jSecure Lite is an interactive store locator
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2012
 * @package     jSecure Lite 1.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: admin.jsplocation.php  $
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

class com_jsecureliteInstallerScript
{
        function install($parent)
        {          
            $manifest = $parent->get("manifest");
            $parent = $parent->getParent();
            $source = $parent->getPath("source");
             
            $installer = new JInstaller();
            
            foreach($manifest->plugins->plugin as $plugin) {
                $attributes = $plugin->attributes();
                $plg = $source . '/' . $attributes['folder'].'/'.$attributes['plugin'];
                $installer->install($plg);
            }
            
            
            $db = JFactory::getDbo();
            $tableExtensions = $db->nameQuote("#__extensions");
            $columnElement   = $db->nameQuote("element");
            $columnType      = $db->nameQuote("type");
            $columnEnabled   = $db->nameQuote("enabled");

            $db->setQuery('update #__extensions set enabled = 1 where element = "jsecurelite" and type = "plugin"');
            $db->query();
			
            
        }


		function uninstall($parent) 
	{
		$database	=  JFactory::getDBO();
         jimport('joomla.filesystem.file');

      // remove system plugin
	$database->setQuery( "DELETE FROM `#__extensions` WHERE `element`= 'jsecurelite';");
	$database->query();

	JFile::delete( JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite.php' );
	JFile::delete( JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite.xml' );
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'404.html'); 

	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'jsecurelite.class.php');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css'.'/'.'jsecurelite.css');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css'.'/'.'index.html');
	JFile::delete(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'index.html');
	JFile::delete(JPATH_ROOT.'/'.'administrator'.'/'.'language'.'/'.'en-GB'.'/'.'en-GB.plg_system_jsecurelite.ini');
	
	//rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite'.'/'.'css');
	//rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite'.'/'.'jsecurelite');
	//rmdir(JPATH_ROOT.'/'.'plugins'.'/'.'system'.'/'.'jsecurelite');

	echo '<h3>jSecure Lite has been succesfully uninstalled.</h3>';
	}

     
}