<?php
//namespace administrator\components\com_jchat;
/**
 * Application install script
 * @package JCHAT::INSTALL::administrator::components::com_jchat 
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html    
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/** 
 * Application install script class
 * @package JCHAT::INSTALL::administrator::components::com_jchat 
 */
class com_jchatInstallerScript {
	/*
	 * The release value to be displayed and checked against throughout this file.
	 */
	private $release = '2.5';
	
	/*
	* Find mimimum required joomla version for this extension. It will be read from the version attribute (install tag) in the manifest file
	*/
	private $minimum_joomla_release = '3.0';
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	function preflight($type, $parent) {
	
	}
	
	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 */
	function install($parent) {
		$database = JFactory::getDBO ();
		echo ('<style type="text/css">div.alert-success, span.step_details {display: none;font-size: 12px;} span.step_details div{margin-top:0 !important;}</style>');
		echo ('<link rel="stylesheet" type="text/css" href="' . JURI::root ( true ) . '/administrator/components/com_jchat/css/bootstrap-install.css' . '" />');
		echo ('<script type="text/javascript" src="' . JURI::root ( true ) . '/administrator/components/com_jchat/js/installer.js' .'"></script>' );
		$lang = JFactory::getLanguage ();
		$lang->load ( 'com_jchat' );
		$parentParent = $parent->getParent();
		
		// Component installer
		$componentInstaller = JInstaller::getInstance ();
		$pathToPlugin = $componentInstaller->getPath ( 'source' ) . '/plugin';
		$pathToModule = $componentInstaller->getPath ( 'source' ) . '/module';
		
		echo ('<div class="installcontainer">');
		// New plugin installer
		$pluginInstaller = new JInstaller ();
		if (! $pluginInstaller->install ( $pathToPlugin )) {
			echo '<p>' . JText::_ ( 'COM_JCHAT_ERROR_INSTALLING_PLUGINS' ) . '</p>';
			// Install failed, rollback changes
			$parentParent->abort(JText::_('COM_JCHAT_ERROR_INSTALLING_PLUGINS'));
			return false;
		} else {
			$query = "UPDATE #__extensions" . "\n SET enabled = 1" . 
					 "\n WHERE type = 'plugin' AND element = " . $database->quote ( 'jchat' ) . 
					 "\n AND folder = " . $database->quote ( 'system' );
			$database->setQuery ( $query );
			if (! $database->execute ()) {
				echo '<p>' . JText::_ ( 'COM_JCHAT_ERROR_PUBLISHING_PLUGIN' ) . '</p>';
			}?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_JCHAT_OK_INSTALLING_PLUGINS');?></span>
				</div>
			</div>
			<?php 
		}
		
		// New module installer
		$moduleInstaller = new JInstaller ();
		if (! $moduleInstaller->install ( $pathToModule )) {
			echo '<p>' . JText::_ ( 'COM_JCHAT_ERROR_INSTALLING_MODULE' ) . '</p>';
			// Install failed, rollback changes
			$parentParent->abort(JText::_('COM_JCHAT_ERROR_INSTALLING_MODULE'));
			return false;
		} else {
			?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_JCHAT_OK_INSTALLING_MODULE');?></span>
				</div>
			</div>
			<?php 
		}
		?>
		<div class="progress">
			<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
				<span class="step_details"><?php echo JText::_('COM_JCHAT_OK_INSTALLING_COMPONENT');?></span>
		  	</div>
		</div>
		
		<div class="alert alert-success"><?php echo JText::_('COM_JCHAT_ALL_COMPLETED');?></div>
		<?php 
		echo ('</div>');
		
		return true;
	}
	
	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update($parent) {
		// Execute always SQL install file to get added updates in that file, disregard DBMS messages and Joomla queue for user
		$parentParent = $parent->getParent();
		$parentManifest = $parentParent->getManifest();
		try {
			// Install/update always without error handlingm case legacy JError
			JError::setErrorHandling(E_ALL, 'ignore');
			if (isset($parentManifest->install->sql)) {
				$parentParent->parseSQLFiles($parentManifest->install->sql);
			}
		} catch (Exception $e) {
			// Do nothing for user for Joomla 3.x case, case Exception handling
		}
		
		$this->install($parent);
	}
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent) { 
		// Preferences
		$params ['chatrefresh'] = '2';
		$params ['lastmessagetime'] = '60';
		$params ['maxinactivitytime'] = '30';
		$params ['forceavailable'] = '0';
		$params ['usefullname'] = 'username';
		$params ['start_open_mode'] = '1';
		$params ['chatboxes_open_mode'] = '0';
		$params ['chat_title'] = 'Chat';
		$params ['resizable_chatboxes'] = '1';
		$params ['resizable_sidebar'] = '1';
		$params ['chatrooms_users_details'] = '1';
		$params ['auto_close_popups'] = '1';
		$params ['exclude_onmobile'] = '0';
		
		// Features
		$params ['3pdintegration'] = '';
		$params ['filter_friendship'] = '0';
		$params ['skypebridge'] = '1';
		$params ['groupchat'] = '1';
		$params ['groupchatmode'] = 'chatroom';
		$params ['autoclear_conversation'] = '1';
		$params ['guestenabled'] = '1';
		$params ['guestprefix'] = 'Guest';
		$params ['searchfield'] = '1';
		$params ['history'] = '1';
		$params ['buddylist_visible'] = '1';
		$params ['privatechat_enabled'] = '1';
		$params ['typing_enabled'] = '1';
		$params ['lastreadmessage'] = '1';
		$params ['maximize_box'] = '1024';
		$params ['usersbanning'] = '0';
		$params ['usersbanning_mode'] = 'private';
		$params ['wordsbanning'] = '0';
		$params ['wordsbanned'] = 'shit,fuck,cock,asshole';
		$params ['wordsbanned_replacement'] = 'banned';
		$params ['ipbanning'] = '0';
		$params ['iprange_multiple'] = '';
		
		// Chat rendering
		$params ['chat_template'] = 'default.css';
		$params ['rendering_mode'] = 'auto';
		$params ['sidebar_width'] = '260';
		$params ['sidebar_height'] = '600';
		$params ['search_width'] = '100';
		$params ['chatroom_width'] = '400';
		$params ['baloon_position'] = 'top';
		
		// File system
		$params ['avatarenable'] = '1';  
		$params ['avatar_allowed_extensions'] = 'jpg,jpeg,png,gif';
		$params ['cropmode'] = '1';
		$params ['avatarupload'] = '1';
		$params ['attachmentsenable'] = '1';  
		$params ['maxfilesize'] = '2';
		$params ['disallowed_extensions'] = 'exe,bat,pif';
		$params ['easysocial_avatar_path'] = 'media/com_easysocial';
		$params ['kunena_avatars_resize_format'] = 'size36';
		
		// Notifications
		$params ['offline_message_switcher'] = '0';
		$params ['offline_message'] = '';
		$params ['notification_email_switcher'] = '0';
		$params ['notification_email'] = '';
		$params ['email_subject'] = 'JChatSocial - New conversation started';
		$params ['email_start_text'] = '';
		
		// Permissions
		$params ['allow_guest_fileupload'] = '1';
		$params ['allow_guest_avatarupload'] = '1';
		$params ['allow_guest_skypebridge'] = '1';
		$params ['allow_guest_overridename'] = '1';
		$params ['allow_media_objects'] = '1';
		$params ['allow_guest_banning'] = '1';
		
		// Advanced
		$params ['chatadmins_gids'] = array('0');
		$params ['chat_accesslevels'] = array('0');
		$params ['chat_exclusions'] = array('0');
		$params ['chatrooms_latest'] = '1';
		$params ['chatrooms_latest_interval'] = '120';
		$params ['wall_history_delay'] = '1';
		$params ['chatrooms_messages_stillinroom'] = '0';
		$params ['maxtimeinterval_groupmessages'] = '12';
		$params ['async_send_message'] = 'before';
		$params ['caching'] = '0';
		$params ['cache_lifetime'] = '15';
		$params ['scripts_loading'] = 'dom';
		$params ['keep_latest_msgs'] = '7';
		$params ['unique_usernames'] = '0';
		$params ['advanced_avatars_mgmt'] = '0';
		$params ['enable_debug'] = '0';
		$params ['includejquery'] = '1';
		$params ['noconflict'] = '1';
		$params ['includeevent'] = 'afterdispatch';
		
		// Insert all params settings default first time, merge and insert only new one if any on update, keeping current settings
		if ($type == 'install') {  
			$this->setParams ( $params );  
		} elseif ($type == 'update') {
			// Load and merge existing params, this let add new params default and keep existing settings one
			$db = JFactory::getDbo ();
			$query = $db->getQuery(true);
			$query->select('params');
			$query->from('#__extensions');
			$query->where($db->quoteName('name') . '=' . $db->quote('jchat'));
			$db->setQuery($query);
			$existingParamsString = $db->loadResult();
			// store the combined new and existing values back as a JSON string
			$existingParams = json_decode ( $existingParamsString, true );
			$updatedParams = array_merge($params, $existingParams);
			
			$this->setParams($updatedParams);
		}
	}
	
	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall($parent) {
		$database = JFactory::getDBO ();
		$lang = JFactory::getLanguage();
		$lang->load('com_jchat');
		 
		// Check if plugin exists
		$query = "SELECT extension_id" .
				 "\n FROM #__extensions" .
				 "\n WHERE type = 'plugin' AND element = " . $database->quote('jchat') .
				 "\n AND folder = " . $database->quote('system');
		$database->setQuery($query);
		$pluginID = $database->loadResult();
		if(!$pluginID) {
			echo '<p>' . JText::_('COM_JCHAT_PLUGIN_ALREADY_REMOVED') . '</p>';
		} else {
			// New plugin installer
			$pluginInstaller = new JInstaller ();
			if(!$pluginInstaller->uninstall('plugin', $pluginID)) {
				echo '<p>' . JText::_('COM_JCHAT_ERROR_UNINSTALLING_PLUGINS') . '</p>';
			} 
		}
		
		// Check if module exists
		$query = "SELECT extension_id" .
				 "\n FROM #__extensions" .
				 "\n WHERE type = 'module' AND element = " . $database->quote('mod_jchat') .
				 "\n AND client_id = 0";
		$database->setQuery($query);
		$moduleID = $database->loadResult();
		if(!$moduleID) {
			echo '<p>' . JText::_('COM_JCHAT_MODULE_ALREADY_REMOVED') . '</p>';
		} else {
			// New plugin installer
			$moduleInstaller = new JInstaller ();
			if(!$moduleInstaller->uninstall('module', $moduleID)) {
				echo '<p>' . JText::_('COM_JCHAT_ERROR_UNINSTALLING_MODULE') . '</p>';
			}
		}
		
		// Uninstall complete
		return true;
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam($name) {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT manifest_cache FROM #__extensions WHERE name = "jchat"' );
		$manifest = json_decode ( $db->loadResult (), true );
		return $manifest [$name];
	}
	
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if (count ( $param_array ) > 0) { 
			$db = JFactory::getDbo (); 
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode ( $param_array );
			$db->setQuery ( 'UPDATE #__extensions SET params = ' . $db->quote ( $paramsString ) . ' WHERE name = "jchat"' );
			$db->execute ();
		}
	}
}