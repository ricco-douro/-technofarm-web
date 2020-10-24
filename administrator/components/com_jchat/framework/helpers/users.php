<?php
//namespace components\com_jchat\libraries; 
/** 
 * @package JCHAT::components::com_jchat
 * @subpackage framework
 * @subpackage helpers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Utility class for chat users
 * 
 * @package JCHAT::components::com_jchat
 * @subpackage framework
 * @subpackage helpers
 * @since 2.0
 */ 
class JChatHelpersUsers {
	/**
	 * Cross system filemtime no bugged
	 * @access private
	 * @param string $filePath
	 * @return int
	 */
	public static function crossFileMTime($filePath) {
		$time = filemtime($filePath);
	
		$isDST = (date('I', $time) == 1);
		$systemDST = (date('I') == 1);
	
		$adjustment = 0;
	
		if($isDST == false && $systemDST == true)
			$adjustment = 3600;
	
		else if($isDST == true && $systemDST == false)
			$adjustment = -3600;
	
		else
			$adjustment = 0;
	
		return ($time + $adjustment);
	}
	
	/**
	 * Effettua un reverse reduce sul'ID di sessione MD5 per arrivare
	 * ad una stringa da appendere al prefix del name assegnato ai guest users
	 * 
	 * @access public
	 * @static
	 * @param string $sessionID
	 * @param Object $cParams
	 * @return string
	 */
	public static function generateRandomGuestNameSuffix($sessionID, $cParams) {
		static $guestNamesCache = array();
		static $db = null;
		
		// First look if already generated guest name available in cache
		if(array_key_exists($sessionID, $guestNamesCache)) {
			return $guestNamesCache[$sessionID];
		}
		
		// If override guest name enabled and is guest this user and not in cache, try to check if t
		if($cParams->get('allow_guest_overridename', true)) {
			if(!is_object($db)) {
				$db = JFactory::getDbo();
			}
			
			$query = "SELECT ccs.override_name" .
					 "\n FROM #__jchat_sessionstatus AS ccs" .
					 "\n INNER JOIN #__session AS sess" .
					 "\n ON ccs.sessionid = sess.session_id" .
					 "\n WHERE sess.session_id = " . $db->quote($sessionID) .
					 "\n AND sess.client_id = 0 AND sess.guest = 1";
			$overrideNameFound = $db->setQuery($query)->loadResult();
			if($overrideNameFound) {
				$guestNamesCache[$sessionID] = $overrideNameFound;
				return $overrideNameFound;
			}
		}
		
		// Recuperiamo la parte numerica dell'hash in base 36
		preg_match_all('/\d/i', $sessionID, $matches);
	
		if(is_array($matches[0]) && count($matches[0])) {
			$numericHashArray = (float)(implode('', $matches[0]));
		}
				
		$appendHashSuffix = $numericHashArray;
		// Limitiamo a 4 cifre il numeric suffix
		$appendHashSuffix = $cParams->get('guestprefix', 'Guest') . substr($appendHashSuffix, 0, 4);
		
		// First store in cache for next message
		$guestNamesCache[$sessionID] = $appendHashSuffix;
	
		return $appendHashSuffix;
	}
	
	/**
	 * Get names for users based on current state, logged or guest
	 * 
	 * @access public
	 * @static
	 * @param string $sessionIDFrom
	 * @param string $sessionIDTo
	 * @param Object $componentParams
	 * @return array
	 */
	
	public static function getActualNames($sessionIDFrom, $sessionIDTo, $componentParams) {
		// Load user table
		$userTable = JTable::getInstance('user');
		
		// Load user session table
		$userSessionTable = JTable::getInstance('session');
		
		// Current chosen user field name
		$userFieldName = $componentParams->get('usefullname', 'username');
		
		// Sender actualfrom
		$userSessionTable->load($sessionIDFrom);
		$userTable->load($userSessionTable->userid);

		$uidf=$userSessionTable->userid;
		$sesf=$userSessionTable->session_id;
		$timf=$userSessionTable->time;
		
		
		$actualFrom = $userTable->{$userFieldName};
		#$actualFrom = $uidf;
		
		if(!$actualFrom) {
			$actualFrom = self::generateRandomGuestNameSuffix($sessionIDFrom, $componentParams);
		}
	
		// Receiver actualto
		$receiverSessionTable = clone($userSessionTable);
		$receiverSessionTable->load($sessionIDTo);
		$userTable->load($receiverSessionTable->userid);
		
		$uidt=$receiverSessionTable->userid;
		$sest=$receiverSessionTable->session_id;
		$timt=$receiverSessionTable->time;
		
		#$actualTo = $userTable->{$userFieldName};
		#$actualTo = $uidt;
		
		if(!$actualTo) {
			$actualTo = self::generateRandomGuestNameSuffix($receiverSessionTable->session_id, $componentParams);
		}
		
		$result = array();
		$result['fromActualName'] = $actualFrom;
		$result['toActualName'] = $actualTo;

		return $result;
	}
	
	/**
	 * Return current user session table object with singleton
	 * @access private
	 * @static
	 * @return Object
	 */
	public static function getSessionTable() {
		// Lazy loading user session
		static $userSessionTable;
		
		if(!is_object($userSessionTable)) {
			$userSessionTable = JTable::getInstance('session');
			$userSessionTable->load(session_id());
		}
	
		return $userSessionTable;
	}
	
	/**
	 * Singleton for session object
	 * @static
	 *
	 * @access private
	 * @return Object
	 */
	public static function getEmptySessionTable() {
		static $sessionTable;
	
		if(!is_object($sessionTable)) {
			$sessionTable = JTable::getInstance('session');
		}
	
		return $sessionTable;
	}
	
