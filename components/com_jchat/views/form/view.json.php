<?php 
// namespace administrator\components\com_jchat\views\stream;

/**
 * @author Joomla! Extensions Store
 * @package JCHAT::STREAM::administrator::components::com_jchat
 * @subpackage views
 * @subpackage stream
 * @copyright (C) 2014 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * View for JS client
 *
 * @package JCHAT::STREAM::administrator::components::com_jchat
 * @subpackage views
 * @subpackage stream
 * @since 2.1
 */
class JChatViewForm extends JChatView {
	/**
	 * Return application/json response to JS client APP
	 * Replace $tpl optional param with $userData contents to inject
	 *        	
	 * @access public
	 * @param string $userData
	 * @return void
	 */
	public function display($userData = null) {
		echo json_encode($userData);  
	}
}