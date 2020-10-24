<?php
// namespace components\com_jchat\libraries\framework\dic;
/**
 *
 * @package JCHAT::administrator::components::com_jchat
 * @subpackage framework
 * @subpackage dic
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Base controller class
 * 
 * @package JCHAT::administrator::components::com_jchat
 * @subpackage framework
 * @subpackage dic
 * @since 2.0
 */
class JChatDicInstance {
	public $name;
	public function __construct($instance) {
		$this->name = $instance;
	}
}
