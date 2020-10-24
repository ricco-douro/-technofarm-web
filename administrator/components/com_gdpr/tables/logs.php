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
class TableLogs extends JTable {
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
	public $name = null;
	
	/**
	 * @public string
	 */
	public $username = null;
	
	/**
	 * @public string
	 */
	public $email = null;
	
	/**
	 * @public int
	 */
	public $change_name = 0;
	
	/**
	 * @public int
	 */
	public $change_username = 0;
	
	/**
	 * @public int
	 */
	public $change_password = 0;
	
	/**
	 * @public int
	 */
	public $change_email = 0;
	
	/**
	 * @public int
	 */
	public $change_params = 0;
	
	/**
	 * @public int
	 */
	public $change_requirereset = 0;
	
	/**
	 * @public int
	 */
	public $change_block = 0;
	
	/**
	 * @public int
	 */
	public $change_sendemail = 0;
	
	/**
	 * @public int
	 */
	public $change_usergroups = 0;
	
	/**
	 * @public int
	 */
	public $change_activation = 0;
	
	/**
	 * @public int
	 */
	public $created_user = 0;
	
	/**
	 * @public int
	 */
	public $deleted_user = 0;
	
	/**
	 * @public int
	 */
	public $privacy_policy = 1;
	
	/**
	 * @public int
	 */
	public $editor_user_id = 0;
	
	/**
	 * @public string
	 */
	public $editor_name = null;
	
	/**
	 * @public string
	 */
	public $editor_username = null;
	
	/**
	 * @public string
	 */
	public $change_date = null;
	
	/**
	 * @public JSON string
	 */
	public $changes_structure = null;
	
	/**
	 * Load Table override
	 * @override
	 *
	 * @see JTable::load()
	 */
	public function load($idEntity = null, $reset = true) {
		// If not $idEntity set return empty object
		if($idEntity) {
			if(!parent::load ( $idEntity )) {
				return false;
			}
		}
	
		// Decode the JSON field structure
		if($this->changes_structure) {
			$this->changes_structure = json_decode($this->changes_structure, true);
		}

		return true;
	}
	
	/**
	 * Method to store a row in the database from the JTable instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 */
	public function store($updateNulls = false) {
		$this->changes_structure = json_encode($this->changes_structure);
		
		parent::store($updateNulls);
	}
	
	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__gdpr_logs', 'id', $db );
	}
}