<?php
// namespace administrator\components\com_gdpr\models;
/**
 *
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Cookie model responsibilities
 *
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
class GdprModelCookie extends GdprModel {
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
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . ")";
		}
		
		// Cookie consent type filter
		if($this->state->get('cookie_consent_type', '') !== '') {
			$cookieConsentType = (int)$this->state->get('cookie_consent_type');
			switch ($cookieConsentType) {
				case 0:
					$where[] = "\n (a.generic = 1)";
					break;
				case 1:
					$where[] = "\n (a.category1 = 1)";
					break;
				case 2:
					$where[] = "\n (a.category2 = 1)";
					break;
				case 3:
					$where[] = "\n (a.category3 = 1)";
					break;
				case 4:
					$where[] = "\n (a.category4 = 1)";
					break;
			}
		}
		
		// User consent type
		if($this->state->get('cookie_consent_user', '') !== '') {
			$cookieConsentUser = (int)$this->state->get('cookie_consent_user');
			switch ($cookieConsentUser) {
				case 1:
					$where[] = "\n ISNULL(u.id)";
					break;
				case 2:
					$where[] = "\n (u.id != '')";
					break;
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
		
		// Manage dynamic fields
		$cParams = $this->getComponentParams();
		$logUserIpaddress = $cParams->get('log_user_ipaddress', 0);
		$enabledCategories = array();
		$separator = null;
		if($cParams->get('cookie_category1_enable', 0)) {
			$enabledCategories[] = "\n a.category1";
		}
		if($cParams->get('cookie_category2_enable', 0)) {
			$enabledCategories[] = "\n a.category2";
		}
		if($cParams->get('cookie_category3_enable', 0)) {
			$enabledCategories[] = "\n a.category3";
		}
		if($cParams->get('cookie_category4_enable', 0)) {
			$enabledCategories[] = "\n a.category4";
		}
		if(count($enabledCategories)) {
			$separator = ',';
		}
		
		// IP address field
		$ipAddress = '';
		if($logUserIpaddress) {
			$ipAddress = "\n a.ipaddress,";
		}
		
		$ANDComPrivacyDeleted = '';
		if(version_compare(JVERSION, '3.9', '>=') && !$this->getComponentParams()->get('consent_registry_include_pseudonymised', 0)) {
			$ANDComPrivacyDeleted = "\n AND SUBSTRING(u.email, -7) != " .  $this->_db->quote('invalid');
		}
		
		$query = "SELECT" .
				 $idField .
				 "\n a.user_id," .
				 "\n a.session_id," .
				 $ipAddress .
				 "\n u.name," .
				 "\n u.username," .
				 "\n u.email," .
				 "\n a.consent_date," .
				 "\n a.generic" .
				 $separator . implode(',', $enabledCategories) .
				 "\n FROM #__gdpr_cookie_consent_registry AS a" .
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
		
		$types = array();
		$types[] = JHtml::_('select.option', '', '- '. JText::_('COM_GDPR_CONSENTS_ALL' ) .' -' ); 
		$types[] = JHtml::_('select.option', '0', JText::_('COM_GDPR_CONSENTS_GENERIC' ) );
		$types[] = JHtml::_('select.option', '1', JText::_('COM_GDPR_CONSENTS_CATEGORY1' ) );
		$types[] = JHtml::_('select.option', '2', JText::_('COM_GDPR_CONSENTS_CATEGORY2' ) );
		$types[] = JHtml::_('select.option', '3', JText::_('COM_GDPR_CONSENTS_CATEGORY3' ) );
		$types[] = JHtml::_('select.option', '4', JText::_('COM_GDPR_CONSENTS_CATEGORY4' ) );
		 
		$lists['cookie_consent_type'] = JHtml::_('select.genericlist', $types, 'cookie_consent_type', 'class="inputbox hidden-phone hidden-tablet" size="1" onchange="document.adminForm.task.value=\'cookie.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('cookie_consent_type'));
		
		$users = array();
		$users[] = JHtml::_('select.option', '', '- '. JText::_('COM_GDPR_USERS_ALL' ) .' -' ); 
		$users[] = JHtml::_('select.option', '1', JText::_('COM_GDPR_USERS_NOTREGISTERED' ) );
		$users[] = JHtml::_('select.option', '2', JText::_('COM_GDPR_USERS_REGISTERED' ) );
			
		$lists['cookie_consent_user'] = JHtml::_('select.genericlist', $users, 'cookie_consent_user', 'class="inputbox hidden-phone hidden-tablet" size="1" onchange="document.adminForm.task.value=\'cookie.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('cookie_consent_user'));
		
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