<?php
// namespace administrator\components\com_gdpr\models;
/**
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Links model concrete implementation <<testable_behavior>>
 *
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.4
 */
class GdprModelCheckbox extends GdprModel {
	/**
	 * Build list entities query
	 * 
	 * @access protected
	 * @return string
	 */
	protected function buildListQuery() {
		// WHERE
		$where = array ();
		$whereString = null;
		$orderString = null;

		// STATE FILTER
		if ($filter_state = $this->state->get ( 'state' )) {
			if ($filter_state == 'P') {
				$where [] = 's.published = 1';
			} else if ($filter_state == 'U') {
				$where [] = 's.published = 0';
			}
		}
		
		// Filtro testo
		if($this->state->get('searchword')) {
			$where[] = "\n s.name LIKE " .
					$this->_db->quote('%' . $this->state->get('searchword') . '%');
		}
		
		if (count ( $where )) {
			$whereString = "\n WHERE " . implode ( "\n AND ", $where );
		}
		
		// ORDERBY
		if ($this->state->get ( 'order' )) {
			$orderString = "\n ORDER BY " . $this->state->get ( 'order' ) . " ";
		}
		
		// ORDERDIR
		if ($this->state->get ( 'order_dir' )) {
			$orderString .= $this->state->get ( 'order_dir' );
		}
		
		$query = "SELECT s.*, levels.title AS accesslevel" .
				 "\n FROM #__gdpr_checkbox AS s" .
				 "\n LEFT JOIN #__viewlevels AS levels" .
				 "\n ON s.access = levels.id" .
				 $whereString . 
				 $orderString;
		return $query;
	}

	/**
	 * Main get data methods
	 * 
	 * @access public
	 * @return Object[]
	 */
	public function getData() {
		// Build query
		$query = $this->buildListQuery ();
		$this->_db->setQuery ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		try {
			$result = $this->_db->loadObjectList ();
			
			if($this->_db->getErrorNum()) {
				throw new GdprException(JText::_('COM_GDPR_ERROR_RETRIEVING_RECORDS') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (GdprException $e) {
			$this->app->enqueueMessage($e->getMessage(), $e->getErrorLevel());
			$result = array();
		} catch (Exception $e) {
			$gdprException = new GdprException($e->getMessage(), 'error');
			$this->app->enqueueMessage($gdprException->getMessage(), $gdprException->getErrorLevel());
			$result = array();
		}
		return $result;
	}
	
	/**
	 * Return select lists used as filter for listEntities
	 *
	 * @access public
	 * @return array
	 */
	public function getFilters() {
		$filters = array();
		
		$filters ['state'] = JHtml::_ ( 'grid.state', $this->getState ( 'state' ) );
		
		return $filters;
	}
	
	/**
	 * Return select lists used as filter for editEntity
	 *
	 * @access public
	 * @param Object $record
	 * @return array
	 */
	public function getLists($record = null) {
		$lists = parent::getLists($record);

		// Grid states
		$lists ['required'] = JHtml::_ ( 'select.booleanlist', 'required', null, $record->required );
		
		// Add access levels list
		$lists['access'] = JHtml::_('access.level', 'access', $record->access, '', false);
		
		return $lists;
	}
}