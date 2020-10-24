<?php
// namespace administrator\components\com_gdpr\models;
/**
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define ( 'SERVER_REMOTE_URI', 'http://storejextensions.org/dmdocuments/updates/' );
define ( 'UPDATES_FORMAT', '.json' );
jimport ( 'joomla.application.component.model' );

/**
 * Messages model responsibilities contract
 *
 * @package GDPR::MESSAGES::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
interface ICPanelModel {
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return array
	 */
	public function getData();
	
	/**
	 * Get by remote server informations for new updates of this extension
	 *
	 * @access public
	 * @param GdprHttp $httpClient        	
	 * @return mixed An object json decoded from server if update information retrieved correctly otherwise false
	 */
	public function getUpdates(GdprHttp $httpClient);
	
	/**
	 * Delete from file system all obsolete exchanged files
	 * 
	 * @access public
	 * @return boolean
	 */
	public function purgeFileCache();
}
/**
 * CPanel model concrete implementation
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
class GdprModelCpanel extends GdprModel {
	/**
	 * Build list entities query
	 *
	 * @access protected
	 * @return string
	 */
	protected function buildListQuery() {
		$where = null;
		
		// Skip pseudonymised users if any and the inclusion is not enabled
		if(!$this->getComponentParams()->get('consent_registry_include_pseudonymised', 0)) {
			$where = "\n AND u.name != ''";
		}
		
		if(version_compare(JVERSION, '3.9', '>=') && !$this->getComponentParams()->get('consent_registry_include_pseudonymised', 0)) {
			$where .= "\n AND SUBSTRING(u.email, -7) != " .  $this->_db->quote('invalid');
		}
		
		$query = "SELECT u.name, u.username, u.email, registerDate, p.profile_value, n.body" .
				 "\n FROM #__user_profiles AS p" .
				 "\n INNER JOIN #__users as u ON p.user_id = u.id" .
				 "\n LEFT JOIN #__user_notes AS n ON p.user_id = n.user_id AND n.state = 1" .
				 "\n AND n.subject = " . $this->_db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT')) .
				 "\n WHERE p.profile_key = " . $this->_db->quote('gdpr_consent_status') .
				 $where .
				 "\n ORDER BY p.user_id DESC";

		return $query;
	}
	
	/**
	 * Counter result set
	 *
	 * @access protected
	 * @return int
	 */
	protected function buildListQueryNewUsers() {
		$query = "SELECT COUNT(*)" .
				 "\n FROM #__gdpr_logs AS s" .
				 "\n WHERE s.created_user = 1";
	
		return $query;
	}
	
	/**
	 * Counter result set
	 *
	 * @access protected
	 * @return int
	 */
	protected function buildListQueryDeletedUsers() {
		$query = "SELECT COUNT(*)" .
				 "\n FROM #__gdpr_logs AS s" .
				 "\n WHERE s.deleted_user = 1";
	
		return $query;
	}
	
	/**
	 * Counter result set
	 *
	 * @access protected
	 * @return int
	 */
	protected function buildListQueryBreachedUsers() {
		$query = "SELECT COUNT(*)" .
				 "\n FROM #__gdpr_databreach_users AS s" .
				 "\n WHERE s.violated_user = 1";
	
		return $query;
	}
	
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return array
	 */
	public function getData() {
		$calculatedStats = array ();
		// Build queries
		try {
			// New Users
			$query = $this->buildListQueryNewUsers();
			$this->_db->setQuery ( $query );
			$totalNewUsers = $this->_db->loadResult ();
			if ($this->_db->getErrorNum ()) {
				throw new GdprException ( JText::_ ( 'COM_GDPR_DBERROR_STATS' ) . $this->_db->getErrorMsg (), 'error' );
			}
			
			// Deleted Users
			$query = $this->buildListQueryDeletedUsers();
			$this->_db->setQuery ( $query );
			$totalDeletedUsers = $this->_db->loadResult ();
			if ($this->_db->getErrorNum ()) {
				throw new GdprException ( JText::_ ( 'COM_GDPR_DBERROR_STATS' ) . $this->_db->getErrorMsg (), 'error' );
			}
			
			// New Users
			$query = $this->buildListQueryBreachedUsers();
			$this->_db->setQuery ( $query );
			$totalBreachedUsers = $this->_db->loadResult ();
			if ($this->_db->getErrorNum ()) {
				throw new GdprException ( JText::_ ( 'COM_GDPR_DBERROR_STATS' ) . $this->_db->getErrorMsg (), 'error' );
			}
			
			// GPlus registered users
			$calculatedStats ['chart_gdpr_canvas'] ['new'] = $totalNewUsers;
			$calculatedStats ['chart_gdpr_canvas'] ['deleted'] = $totalDeletedUsers;
			$calculatedStats ['chart_gdpr_canvas'] ['breached'] = $totalBreachedUsers;
		} catch ( GdprException $e ) {
			$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
			$calculatedStats = array ();
		} catch ( Exception $e ) {
			$gdprException = new GdprException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $gdprException->getMessage (), $gdprException->getErrorLevel () );
			$calculatedStats = array ();
		}
		
		return $calculatedStats;
	}
	
	/**
	 * Esplica la funzione di esportazione del registro in formato CSV
	 * @access public
	 * @return Object[]&
	 */
	public function exportRegistry($dataType = 'assoc_array') {
		try {
			// Obtain query string
			$query = $this->buildListQuery();
			$this->_db->setQuery($query);
			if($dataType == 'assoc_array') {
				$resultSet = $this->_db->loadAssocList();
			} else {
				$resultSet = $this->_db->loadObjectList();
			}
		} catch ( GdprException $e ) {
			$this->app->enqueueMessage ( $e->getMessage (), $e->getErrorLevel () );
			$resultSet = array ();
		} catch ( Exception $e ) {
			$gdprException = new GdprException ( $e->getMessage (), 'error' );
			$this->app->enqueueMessage ( $gdprException->getMessage (), $gdprException->getErrorLevel () );
			$resultSet = array ();
		}
	
		if(!is_array($resultSet) || !count($resultSet)) {
			return false;
		}
	
		return $resultSet;
	}
	
	/**
	 * Get by remote server informations for new updates of this extension
	 *
	 * @access public
	 * @param GdprHttp $httpClient        	
	 * @return mixed An object json decoded from server if update information retrieved correctly otherwise false
	 */
	public function getUpdates(GdprHttp $httpClient) {
		// Check if updates checker is disabled
		if($this->getComponentParams()->get('disable_version_checker', 0)) {
			return false;
		}
		
		// Updates server remote URI
		$option = $this->getState ( 'option', 'com_gdpr' );
		if (! $option) {
			return false;
		}
		$url = SERVER_REMOTE_URI . $option . UPDATES_FORMAT;
		
		// Try to get informations
		try {
			$response = $httpClient->get ( $url )->body;
			if ($response) {
				$decodedUpdateInfos = json_decode ( $response );
			}
			return $decodedUpdateInfos;
		} catch ( GdprException $e ) {
			return false;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Class constructor
	 * 
	 * @access public
	 * @param array $config        	
	 * @return Object&
	 */
	public function __construct($config = array()) {
		// Parent constructor
		parent::__construct ( $config );
	}
}