<?php
// namespace components\com_gdpr\models;
/**
 * @package GDPR::USER::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main offline cache resources model class
 *
 * @package GDPR::USER::components::com_gdpr
 * @subpackage models
 * @since 1.0
 */
class GdprModelUser extends GdprModel {
	/**
	 * Load manifest file for this type of data source
	 * @access private
	 * @return mixed
	 */
	private function loadManifest($option) {
		// Load configuration manifest file
		$fileName = JPATH_COMPONENT . '/manifests/' . $option . '.json';
	
		// Check if file exists and is valid manifest
		if(!file_exists($fileName)) {
			return false;
		}
	
		// Load the manifest serialized file and assign to local variable
		$manifest = file_get_contents($fileName);
		$manifestConfiguration = json_decode($manifest);
	
		return $manifestConfiguration;
	}
	
	/**
	 * Purge the cache of all messages in a single operation
	 *
	 * @access public
	 * @param int $userId
	 * @return boolean
	 */
	public function deleteEntities($userId) {
		JPluginHelper::importPlugin('user');
		$dispatcher = JEventDispatcher::getInstance();
		$deletionMode = $this->getComponentParams()->get('userprofile_delete_mode', 'permanent');
		// Get users data for the users to delete.
		$user_to_delete = JFactory::getUser($userId);
		
		$table = JTable::getInstance('User', 'JTable', array());
		$table->load($userId);
		
		// Fire the before delete event.
		$dispatcher->trigger('onUserBeforeDelete', array($table->getProperties()));
		
		// Delete all user informations, profile and tables records
		if($deletionMode == 'permanent') {
			try {
				$query = "DELETE " .
						  $this->_db->quoteName('jusers') . "," .
						  $this->_db->quoteName('userkeys') . "," .
						  $this->_db->quoteName('usernotes') . "," .
				 		  $this->_db->quoteName('userprofiles') . "," .
		 				  $this->_db->quoteName('usergroupmap') . "," .
	 				 	  $this->_db->quoteName('sessiontable') .
						  "\n FROM #__users AS jusers" .
						  "\n LEFT JOIN #__user_keys AS userkeys ON jusers.id = userkeys.user_id " .
						  "\n LEFT JOIN #__user_notes AS usernotes ON jusers.id = usernotes.user_id " .
						  "\n LEFT JOIN #__user_profiles AS userprofiles ON jusers.id = userprofiles.user_id " .
						  "\n LEFT JOIN #__user_usergroup_map AS usergroupmap ON jusers.id = usergroupmap.user_id " .
						  "\n LEFT JOIN #__session AS sessiontable ON jusers.id = sessiontable.userid " .
						  "\n WHERE jusers.id = " . $userId;
				$this->_db->setQuery($query);
				if(!$this->_db->execute()) {
					throw new GdprException($this->_db->getErrorMsg(), 'error');
				}
			} catch (GdprException $e) {
				$this->setError($e);
				return false;
			} catch (Exception $e) {
				$gdprException = new GdprException($e->getMessage(), 'error');
				$this->setError($gdprException);
				return false;
			}
		} 

		// Delete all user informations using the Pseudoanonymisation
		if($deletionMode == 'pseudonymisation') {
			// Pseudoanonymisation of the user record
			try {
				$randomPseudonymisationString = md5(microtime() . $userId);
				$query = "UPDATE #__users" .
						 "\n SET " .
						 $this->_db->quoteName('name') . " = " . $this->_db->quote('') . "," .
						 $this->_db->quoteName('username') . " = " . $this->_db->quote($randomPseudonymisationString) . "," .
						 $this->_db->quoteName('email') . " = " . $this->_db->quote($randomPseudonymisationString) . "," .
						 $this->_db->quoteName('password') . " = ''," .
						 $this->_db->quoteName('block') . " = 1," .
						 $this->_db->quoteName('registerDate') . " = " . $this->_db->quote($this->_db->getNullDate()) . "," .
						 $this->_db->quoteName('lastvisitDate') . " = " . $this->_db->quote($this->_db->getNullDate()) . "," .
						 $this->_db->quoteName('params') . " = '{}'" .
						 "\n WHERE id = " . $userId;
						 $this->_db->setQuery($query);
						if(!$this->_db->execute()) {
							throw new GdprException($this->_db->getErrorMsg(), 'error');
						}

				$queryNotes = "UPDATE " . $this->_db->quotename('#__user_notes') .
							  "\n SET " .  $this->_db->quotename('body') . " = " . $this->_db->quote($randomPseudonymisationString) .
						 	  "\n WHERE " .  $this->_db->quotename('user_id') . " = " . $userId .
					 		  "\n AND " .  $this->_db->quotename('catid') . " = " . (int) $this->getComponentParams()->get('log_usernote_privacypolicy_category', 0) .
							  "\n AND " .  $this->_db->quotename('subject') . " = " . $this->_db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'));
						$this->_db->setQuery($queryNotes);
						if(!$this->_db->execute()) {
							throw new GdprException($this->_db->getErrorMsg(), 'error');
						}
			} catch (GdprException $e) {
				$this->setError($e);
				return false;
			} catch (Exception $e) {
				$gdprException = new GdprException($e->getMessage(), 'error');
				$this->setError($gdprException);
				return false;
			}
		}
		
		// Fire the after delete event.
		$dispatcher->trigger('onUserAfterDelete', array($user_to_delete->getProperties(), true, null));
		
		// Check if additional contents must be deleted as well
		if($this->getComponentParams()->get('userprofile_delete_additional_contents', 0) && $deletionMode == 'permanent') {
			try {
				// Delete user generated contents
				$query = "DELETE FROM" .
						 "\n " .  $this->_db->quoteName ('#__content') .
						 "\n WHERE " . $this->_db->quoteName ('created_by') . " = " . $userId;
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query = "DELETE FROM" .
						 "\n " .  $this->_db->quoteName ('#__contact_details') .
						 "\n WHERE " . $this->_db->quoteName ('created_by') . " = " . $userId .
						 "\n OR " . $this->_db->quoteName ('user_id') .  " = " . $userId;
				$this->_db->setQuery($query);
				$this->_db->execute();
				
				$query = "DELETE FROM" .
						 "\n " .  $this->_db->quoteName ('#__messages') .
						 "\n WHERE " . $this->_db->quoteName ('user_id_from') . " = " . $userId .
						 "\n OR " . $this->_db->quoteName ('user_id_to') .  " = " . $userId;
				$this->_db->setQuery($query);
				$this->_db->execute();
			} catch (Exception $e) {
				// No user exceptions for this stage
			}
		}
		
		// Check for integration with third party apps, and delete them accordingly jomsocial, easysocial, kunena, cbuilder, k2user
		$tpdIntegrations = $this->getComponentParams()->get('3pdintegration', array());
		if(count($tpdIntegrations) && $this->getComponentParams()->get('userprofile_delete_additional_contents', 0) && $deletionMode == 'permanent') {
			foreach ($tpdIntegrations as $integratedExtension) {
				$manifest = $this->loadManifest($integratedExtension);
				if($manifest && is_object($manifest)) {
					foreach ($manifest->delete_profile as $deleteQuery) {
						$query = $deleteQuery . $userId;
						try {
							$this->_db->setQuery($query);
							$this->_db->execute();
						} catch (Exception $e) {
							// No exceptions raising for users
						}
					}
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Get all stored pre-existing consent data for both generic checkbox and dynamic checkbox if any for a give tuple of url, form, user
	 *
	 * @access public
	 * @param array $recordData
	 * @return Object&
	 */
	public function loadConsentEntityData($recordData) {
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
	
		// Check if we have a duplicated key AKA same url, same formid/or/formname and same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
	
		// Identify the form in the page
		if(isset($recordData['formid'])) {
			$where[] = "\n " . $this->_db->quoteName('formid') . " = " . $this->_db->quote($recordData['formid']);
		} elseif(isset($recordData['formname'])) {
			$where[] = "\n " . $this->_db->quoteName('formname') . " = " . $this->_db->quote($recordData['formname']);
		}
	
		// Check the type of the consent origin and if a global scope override is required
		$consentOrigin = $this->app->input->post->get('dynamicCheckbox', null) ? 'dynamic' : 'generic';
		if(!$this->getComponentParams()->get('consent_generic_bypage', 1) && $consentOrigin == 'generic') {
			$recordData['url'] = '*';
		}
		if(!$this->getComponentParams()->get('consent_dynamic_checkbox_bypage', 1) && $consentOrigin == 'dynamic') {
			$recordData['url'] = '*';
		}
	
		$query = "SELECT *" .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_consent_registry') .
				 "\n WHERE " . $this->_db->quoteName('url') . " = " . $this->_db->quote($recordData['url']) .
				 "\n AND "  . implode(" AND ", $where);
		try {
			$consentData = $this->_db->setQuery($query)->loadObject();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
	
		return $consentData;
	}
	
	/**
	 * Get a pre-existing consent status if any for a give tuple of url, form, user
	 *
	 * @access public
	 * @param array $recordData
	 * @return mixed
	 */
	public function loadConsentEntity($recordData) {
		// Skip if tracking of previous consent is disabled
		if(!$this->getComponentParams()->get('consent_registry_track_previous_consent', 1)) {
			return 0;
		}
		
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
	
		// Check if we have a duplicated key AKA same url, same formid/or/formname and same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
	
		// Identify the form in the page
		if(isset($recordData['formid'])) {
			$where[] = "\n " . $this->_db->quoteName('formid') . " = " . $this->_db->quote($recordData['formid']);
		} elseif(isset($recordData['formname'])) {
			$where[] = "\n " . $this->_db->quoteName('formname') . " = " . $this->_db->quote($recordData['formname']);
		}
		
		// Check the type of the consent origin and if a global scope override is required
		$consentOrigin = $this->app->input->post->get('dynamicCheckbox', null) ? 'dynamic' : 'generic';
		if(!$this->getComponentParams()->get('consent_generic_bypage', 1) && $consentOrigin == 'generic') {
			$recordData['url'] = '*';
		}
		if(!$this->getComponentParams()->get('consent_dynamic_checkbox_bypage', 1) && $consentOrigin == 'dynamic') {
			$recordData['url'] = '*';
		}
		
		$query = "SELECT " . $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_consent_registry') .
				 "\n WHERE " . $this->_db->quoteName('url') . " = " . $this->_db->quote($recordData['url']) .
				 "\n AND "  . implode(" AND ", $where);
		try {
			$existentId = $this->_db->setQuery($query)->loadResult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
	
		return $existentId;
	}
	
	/**
	 * Store the consent status for a give tuple of url, form, user
	 *
	 * @access public
	 * @param array $recordData
	 * @return mixed
	 */
	public function storeConsentEntity($recordData) {
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
		$recordData['consent_date'] = JDate::getInstance()->toSql();

		// If log IP address
		if($this->getComponentParams()->get('log_user_ipaddress', 0)) {
			$recordData['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		}
		
		// Check if we have a duplicated key AKA same url, same formid/or/formname and same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
		
		// Identify the form in the page
		if(isset($recordData['formid'])) {
			$where[] = "\n " . $this->_db->quoteName('formid') . " = " . $this->_db->quote($recordData['formid']);
		} elseif(isset($recordData['formname'])) {
			$where[] = "\n " . $this->_db->quoteName('formname') . " = " . $this->_db->quote($recordData['formname']);
		}
		
		// Check the type of the consent origin and if a global scope override is required
		$consentOrigin = $this->app->input->post->get('dynamicCheckbox', null) ? 'dynamic' : 'generic';
		if(!$this->getComponentParams()->get('consent_generic_bypage', 1) && $consentOrigin == 'generic') {
			$recordData['url'] = '*';
		}
		if(!$this->getComponentParams()->get('consent_dynamic_checkbox_bypage', 1) && $consentOrigin == 'dynamic') {
			$recordData['url'] = '*';
		}
		
		$query = "SELECT " . $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_consent_registry') .
				 "\n WHERE " . $this->_db->quoteName('url') . " = " . $this->_db->quote($recordData['url']) .
				 "\n AND "  . implode(" AND ", $where);
		try {
			$existentId = $this->_db->setQuery($query)->loadResult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
		
		// Skip if tracking of previous consent is disabled
		if(!$this->getComponentParams()->get('consent_registry_track_previous_consent', 1)) {
			$existentId = false;
		}
		
		// Go on with a new store if no duplicated key detected
		if(!$existentId) {
			$recordDataObject = (object)$recordData;
			try {
				$this->_db->insertObject('#__gdpr_consent_registry', $recordDataObject);
				return $this->_db->insertid();
			} catch(Exception $e) {
				// No errors handling for user interface
			}
		}
		
		return false;
	}
	
	/**
	 * Delete the consent status for a give tuple of url, form, user
	 *
	 * @access public
	 * @param array $recordData
	 * @return boolean
	 */
	public function deleteConsentEntity($postData) {
		$userId = JFactory::getUser()->id;
		$sessionId = session_id();
		$where = array();
		
		// We have a logged in user
		if($userId) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($userId);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($sessionId);
		}
		
		// Identify the form in the page
		if(isset($postData['formid'])) {
			$where[] = "\n " . $this->_db->quoteName('formid') . " = " . $this->_db->quote($postData['formid']);
		} elseif(isset($postData['formname'])) {
			$where[] = "\n " . $this->_db->quoteName('formname') . " = " . $this->_db->quote($postData['formname']);
		}
		
		// Check the type of the consent origin and if a global scope override is required
		$consentOrigin = $this->app->input->post->get('dynamicCheckbox', null) ? 'dynamic' : 'generic';
		if(!$this->getComponentParams()->get('consent_generic_bypage', 1) && $consentOrigin == 'generic') {
			$postData['url'] = '*';
		}
		if(!$this->getComponentParams()->get('consent_dynamic_checkbox_bypage', 1) && $consentOrigin == 'dynamic') {
			$postData['url'] = '*';
		}
		
		$query = "DELETE FROM " . $this->_db->quoteName('#__gdpr_consent_registry') .
				 "\n WHERE " . $this->_db->quoteName('url') . " = " . $this->_db->quote($postData['url']) . 
				 "\n AND "  . implode(" AND ", $where);
		try {
			$this->_db->setQuery($query);
			$this->_db->execute();
		} catch(Exception $e) {
			// No errors handling for user interface
		}
	}
	
	/**
	 * Store the consent status for a given category of cookie
	 *
	 * @access public
	 * @param int $cookieCategory
	 * @param int $cookieCategoryState
	 * @return mixed
	 */
	public function storeCookieConsentEntity($cookieCategory, $cookieCategoryState) {
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
		$recordData['consent_date'] = JDate::getInstance()->toSql();
	
		// Build the db field based on cookie category
		$recordData['generic'] = 1; // Always imply the generic cookie consent active
		$dbCategoryField = 'category' . $cookieCategory;
		$recordData[$dbCategoryField] = $cookieCategoryState;
	
		// Auto repopulate all OTHER CATEGORIES different than this one
		// Allow state, store default checked categories or restore them from the session
		$cParams = $this->getComponentParams();
		$session = $this->app->getSession();
		
		// Category 1
		if($dbCategoryField != 'category1') {
			$sessionStatusCategory1 = $session->get('gdpr_cookie_category_disabled_1', null);
			if(!is_null($sessionStatusCategory1)) {
				$recordData['category1'] = (int)$sessionStatusCategory1 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category1'] = $cParams->get('cookie_category1_checked', 1);
			}
		}
		
		// Category 2
		if($dbCategoryField != 'category2') {
			$sessionStatusCategory2 = $session->get('gdpr_cookie_category_disabled_2', null);
			if(!is_null($sessionStatusCategory2)) {
				$recordData['category2'] = (int)$sessionStatusCategory2 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category2'] = $cParams->get('cookie_category2_checked', 1);
			}
		}
		
		// Category 3
		if($dbCategoryField != 'category3') {
			$sessionStatusCategory3 = $session->get('gdpr_cookie_category_disabled_3', null);
			if(!is_null($sessionStatusCategory3)) {
				$recordData['category3'] = (int)$sessionStatusCategory3 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category3'] = $cParams->get('cookie_category3_checked', 0);
			}
		}
		
		// Category 4
		if($dbCategoryField != 'category4') {
			$sessionStatusCategory4 = $session->get('gdpr_cookie_category_disabled_4', null);
			if(!is_null($sessionStatusCategory4)) {
				$recordData['category4'] = (int)$sessionStatusCategory4 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category4'] = $cParams->get('cookie_category4_checked', 0);
			}
		}
		
		// If log IP address
		if($this->getComponentParams()->get('log_user_ipaddress', 0)) {
			$recordData['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		}
	
		// Check if we have a duplicated key AKA same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
	
		// Always consider a consent valid within a specific time range, once elapsed it start a new consent
		$where[] = "\n " . $this->_db->quoteName('consent_date') . " > " . $this->_db->quote(date('Y-m-d', strtotime("-1 year", time())));
	
		$query = "SELECT " . $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_cookie_consent_registry') .
				 "\n WHERE " . implode(" AND ", $where);
		try {
			$existentId = $this->_db->setQuery($query)->loadResult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
	
		// Normalize to object
		$recordDataObject = (object)$recordData;
	
		// Go on with a new store if no duplicated key detected
		if(!$existentId) {
			try {
				$this->_db->insertObject('#__gdpr_cookie_consent_registry', $recordDataObject);
			} catch(Exception $e) {
				// No errors handling for user interface
			}
		} else {
			try {
				$recordDataObject->id = $existentId;
				$this->_db->updateObject('#__gdpr_cookie_consent_registry', $recordDataObject, 'id');
			} catch(Exception $e) {
				// No errors handling for user interface
			}
		}
	
		return true;
	}
	
	/**
	 * Store the generic cookie consent status and for the related session categories
	 *
	 * @access public
	 * @param int $cookieGenericState
	 * @return mixed
	 */
	public function storeCookieGenericConsentEntity($cookieGenericState) {
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
		$recordData['consent_date'] = JDate::getInstance()->toSql();
	
		// Build the db field based on cookie category
		$recordData['generic'] = $cookieGenericState;
	
		// Deny all state
		if(!$cookieGenericState) {
			$recordData['category1'] = 0;
			$recordData['category2'] = 0;
			$recordData['category3'] = 0;
			$recordData['category4'] = 0;
		} else {
			// Allow state, store default checked categories or restore them from the session
			$cParams = $this->getComponentParams();
			$session = $this->app->getSession();
				
			// Category 1
			$sessionStatusCategory1 = $session->get('gdpr_cookie_category_disabled_1', null);
			if(!is_null($sessionStatusCategory1)) {
				$recordData['category1'] = (int)$sessionStatusCategory1 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category1'] = $cParams->get('cookie_category1_checked', 1);
			}
				
			// Category 2
			$sessionStatusCategory2 = $session->get('gdpr_cookie_category_disabled_2', null);
			if(!is_null($sessionStatusCategory2)) {
				$recordData['category2'] = (int)$sessionStatusCategory2 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category2'] = $cParams->get('cookie_category2_checked', 1);
			}
				
			// Category 3
			$sessionStatusCategory3 = $session->get('gdpr_cookie_category_disabled_3', null);
			if(!is_null($sessionStatusCategory3)) {
				$recordData['category3'] = (int)$sessionStatusCategory3 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category3'] = $cParams->get('cookie_category3_checked', 0);
			}
				
			// Category 4
			$sessionStatusCategory4 = $session->get('gdpr_cookie_category_disabled_4', null);
			if(!is_null($sessionStatusCategory4)) {
				$recordData['category4'] = (int)$sessionStatusCategory4 == 1 ? 0 : 1; // Reverse logic 1 = declined -1 = accepted
			} else {
				$recordData['category4'] = $cParams->get('cookie_category4_checked', 0);
			}
		}
	
		// If log IP address
		if($this->getComponentParams()->get('log_user_ipaddress', 0)) {
			$recordData['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		}
	
		// Check if we have a duplicated key AKA same url, same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
	
		// Always consider a consent valid within a specific time range, once elapsed it start a new consent
		$where[] = "\n " . $this->_db->quoteName('consent_date') . " > " . $this->_db->quote(date('Y-m-d', strtotime("-1 year", time())));
	
		$query = "SELECT " . $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_cookie_consent_registry') .
				 "\n WHERE " . implode(" AND ", $where);
		try {
			$existentId = $this->_db->setQuery($query)->loadResult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
	
		// Normalize to object
		$recordDataObject = (object)$recordData;
	
		// Go on with a new store if no duplicated key detected
		if(!$existentId) {
			try {
				$this->_db->insertObject('#__gdpr_cookie_consent_registry', $recordDataObject);
			} catch(Exception $e) {
				// No errors handling for user interface
			}
		} else {
			try {
				$recordDataObject->id = $existentId;
				$this->_db->updateObject('#__gdpr_cookie_consent_registry', $recordDataObject, 'id');
			} catch(Exception $e) {
				// No errors handling for user interface
			}
		}
	
		return true;
	}
	
	/**
	 * Get data for a given dynamic checkbox:
	 * 1: name
	 * 2: formselector
	 * 3: required
	 *
	 * @access public
	 * @param string $placeholderIdentifier
	 * @param string $currentUrl
	 * @return mixed
	 */
	public function getCheckboxData($placeholderIdentifier, $currentUrl) {
		$checkboxData = new stdClass();
		$recordData = array();
		$user = JFactory::getUser();
		if($user->id) {
			// We have a logged in user, track it
			$recordData['user_id'] = $user->id;
		}
		$recordData['session_id'] = session_id();
	
		// Check if we have a duplicated key AKA same url, same formid/or/formname and same user_id/or/session_id
		$where = array();
		// We have a logged in user
		if(isset($recordData['user_id'])) {
			$where[] = "\n " . $this->_db->quoteName('user_id') . " = " . (int)($recordData['user_id']);
		} else {
			$where[] = "\n " . $this->_db->quoteName('session_id') . " = " . $this->_db->quote($recordData['session_id']);
		}
	
		// Identify the checkbox in the page
		$where[] = "\n " . $this->_db->quoteName('formid') . " = " . $this->_db->quote($placeholderIdentifier);
	
		// Check the type of the consent origin and if a global scope override is required
		$consentOrigin = $this->app->input->post->get('dynamicCheckbox', null) ? 'dynamic' : 'generic';
		if(!$this->getComponentParams()->get('consent_generic_bypage', 1) && $consentOrigin == 'generic') {
			$currentUrl = '*';
		}
		if(!$this->getComponentParams()->get('consent_dynamic_checkbox_bypage', 1) && $consentOrigin == 'dynamic') {
			$currentUrl = '*';
		}
		
		$query = "SELECT " . $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_consent_registry') .
				 "\n WHERE " . $this->_db->quoteName('url') . " = " . $this->_db->quote($currentUrl) .
				 "\n AND "  . implode(" AND ", $where);
		try {
			$existentId = $this->_db->setQuery($query)->loadResult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}
	
		$query = "SELECT " . 
				 $this->_db->quoteName('name') . "," .
				 $this->_db->quoteName('formselector') . "," .
				 $this->_db->quoteName('required') .  "," .
				 $this->_db->quoteName('published') .  "," .
				 $this->_db->quoteName('access') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_checkbox') .
			 	 "\n WHERE " . $this->_db->quoteName('placeholder') . " = " . $this->_db->quote($placeholderIdentifier);
		try {
			$checkboxData = $this->_db->setQuery($query)->loadObject();
			$userAccessLevels = $user->getAuthorisedViewLevels();
			if(in_array($checkboxData->access, $userAccessLevels)) {
				$checkboxData->allowed = 1;
			} else {
				$checkboxData->allowed = 0;
			}
			unset($checkboxData->access);
			
			// Process JText for 'name' field
			$checkboxData->name = JText::_($checkboxData->name);
		} catch (Exception $e) {
			// No errors handling for user interface
		}
		
		// Skip if tracking of previous consent is disabled
		if(!$this->getComponentParams()->get('consent_registry_track_previous_consent', 1)) {
			$existentId = false;
		}
		
		// Add checkbox status
		$checkboxData->checked = $existentId;
		
		return $checkboxData;
	}
	
	/**
	 * Get description for the popup fancybox for a given dynamic checkbox:
	 *
	 * @access public
	 * @param string $placeholderIdentifier
	 * @return mixed
	 */
	public function getCheckboxDescription($placeholderIdentifier) {
		$checkboxDescription = null;
		
		$query = "SELECT " .
				 $this->_db->quoteName('descriptionhtml') .
				 "\n FROM " . $this->_db->quoteName('#__gdpr_checkbox') .
				 "\n WHERE " . $this->_db->quoteName('placeholder') . " = " . $this->_db->quote($placeholderIdentifier) .
				 "\n AND "  .  $this->_db->quoteName('published') . " = 1";
				
		try {
			$checkboxDescription = $this->_db->setQuery($query)->loadresult();
		} catch (Exception $e) {
			// No errors handling for user interface
		}

		return $checkboxDescription;
	}
}