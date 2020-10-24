<?php
//namespace components\com_jchat\framework\helpers; 
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
 * Utility class for chat messages
 * 
 * @package JCHAT::components::com_jchat
 * @subpackage framework
 * @subpackage helpers
 * @since 2.1
 */ 
class JChatHelpersMessages {
	/**
	 * Purify messages
	 *
	 * @access public
	 * @static
	 * @param string $message
	 * @param Object $componentConfig
	 * @return string
	 */
	public static function purifyMessage($message, $componentConfig) {
		// Strip delle immagini delle emeoticons con estrazione battitura
		$message = preg_replace ('/(<img class="jchat_emoticons")\s(title=")(.+)(")(.*>)/iUu', "$3", $message);
	
		$allowedTags = '<img>,<a>';
		if($componentConfig->get('allow_media_objects', true)) {
			$allowedTags .= ',<iframe>,<video>,<source>,<object>,<embed>,<param>';
		}
		// Strip html tags
		$message = strip_tags($message, $allowedTags);
	
		return $message;
	}
	
}