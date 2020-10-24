<?php
// namespace administrator\components\com_gdpr\models;
/**
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Links model concrete implementation <<testable_behavior>>
 *
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage models
 * * @since 1.0
 */
class GdprModelLogs extends GdprModel {
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
			if(strpos($filter_state, '0:') !== false) {
				$filter_state = str_replace('0:', '', $filter_state);
				$where [] = 's.' . $filter_state . ' = 0';
			} else { 
				$where [] = 's.' . $filter_state . ' = 1';
			}
		}
		
		// TEXT FILTER
		if ($this->state->get ( 'searchword' )) {
			$where [] = "(s.name LIKE " . $this->_db->quote("%" . $this->state->get ( 'searchword' ) . "%") . " OR " .
						 "s.username LIKE " . $this->_db->quote("%" . $this->state->get ( 'searchword' ) . "%") . " OR " .
						 "s.email LIKE " . $this->_db->quote("%" . $this->state->get ( 'searchword' ) . "%") . ")";
		}
		
		//Filtro periodo
		if($this->state->get('fromPeriod')) {
			$where[] = "\n s.change_date > " . $this->_db->quote($this->state->get('fromPeriod'));
		}
		
		if($this->state->get('toPeriod')) {
			$toPeriod = $this->state->get('toPeriod');
			$toPeriod = date ( "Y-m-d", strtotime ( "+1 day", strtotime ( $toPeriod ) ) );
			$where[] = "\n s.change_date < " . $this->_db->quote($toPeriod);
		}
		
		// TEXT FILTER
		if ($this->state->get ( 'search_editorword' )) {
			$where [] = "(s.editor_name LIKE " . $this->_db->quote("%" . $this->state->get ( 'search_editorword' ) . "%") . " OR " .
						 "s.editor_username LIKE " . $this->_db->quote("%" . $this->state->get ( 'search_editorword' ) . "%") . ")";
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
		
		// IP address field
		$ipAddress = '';
		if($this->getComponentParams()->get('log_user_ipaddress', 0)) {
			$ipAddress = "\n s.ipaddress,";
		}
		
		$query = "SELECT" .
				 "\n s.id," .
				 "\n s.user_id," .
				 "\n s.name," .
				 "\n s.username," .
				 "\n s.email," .
				 $ipAddress .
				 "\n s.change_name," .
				 "\n s.change_username," .
				 "\n s.change_password," .
				 "\n s.change_email," .
				 "\n s.change_params," .
				 "\n s.change_requirereset," .
				 "\n s.change_block," .
				 "\n s.change_sendemail," .
				 "\n s.change_usergroups," .
				 "\n s.change_activation," .
				 "\n s.created_user," .
				 "\n s.deleted_user," .
				 "\n s.privacy_policy," .
				 "\n s.editor_user_id," .
				 "\n s.editor_name," .
				 "\n s.editor_username," .
				 "\n s.change_date," .
				 "\n s.changes_structure" .
				 "\n FROM #__gdpr_logs AS s" .
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
		
		// Filter by redirect state
		$filterState = array();
		$filterState[] = JHtml::_('select.option', null, JText::_('COM_GDPR_LOGS_ALL'));
		$filterState[] = JHtml::_('select.option', 'change_name', JText::_('COM_GDPR_LOGS_CHANGE_NAME'));
		$filterState[] = JHtml::_('select.option', 'change_username', JText::_('COM_GDPR_LOGS_CHANGE_USERNAME'));
		$filterState[] = JHtml::_('select.option', 'change_password', JText::_('COM_GDPR_LOGS_CHANGE_PASSWORD'));
		$filterState[] = JHtml::_('select.option', 'change_email', JText::_('COM_GDPR_LOGS_CHANGE_EMAIL'));
		$filterState[] = JHtml::_('select.option', 'change_params', JText::_('COM_GDPR_LOGS_CHANGE_PARAMS'));
		$filterState[] = JHtml::_('select.option', 'change_requirereset', JText::_('COM_GDPR_LOGS_CHANGE_REQUIRERESET'));
		$filterState[] = JHtml::_('select.option', 'change_block', JText::_('COM_GDPR_LOGS_CHANGE_BLOCK'));
		$filterState[] = JHtml::_('select.option', 'change_sendemail', JText::_('COM_GDPR_LOGS_CHANGE_SENDEMAIL'));
		$filterState[] = JHtml::_('select.option', 'change_usergroups', JText::_('COM_GDPR_LOGS_CHANGE_USERGROUPS'));
		$filterState[] = JHtml::_('select.option', 'change_activation', JText::_('COM_GDPR_LOGS_CHANGE_ACTIVATION'));
		$filterState[] = JHtml::_('select.option', 'created_user', JText::_('COM_GDPR_LOGS_CREATED_USER'));
		$filterState[] = JHtml::_('select.option', 'deleted_user', JText::_('COM_GDPR_LOGS_DELETED_USER'));
		// Add only if privacy policy revokable is enabled
		if($this->getComponentParams()->get('revokable_privacypolicy', 0)) {
			$filterState[] = JHtml::_('select.option', 'privacy_policy', JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_USER'));
			$filterState[] = JHtml::_('select.option', '0:privacy_policy', JText::_('COM_GDPR_LOGS_NOT_PRIVACYPOLICY_USER'));
		}
		
		$filters ['state'] = JHtml::_ ( 'select.genericlist', $filterState, 'filter_state', 'onchange="Joomla.submitform();"', 'value', 'text', $this->getState ( 'state' ));
		
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
		//$lists ['evaluate_params'] = JHtml::_ ( 'select.booleanlist', 'evaluate_params', null, $record->evaluate_params );
		
		return $lists;
	}
	
	/**
	 * Esplica la funzione di esportazione della lista messaggi
	 * in formato CSV per i record estratti dai filtri userstate attivi
	 * @access public
	 * @return Object[]&
	 */
	public function exportLogs() {
		// Obtain query string
		$query = $this->buildListQuery();
		$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit') );
		$resultSet = $this->_db->loadAssocList();
	
		if(!is_array($resultSet) || !count($resultSet)) {
			return false;
		}
	
		return $resultSet;
	}
}