	/**
	 * Generate and assign avatars to users
	 * TODO caching system for $sessionID->avatar associated for the still same request
	 *
	 * @param string $sessionID
	 * @return string
	 */
	public static function getAvatar($sessionID) {
		static $avatarCache = array();
		$integrationType = null;
		
		// Avoid uneeded queries
		if(isset($avatarCache[$sessionID])) {
			return $avatarCache[$sessionID];
		}
		
		$baseURL = JURI::base();
		$cParams = JComponentHelper::getParams('com_jchat');
		$avatarFormat = 'png';
		$avatarSubPath = '/images/avatars/';
	
		// User session object
		$userSessionTable = self::getEmptySessionTable();
		$userSessionTable->load($sessionID);
		$userId = $userSessionTable->userid;
	
		$thirdPartyIntegration = $cParams->get('3pdintegration', false);
		switch ($thirdPartyIntegration) {
			case 'jomsocial':
				$integrationType = 'jomsocial/';
				break;

			case 'easysocial':
				$integrationType = 'easysocial/';
				break;

			case 'cbuilder':
				$integrationType = 'cb/';
				break;
		}
		
		// PRIORITY 1 - Try for JomSocial avatar if integration active
		if($thirdPartyIntegration === 'jomsocial' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('$baseURL', thumb) AS avatar" .
					"\n FROM #__community_users AS cu" .
					"\n INNER JOIN #__users AS u ON cu.userid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.thumb != ''";
			$DBO->setQuery($sql);
			if($userAvatar = $DBO->loadResult()) {
				$avatarCache[$sessionID] = $userAvatar;
				return $userAvatar;
			}
		}
	
		// PRIORITY 1 - Try for EasySocial avatar if integration active
		if($thirdPartyIntegration === 'easysocial' && $userId) {
			$DBO = JFactory::getDBO();
			$easySocialAvatarPath = $cParams->get('easysocial_avatar_path', 'media/com_easysocial');
			$sql = 	"SELECT CONCAT('" . $baseURL . "$easySocialAvatarPath/avatars/users/" . $userId . "/', square) AS avatar" .
					"\n FROM #__social_avatars AS cu" .
					"\n INNER JOIN #__users AS u ON cu.uid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.square != ''";
			$DBO->setQuery($sql);
			if($userAvatar = $DBO->loadResult()) {
				$avatarCache[$sessionID] = $userAvatar;
				return $userAvatar;
			}
		}
	
		// PRIORITY 1 - Try for CB avatar if integration active
		if($thirdPartyIntegration === 'cbuilder' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "images/comprofiler/', avatar)" .
					"\n FROM #__comprofiler AS cu" .
					"\n INNER JOIN #__users AS u ON cu.id = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.avatarapproved = 1 AND cu.avatar != ''";
			$DBO->setQuery($sql);
			if($userAvatar = $DBO->loadResult()) {
				$avatarCache[$sessionID] = $userAvatar;
				return $userAvatar;
			}
		}
	
		// PRIORITY 1 - Try for Kunena avatar if integration active
		if($thirdPartyIntegration === 'kunena' && $userId) {
			$kunenaAvatarSize = $cParams->get('kunena_avatars_resize_format', 'size36');
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "media/kunena/avatars/resized/$kunenaAvatarSize/', avatar) AS avatar" .
					"\n FROM #__kunena_users AS cu" .
					"\n INNER JOIN #__users AS u ON cu.userid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.avatar != ''";
			$DBO->setQuery($sql);
			if($userAvatar = $DBO->loadResult()) {
				$avatarCache[$sessionID] = $userAvatar;
				return $userAvatar;
			}
		}
		
		// PRIORITY 1 - Try for EasyProfile avatar if integration active
		if($thirdPartyIntegration === 'easyprofile' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "', REPLACE(avatar, '_', 'mini_')) AS avatar" .
					"\n FROM #__jsn_users AS cu" .
					"\n INNER JOIN #__users AS u ON cu.id = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.avatar != ''";
			$DBO->setQuery($sql);
			if($userAvatar = $DBO->loadResult()) {
				$avatarCache[$sessionID] = $userAvatar;
				return $userAvatar;
			}
		}
	
		// Calculate avatar name based on md5 from user id and username
		$calculatedHash = $userId ? 'uidavatar_' . $userId : 'gsidavatar_' . $sessionID;
		$finalName = $calculatedHash . '.' . $avatarFormat;
		$filePath = JPATH_COMPONENT . $avatarSubPath . $finalName;
	
		// PRIORITY 2 - User uploaded avatar, check if user has uploaded avatar
		if(file_exists($filePath)) {
			$lastModTimeFile = self::crossFileMTime($filePath);
			$userAvatar = JURI::base() . 'components/com_jchat/images/avatars/' . $finalName . '?nocache='.$lastModTimeFile;
			$avatarCache[$sessionID] = $userAvatar;
		} else {
			// PRIORITY 3 - Default avatar image for my and other users
			// Current user session table
			$userSessionTable->load(session_id());
			$am_i = $sessionID == $userSessionTable->session_id ? 'my' : 'other';
			$defaultAvatar = 'default_' . $am_i . '.png';
			$userAvatar = JURI::base() . 'components/com_jchat/images/avatars/' . $integrationType . $defaultAvatar ;
			$avatarCache[$sessionID] = $userAvatar;
		}
		
		return $userAvatar;
	}
}