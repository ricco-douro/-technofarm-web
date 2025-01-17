<?php
// namespace administrator\components\com_jchat\tables;
/**
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage tables
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Messages table
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage tables
 * @since 1.0
 */
class TableMessages extends JTable {
	/**
	 * @var int Primary key
	 */
	var $id = null;
	/**
	 * @var string
	 */
	var $from = null;
	/**
	 * @var string
	 */
	var $to = null;
	/**
	 * @var string
	 */
	var $message = null;
	/**
	 * @var string
	 */
	var $sent = null;
	/**
	 * @var int
	 */
	var $read = null;
	/**
	 * @var string
	 */
	var $type = null;
	/**
	 * @var int
	 */
	var $status = null;
	/**
	 * @var int
	 */
	var $clientdeleted = null;
	/**
	 * @var string
	 */
	var $actualfrom = null;
	/**
	 * @var string
	 */
	var $actualto = null;
	
	/**
	 * Delete Table override
	 * @override
	 *
	 * @see JTable::delete()
	 */
	public function delete($pk = null) {
		$messageDeleted = parent::delete($pk);
	
		if($messageDeleted) {
			// Delete reference table messages by foreign key
			$query = $this->_db->getQuery(true)->delete('#__jchat_public_readmessages');
			$query->where('messageid = ' . (int)$pk);
			$this->_db->setQuery($query);
			// Check for a database error.
			$this->_db->execute();
			if ($this->_db->getErrorNum()) {
				$messageDeleted = false;
			}
		}
	
		return $messageDeleted;
	}
	
	/**
	 *
	 * @param
	 *        	database A database connector object
	 */
	function __construct(&$db) {
		parent::__construct ( '#__jchat', 'id', $db );
	}
}