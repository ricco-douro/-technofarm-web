<?php
// namespace administrator\components\com_gdpr\tables;
/**
 *
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage tables
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Tracking of consents given for cookies
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage tables
 * @since 1.6
 */
class TableCookie extends JTable {
	/**
	 * @public int Primary key
	 */
	public $id = null;
	
	/**
	 * @public int
	 */
	public $user_id = 0;
	
	/**
	 * @public string
	 */
	public $session_id = null;
	
	/**
	 * @public string
	 */
	public $ipaddress = null;
	
	/**
	 * @public string
	 */
	public $consent_date = null;
	
	/**
	 * @public int
	 */
	public $generic = 0;
	
	/**
	 * @public int
	 */
	public $category1 = 0;
	
	/**
	 * @public int
	 */
	public $category2 = 0;
	
	/**
	 * @public int
	 */
	public $category3 = 0;
	
	/**
	 * @public int
	 */
	public $category4 = 0;

	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__gdpr_cookie_consent_registry', 'id', $db );
	}
}