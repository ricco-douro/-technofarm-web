<?php
// namespace administrator\components\com_gdpr\models;
/**
 *
 * @package GDPR::CONSENTS::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Consents model responsibilities
 *
 * @package GDPR::CONSENTS::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
class GdprModelConsents extends GdprModel {
	/**
	 * Model data format
	 * 
	 *  @access private
	 *  @param string
	 */
	private $dataType;
	
	
	/**
	 * Restituisce la query string costruita per ottenere il wrapped set richiesto in base
	 * allo userstate, opzionalmente seleziona i campi richiesti
	 * 
	 * @access private
	 * @return string
	 */
	protected function buildListQuery() {
		// WHERE
		$where = array();
		$whereString = null;
				
		//Filtro testo
		if($this->state->get('searchword')) {
			$where[] = "\n (u.email LIKE " .
						$this->_db->quote('%' . $this->state->get('searchword') . '%') .
						"\n OR u.name LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . 
						"\n OR a.url LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . 
						"\n OR a.formid LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') .
						"\n OR a.formname LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . ")";
		}
		
		if($this->state->get('registered_user', '') !== '') {
			$registeredUser = (int)$this->state->get('registered_user');
			if($registeredUser == 1) {
				$where[] = "\n (u.id > 0)";
			} elseif($registeredUser == 0) {
				$where[] = "\n (u.id = 0 OR ISNULL(u.id))";
			}
		}
		
		//Filtro periodo
		if($this->state->get('fromPeriod')) {
			$where[] = "\n a.consent_date > " . $this->_db->quote($this->state->get('fromPeriod'));
		}
		
		if($this->state->get('toPeriod')) {
			$toPeriod = $this->state->get('toPeriod');
			$toPeriod = date ( "Y-m-d", strtotime ( "+1 day", strtotime ( $toPeriod ) ) );
			$where[] = "\n a.consent_date < " . $this->_db->quote($toPeriod);
		}
		
		if (count($where)) {
			$whereString = "\n WHERE " . implode ("\n AND ", $where);
		}
		
		// ORDERBY
		if($this->state->get('order')) {
			$orderString = "\n ORDER BY " . $this->state->get('order') . " ";
		}
		
		//Filtro testo
		if($this->state->get('order_dir')) {
			$orderString .= $this->state->get('order_dir');
		}
		
		// ID exclude for CSV
		$idField = "\n a.id,";
		if($this->dataType === 'assoc_array') {
			$idField = "";
		}
		
		// IP address field
		$ipAddress = '';
		if($this->getComponentParams()->get('log_user_ipaddress', 0)) {
			$ipAddress = "\n a.ipaddress,";
		}
		
		$ANDComPrivacyDeleted = '';
		if(version_compare(JVERSION, '3.9', '>=') && !$this->getComponentParams()->get('consent_registry_include_pseudonymised', 0)) {
			$ANDComPrivacyDeleted = "\n AND SUBSTRING(u.email, -7) != " .  $this->_db->quote('invalid');
		}
		
		$query = "SELECT" .
				 $idField .
				 "\n a.url," .
				 "\n a.formid," .
				 "\n a.formname," .
				 "\n a.user_id," .
				 "\n a.session_id," .
				 $ipAddress .
				 "\n u.name," .
				 "\n u.username," .
				 "\n u.email," .
				 "\n a.consent_date," .
				 "\n a.formfields" .
				 "\n FROM #__gdpr_consent_registry AS a" .
				 "\n LEFT JOIN #__users as u ON a.user_id = u.id AND u.name != ''" .
				 $ANDComPrivacyDeleted .
				 $whereString . 
				 $orderString;
		
		return $query;
	}

	/**
	 * Main get data method
	 * @access public
	 * @return Object[]
	 */
	public function getData($dataType = 'object_array') {
		$this->dataType = $dataType;
		
		// Build query
		$query = $this->buildListQuery ();
		$this->_db->setQuery ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		try {
			
			if($dataType == 'assoc_array') {
				$result = $this->_db->loadAssocList();
			} else {
				$result = $this->_db->loadObjectList();
			}
			
			if($this->_db->getErrorNum()) {
				throw new GdprException ( JText::sprintf ( 'COM_GDPR_ERROR_RECORDS', $this->_db->getErrorMsg () ), 'error' );
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
	 * Restituisce le select list usate dalla view per l'interfaccia
	 * @access public
	 * @return array
	 */
	public function getFilters() {
		$lists = array();
		 
		$types[] = JHtml::_('select.option',  '', '- '. JText::_('COM_GDPR_USERS_ALL' ) .' -' ); 
		$types[] = JHtml::_('select.option', '1', JText::_('COM_GDPR_USERS_REGISTERED' ) );
		$types[] = JHtml::_('select.option', '0', JText::_('COM_GDPR_USERS_NOTREGISTERED' ) );
		 
		$lists['registered_user'] = JHtml::_('select.genericlist', $types, 'registered_user', 'class="inputbox hidden-phone" size="1" onchange="document.adminForm.task.value=\'consents.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('registered_user'));
			
		return $lists;
	}
	
	/**
	 * Class constructor
	 *
	 * @access public
	 * @param $config array
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	
		$componentParams = $this->getComponentParams();
		$this->setState('cparams', $componentParams);
	}
} 