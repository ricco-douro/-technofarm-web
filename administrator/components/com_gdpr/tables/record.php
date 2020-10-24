<?php
// namespace administrator\components\com_gdpr\tables;
/**
 *
 * @package GDPR::RECORD::administrator::components::com_gdpr
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
 * @package GDPR::RECORD::administrator::components::com_gdpr
 * @subpackage tables
 * @since 1.6
 */
class TableRecord extends JTable {
	/**
	 * @public int Primary key
	 */
	public $id = null;
	
	/**
	 * @public JSON string
	 */
	public $fields = null;
	
	/**
	 * @var int
	 */
	public $checked_out = 0;
	
	/**
	 * @var datetime
	 */
	public $checked_out_time = 0;
	
	/**
	 * @var int
	 */
	public $published = 1;
	
	/**
	 * @var int
	 */
	public $ordering = null;
	
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
		if($this->fields) {
			$this->fields = json_decode($this->fields, true);
		}

		return true;
	}
	
	/**
	 * Bind Table override
	 * @override
	 * 
	 * @see JTable::bind()
	 */
	public function bind($fromArray, $saveTask = false, $sessionTask = false) {
		parent::bind($fromArray);
		
		// Encode the JSON field structure
		if($saveTask) {
			// Structure required
			if (! $this->fields['structure']) {
				$this->setError ( JText::_('COM_GDPR_VALIDATION_ERROR' ) );
				return false;
			}
			
			// Treatment_name required
			if (! $this->fields['treatment_name']) {
				$this->setError ( JText::_('COM_GDPR_VALIDATION_ERROR' ) );
				return false;
			}
			
			$this->fields = json_encode($this->fields);
		}
		
		return true;
	}
	
	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__gdpr_record', 'id', $db );
	}
}