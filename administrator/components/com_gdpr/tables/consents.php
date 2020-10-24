<?php
// namespace administrator\components\com_gdpr\tables;
/**
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage tables
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Tracking of links redirected by the plugin
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage tables
 * @since 1.6
 */
class TableConsents extends JTable {
	/**
	 * @public int Primary key
	 */
	public $id = null;
	
	/**
	 * @public string
	 */
	public $url = null;
	
	/**
	 * @public string
	 */
	public $formid = null;
	
	/**
	 * @public string
	 */
	public $formname = null;
	
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
	public $consent_date = null;
	
	/**
	 * @public string
	 */
	public $formfields = null;

	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__gdpr_consent_registry', 'id', $db );
	}
}