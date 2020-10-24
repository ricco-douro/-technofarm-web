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
class TableCheckbox extends JTable {
	/**
	 * @public int Primary key
	 */
	public $id = null;
	
	/**
	 * @public string
	 */
	public $placeholder = null;
	
	/**
	 * @public string
	 */
	public $name = null;
	
	/**
	 * @public string
	 */
	public $descriptionhtml = null;
	
	/**
	 * @public string
	 */
	public $formselector = null;
	
	/**
	 * @var int
	 */
	public $required = 0;
	
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
	public $access = 1;
	
	/**
	 * Store Table override
	 * @override
	 *
	 * @see JTable::store()
	 */
	public function store($updateNulls = false) {
		// Name required
		if (! $this->name) {
			$this->setError ( JText::_('COM_GDPR_VALIDATION_ERROR' ) );
			return false;
		}
		
		if(JString::strpos($this->descriptionhtml, 'COM_GDPR_') !== false) {
			$this->descriptionhtml = strip_tags($this->descriptionhtml);
		}
		
		$result = parent::store($updateNulls);
	
		// If store sucessful go on to popuplate relations table for sources/datasets
		if($result) {
			// Manage multiple tuples to be inserted using single query
			$queryPlaceholder = "UPDATE" .
							    "\n " . $this->_db->quoteName('#__gdpr_checkbox') .
							    "\n SET " . $this->_db->quoteName('placeholder') . " = " .
								$this->_db->quote('{' . JFilterOutput::stringUrlUnicodeSlug($this->name) . '-' . $this->id . '}') .
								"\n WHERE " . $this->_db->quoteName('id') . " = " . (int)$this->id;
			if(!$this->_db->setQuery($queryPlaceholder)->execute()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
	
		return $result;
	}
	
	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__gdpr_checkbox', 'id', $db );
	}
}