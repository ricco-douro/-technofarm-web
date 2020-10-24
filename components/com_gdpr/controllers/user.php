<?php
// namespace components\com_gdpr\controllers;
/**
 *
 * @package GDPR::USER::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Controller for links entity tasks
 *
 * @package GDPR::USER::components::com_gdpr
 * @subpackage controllers
 *             * @since 1.0
 */
class GdprControllerUser extends GdprController {
	/**
	 *  Retrieve the user profile form URL
	 *
	 * @param lang
	 * @param Itemid
	 * @param splitted
	 */
	private function getUserFormUrl($lang, $Itemid, $original_option, $original_view, $original_task, $original_layout) {
		// Format redirect URI to the com_users if some error occurs
		$url = array (
				'option' => $original_option,
				'view' => $original_view,
				'task' => $original_task,
				'layout' => $original_layout
		);
		if ($lang) {
			if (strlen ( $lang ) > 2) {
				$splitted = explode ( '-', $lang );
				$lang = $splitted [0];
			}
			$url ['lang'] = $lang;
		}
		if ($Itemid) {
			$url ['Itemid'] = $Itemid;
		}
		$redirectUrl = http_build_query ( $url );
	
		return $redirectUrl;
	}
	
	/**
	 * Check a specific feature exclusion by group
	 *
	 * @param string $feature
	 * @param Object $cParams
	 * @access private
	 * @return bool
	 */
	private function checkExclusionPermissions($feature, $cParams) {
		static $userGroups;
	
		$isExcluded = false;
	
		if(!$userGroups) {
			$userGroups = $this->user->getAuthorisedGroups();
		}
	
		$featureExcludedGroups = $cParams->get($feature, array(0));
	
		if(is_array($featureExcludedGroups) && !in_array(0, $featureExcludedGroups, false)) {
			$intersectResult = array_intersect($userGroups, $featureExcludedGroups);
			$isExcluded = (int)(count($intersectResult));
		}
	
		return $isExcluded;
	}
	
	/**
	 * Send an email notification and return message no model
	 *
	 * @param $type
	 * @access private
	 * return bool
	 */
	private function sendEmailNotification($type, $model, $userId) {
		// Joomla global configuration
		$jConfig = JFactory::getConfig();
		$cParams = $model->getComponentParams();
		$user = JFactory::getUser($userId);

		// Integration with Joomla 3.9+ Privacy tool suite, add a record to the com_privacy requests manager
		if(version_compare(JVERSION, '3.9', '>=') && $cParams->get('integrate_comprivacy', 1)) {
			// Search for an open information request matching the email and type
			$db = $model->getDbo();
			$fullUser = JFactory::getUser ();
			$requestType = $type == 'delete' ? 'remove' : 'export';
			$query = $db->getQuery(true)
						->select('COUNT(id)')
						->from('#__privacy_requests')
						->where('email = ' . $db->quote($fullUser->email))
						->where('request_type = ' . $db->quote($requestType))
						->where('status IN (0, 1)');
			try {
				$result = (int) $db->setQuery($query)->loadResult();
			}
			catch (Exception $e) {
				// No error handling for the user
			}
			
			if (!$result) {
				// Everything is good to go, create the request
				$token = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
				$hashedToken = JUserHelper::hashPassword($token);
				$userRequest = (object) array(
						'email' => $fullUser->email,
						'requested_at' => JDate::getInstance()->toSql(),
						'status' => 1,
						'request_type' => $requestType,
						'confirm_token' => $hashedToken,
						'confirm_token_created_at' => JDate::getInstance()->toSql()
				);
				
				try {
					$db->insertObject('#__privacy_requests', $userRequest);
				} catch(Exception $e) {
					// No errors during the create request record phase
				}
			}
		}
		
		// Check for notify email addresses
		$validEmailAddresses = array();
		$emailAddresses = $cParams->get('logs_emails', '');
		$emailAddresses = explode(',', $emailAddresses);
		if(!empty($emailAddresses)) {
			foreach ($emailAddresses as $validEmail) {
				if(filter_var(trim($validEmail), FILTER_VALIDATE_EMAIL)) {
					$validEmailAddresses[] = trim($validEmail);
				}
			}
		}
	
		if(!empty($validEmailAddresses)) {
			// Build the email subject and message
			$sitename = $jConfig->get('sitename');
			$subject  = JText::sprintf('COM_GDPR_USER_REQUESTED_' . strtoupper($type) . '_OWN_PROFILE_SUBJECT', $sitename);
			$dateTimeRequest = JHtml::_('date', 'now', JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
			if($type == 'export') {
				$reportFormat = JText::_('COM_GDPR_' . strtoupper($this->app->input->getString('reportformat')));
				$msg = JText::sprintf('COM_GDPR_USER_REQUESTED_' . strtoupper($type) . '_OWN_PROFILE_MSG', $user->name, $reportFormat, $sitename, $user->name, $user->username, $user->email, $dateTimeRequest);
			} else {
				$msg = JText::sprintf('COM_GDPR_USER_REQUESTED_' . strtoupper($type) . '_OWN_PROFILE_MSG', $user->name, $sitename, $user->name, $user->username, $user->email, $dateTimeRequest);
			}

			// Send the email
			$mailer = JFactory::getMailer();
			$mailer->isHtml(true);
			$mailer->addReplyTo($user->email, $user->name);
	
			$mailer->setSender(array($cParams->get('logs_mailfrom', $jConfig->get('mailfrom')),
									 $cParams->get('logs_fromname', $jConfig->get('fromname'))));
	
			$mailer->addRecipient($validEmailAddresses);
	
			$mailer->setSubject($subject);
			$mailer->setBody($msg);
	
			// The Send method will raise an error via JError on a failure, we do not need to check it ourselves here
			try {
				return $mailer->Send();
			} catch (Exception $e) {
				return false;
			}
		}
	}

	/**
	 * Send an email confirmation after that a user self-deleted his own profile
	 *
	 * @param string $deletedUserEmail
	 * @param Object $model
	 * @access private
	 * return bool
	 */
	private function sendEmailConfirmation($deletedUserEmail, $model) {
		// Joomla global configuration
		$jConfig = JFactory::getConfig();
		$cParams = $model->getComponentParams();
	
		// Check for notify email addresses
		$validEmailAddresses = array();
		if(filter_var(trim($deletedUserEmail), FILTER_VALIDATE_EMAIL)) {
			$validEmailAddresses[] = trim($deletedUserEmail);
		}

		if(!empty($validEmailAddresses)) {
			// Build the email subject and message
			$sitename = $jConfig->get('sitename');
			$subject  = JText::sprintf('COM_GDPR_USER_SUCCESS_DELETED_OWN_PROFILE_SUBJECT', $sitename);
			$msg      = JText::sprintf('COM_GDPR_USER_SUCCESS_DELETED_OWN_PROFILE_MSG', $sitename, JHtml::_('date', 'now', JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')));
	
			// Send the email
			$mailer = JFactory::getMailer();
			$mailer->isHtml(true);
	
			$mailer->setSender(array($cParams->get('logs_mailfrom', $jConfig->get('mailfrom')),
									 $cParams->get('logs_fromname', $jConfig->get('fromname'))));
	
			$mailer->addRecipient($validEmailAddresses);
	
			$mailer->setSubject($subject);
			$mailer->setBody($msg);
	
			// The Send method will raise an error via JError on a failure, we do not need to check it ourselves here
			try {
				return $mailer->Send();
			} catch (Exception $e) {
				return false;
			}
		}
	}

	/**
	 * Send an email confirmation after that a user revoked a generic or dynamic checkbox consent
	 *
	 * @param string $deletedUserEmail
	 * @param Object $model
	 * @access private
	 * return bool
	 */
	private function sendEmailConsentsRevoked($data, $model) {
		// Retrieve the current stored data 
		$dataConsentObjectToFormat = $model->loadConsentEntityData($data);
		
		// Joomla global configuration
		$jConfig = JFactory::getConfig();
		$cParams = $model->getComponentParams();
	
		// Check for notify email addresses
		$validEmailAddresses = array();
		$emailAddresses = $cParams->get('logs_emails', '');
		$emailAddresses = explode(',', $emailAddresses);
		if(!empty($emailAddresses)) {
			foreach ($emailAddresses as $validEmail) {
				if(filter_var(trim($validEmail), FILTER_VALIDATE_EMAIL)) {
					$validEmailAddresses[] = trim($validEmail);
				}
			}
		}
		
		if(!empty($validEmailAddresses)) {
			// Build the email subject and message
			$sitename = $jConfig->get('sitename');
			$subject  = JText::sprintf('COM_GDPR_USER_REVOKED_CONSENT_SUBJECT', $sitename);
			
			$url = $dataConsentObjectToFormat->url != '*' ? $dataConsentObjectToFormat->url : JText::_('COM_GDPR_CONSENTS_REGISTRY_URL_ALL_PAGES');
			$formIdentifier = isset($data['formid']) ? $data['formid'] : (isset($data['formname']) ? $data['formname'] : '-');
			
			// Setup user informations if he's a registered one
			$userInfo = JText::_('COM_GDPR_LOGS_NA');
			if($dataConsentObjectToFormat->user_id) {
				$user = JFactory::getUser($dataConsentObjectToFormat->user_id);
				$userInfo = '(ID: ' . $user->id . ') (' . 
							JText::_('COM_GDPR_LOGS_NAME') . ': ' . $user->name . ') (' . 
							JText::_('COM_GDPR_LOGS_USERNAME') . ': ' . $user->username . ') (' .
							JText::_('COM_GDPR_LOGS_EMAIL') . ': ' . $user->email . ')';
			}
			
			$formFields = null;
			$formFieldsFormatted = null;
			if($dataConsentObjectToFormat->formfields) {
				try {
					$formFields = json_decode($dataConsentObjectToFormat->formfields, true);
				} catch(Exception $e) {
					// Don't stop operation, go on anyway
				}
			}
			if(is_array($formFields) && count($formFields)) {
				foreach ($formFields as $formFieldName=>$formFieldValue) {
					$formFieldsFormatted .= ' ( ' . ucfirst($formFieldName) . ': ';
						$cellValue = null;
						switch($formFieldValue){
							case null:
							case '0':
								$cellValue = JText::_('COM_GDPR_LOGS_NA');
								break;
								
							default:
								$cellValue = $formFieldValue;
						}
					$formFieldsFormatted .= $cellValue . ' ) ';
				}
			} else {
				$formFieldsFormatted = JText::_('COM_GDPR_LOGS_NA');
			}
			
			$msg = JText::sprintf('COM_GDPR_USER_REVOKED_CONSENT_MSG', $url, $formIdentifier, $userInfo, JHtml::_('date', $dataConsentObjectToFormat->consent_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')), JHtml::_('date', 'now', JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')), $formFieldsFormatted);
	
			// Send the email
			$mailer = JFactory::getMailer();
			$mailer->isHtml(true);
	
			$mailer->setSender(array($cParams->get('logs_mailfrom', $jConfig->get('mailfrom')),
									 $cParams->get('logs_fromname', $jConfig->get('fromname'))));
	
			$mailer->addRecipient($validEmailAddresses);
	
			$mailer->setSubject($subject);
			$mailer->setBody($msg);
	
			// The Send method will raise an error via JError on a failure, we do not need to check it ourselves here
			try {
				return $mailer->Send();
			} catch (Exception $e) {
				return false;
			}
		}
	}

	/**
	 * Manage rendering of offline cache manifest generating on the fly for the current page resources
	 *
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		return false;
	}
	
	/**
	 * Delete a db table entity
	 *
	 * @access public
	 * @return void
	 */
	public function deleteEntity() {
		// Check for request forgeries.
		$this->checkToken ();
		
		$original_option = $this->app->input->getCmd ( 'original_option', null );
		$original_view = $this->app->input->getCmd ( 'original_view', null );
		$original_task = $this->app->input->getCmd ( 'original_task', null );
		$original_layout = $this->app->input->getCmd ( 'original_layout', null );
		$lang = $this->app->input->get ( 'lang', null );
		$Itemid = $this->app->input->getInt ( 'Itemid', null );
		
		// Find the user id in the jform posted array if not present in the root post
		$userId = 0;
		$jFormArray = $this->app->input->get ( 'jform', array (), 'array' );
		if(isset($jFormArray['id']) && $original_option == 'com_users') {
			$userId = ( int ) $jFormArray ['id'];
		}
		
		if (! $userId) {
			$userId = $this->app->input->getInt ( 'original_userid', null);
		}
		
		$redirectUrl = $this->getUserFormUrl ( $lang, $Itemid, $original_option, $original_view, $original_task, $original_layout );
		
		// Get current user id
		$currentUser = JFactory::getUser ();
		if ($currentUser->id != $userId) {
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), JText::_ ( 'COM_GDPR_CANT_DELETE_OTHER_USERS' ) );
			return false;
		}
		
		// Load della model e checkin before exit
		$model = $this->getModel ();
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_deleteprofile', $model->getComponentParams())) {
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), JText::_ ( 'COM_GDPR_NOT_ALLOWED' ) );
			return false;
		}
		
		// If a delete notification only is requested, send email and redirect here
		if($model->getComponentParams()->get('userprofile_buttons_workingmode', 0)) {
			$resultNotification = $this->sendEmailNotification('delete', $model, $userId);
			$userMessage = $resultNotification ? JText::_ ( 'COM_GDPR_REQUEST_SUCCESS' ) : JText::_ ( 'COM_GDPR_REQUEST_ERROR' );
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), $userMessage );
			return true;
		}
		
		$result = $model->deleteEntities ( $userId );
		if (! $result) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError ( null, false );
			$this->app->enqueueMessage ( $modelException->getMessage (), $modelException->getErrorLevel () );
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), JText::_ ( 'COM_GDPR_ERROR_DELETE' ) );
			return false;
		} else {
			// The user has been deleted correctly, check if an email confirmation to the user must be sent
			if($model->getComponentParams()->get('userprofile_self_delete_confirmation', 0)) {
				$this->sendEmailConfirmation($currentUser->email, $model);
			}
		}
		
		// Perform the user logout and the final redirect to the home page after a delete and a logout, success message is shown
		try {
			$options = array (
					'clientid' => $this->app->get ( 'shared_session', '0' ) ? null : 0 
			);
			// Perform the log out.
			$this->app->logout ( null, $options );
		} catch ( Exception $e ) {
			// No exceptions raising for users
		}
		
		$this->app->redirect ( JRoute::_('index.php') );
	}
	
	/**
	 * Export user profile data
	 *
	 * @access public
	 * @return void
	 */
	public function exportEntity() {
		// Check for request forgeries.
		$this->checkToken ();
		
		$original_option = $this->app->input->getCmd ( 'original_option', null );
		$original_view = $this->app->input->getCmd ( 'original_view', null );
		$original_task = $this->app->input->getCmd ( 'original_task', null );
		$original_layout = $this->app->input->getCmd ( 'original_layout', null );
		$lang = $this->app->input->get ( 'lang', null );
		$Itemid = $this->app->input->getInt ( 'Itemid', null );
		$reportFormat = $this->app->input->getCmd ( 'reportformat', null );
		
		// Find the user id in the jform posted array if not present in the root post
		$userId = 0;
		$jFormArray = $this->app->input->get ( 'jform', array (), 'array' );
		if(isset($jFormArray['id']) && $original_option == 'com_users') {
			$userId = ( int ) $jFormArray ['id'];
		}
		
		if (! $userId) {
			$userId = $this->app->input->getInt ( 'original_userid', null);
		}

		$redirectUrl = $this->getUserFormUrl ( $lang, $Itemid, $original_option, $original_view, $original_task, $original_layout );
		
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
		$cParams = $this->getModel ()->getComponentParams();
		$revokablePrivacyPolicy = $cParams->get('revokable_privacypolicy', 0);
		
		// Get current user id
		$currentUser = JFactory::getUser ();
		if ($currentUser->id != $userId) {
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), JText::_ ( 'COM_GDPR_CANT_EXPORT_OTHER_USERS' ) );
			return false;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_exportprofile', $cParams)) {
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), JText::_ ( 'COM_GDPR_NOT_ALLOWED' ) );
			return false;
		}
		
		// Load della model e checkin before exit
		$model = $this->getModel ();
		
		// If a delete notification only is requested, send email and redirect here
		if($model->getComponentParams()->get('userprofile_buttons_workingmode', 0)) {
			$resultNotification = $this->sendEmailNotification('export', $model, $userId);
			$userMessage = $resultNotification ? JText::_ ( 'COM_GDPR_REQUEST_SUCCESS' ) : JText::_ ( 'COM_GDPR_REQUEST_ERROR' );
			$this->setRedirect ( JRoute::_ ( "index.php?" . $redirectUrl, false ), $userMessage );
			return true;
		}
		
		$headerFields = array(
				JText::_('COM_GDPR_LOGS_NAME'),
				JText::_('COM_GDPR_LOGS_USERNAME'),
				JText::_('COM_GDPR_LOGS_EMAIL'),
				JText::_('COM_GDPR_LOGS_REGISTERDATE'),
				JText::_('COM_GDPR_LOGS_LASTVISITDATE'),
				JText::_('COM_GDPR_LOGS_BLOCK'),
				JText::_('COM_GDPR_LOGS_SENDEMAIL'),
				JText::_('COM_GDPR_LOGS_LANGUAGE'),
				JText::_('COM_GDPR_LOGS_EDITOR'),
				JText::_('COM_GDPR_LOGS_TIMEZONE')
		);
		
		$hasAdminFields = false;
		$hasPrivacyFields = false;
		$hasProfileFields = false;
		$hasProfileCustomFields = false;
		$hasProfileRawFields = false;
		$fulldata = array();
		$nullDate = JFactory::getDbo()->getNullDate();
		$fieldsToLoadArray = array('name', 'username', 'email', 'registerDate', 'lastvisitDate','block','sendEmail','params');
		foreach ($fieldsToLoadArray as $fieldToLoad) {
			if(stripos($fieldToLoad, 'date')) {
				if($currentUser->$fieldToLoad == $nullDate) {
					$fulldata[] = JText::_('COM_GDPR_NEVER');
				} else {
					$fulldata[] = JHtml::_('date', $currentUser->$fieldToLoad, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
				}
			} elseif($fieldToLoad == 'params') {
				$decodedParams = json_decode($currentUser->$fieldToLoad, true);
				$fulldata[] = isset($decodedParams['language']) ? $decodedParams['language'] : JText::_('COM_GDPR_DEFAULT');
				$fulldata[] = isset($decodedParams['editor']) ? $decodedParams['editor'] : JText::_('COM_GDPR_DEFAULT');
				$fulldata[] = isset($decodedParams['timezone']) ? $decodedParams['timezone'] : JText::_('COM_GDPR_DEFAULT');
				// Detect Admin fields
				if(isset($decodedParams['admin_language'])) {
					$fulldata[] = isset($decodedParams['admin_style']) ? $decodedParams['admin_style'] : JText::_('COM_GDPR_DEFAULT');
					$fulldata[] = isset($decodedParams['admin_language']) ? $decodedParams['admin_language'] : JText::_('COM_GDPR_DEFAULT');
					$fulldata[] = isset($decodedParams['helpsite']) ? $decodedParams['helpsite'] : JText::_('COM_GDPR_DEFAULT');
					$headerFields[] = JText::_('COM_GDPR_LOGS_ADMIN_TEMPLATE');
					$headerFields[] = JText::_('COM_GDPR_LOGS_ADMIN_LANGUAGE');
					$headerFields[] = JText::_('COM_GDPR_LOGS_HELPSITE');
					$hasAdminFields = true;
				}
				// Integration with Joomla 3.9+ Privacy tool suite, if Joomla 3.9+ detect if an admin has user action log options to export as well
				if(version_compare(JVERSION, '3.9', '>=')) {
					if(isset($decodedParams['logs_notification_option'])) {
						$fulldata[] = $decodedParams['logs_notification_option'] == '1' ? JText::_('JYES') : JText::_('JNO');
						$fulldata[] = implode(', ', $decodedParams['logs_notification_extensions']);
						$headerFields[] = JText::_('COM_GDPR_LOGS_LOGS_NOTIFICATION_OPTION');
						$headerFields[] = JText::_('COM_GDPR_LOGS_LOGS_NOTIFICATION_EXTENSIONS');
						$hasPrivacyFields = true;
					}
				}
			} else {
				if($currentUser->$fieldToLoad == '0') {
					$fulldata[] = JText::_('JYES');
				} elseif ($currentUser->$fieldToLoad == '1') {
					$fulldata[] = JText::_('JNO');
				} else {
					$fulldata[] = $currentUser->$fieldToLoad;
				}
			}
		}
		
		// Evaluate the addition of the privacy policy field and value
		if($revokablePrivacyPolicy) {
			$headerFields[] = JText::_('COM_GDPR_LOGS_PRIVACY_POLICY');
			$db = JFactory::getDbo();
			$query = "SELECT " . $db->quoteName('profile_value') .
					 "\n FROM " . $db->quoteName('#__user_profiles') .
					 "\n WHERE " .  $db->quoteName('user_id') . " = " . (int) $currentUser->id .
					 "\n AND " .  $db->quoteName('profile_key') . " = " . $db->quote('gdpr_consent_status');
			$latestPrivacyPolicy = $db->setQuery($query)->loadResult();
			$fulldata[] = $latestPrivacyPolicy ? JText::_('JYES') : JText::_('JNO');
		}
		
		// Manage additional profile data field, generated by the user profile plugin if enabled
		if(isset($jFormArray['profile'])) {
			foreach ($jFormArray['profile'] as $profileField=>$profileValue) {
				$headerFields[] = JText::_('COM_GDPR_LOGS_' . strtoupper($profileField) . '_PROFILE');
				$fulldata[] = $profileValue;
			}
			$hasProfileFields = true;
		}
		
		// Manage additional custom fields
		if(isset($jFormArray['com_fields'])) {
			foreach ($jFormArray['com_fields'] as $profileCustomField=>$profileCustomValue) {
				$headerFields[] = $profileCustomField;
				$fulldata[] = $profileCustomValue;
			}
			$hasProfileCustomFields = true;
		}

		// Export all the raw form fields
		if ($this->getModel ()->getComponentParams ()->get ( 'include_raw_post_fields', 0 )) {
			$headerFields [] = JText::_ ( 'COM_GDPR_LOGS_RAW_FIELDS' );
			$dirtyArray = $this->app->input->post->getArray ();
			$cleanArray = array();
			foreach ($dirtyArray as $key=>$value) {
				if (stripos ( $key, 'original_' ) !== false) {
					continue;
				}
				if (stripos ( $key, 'gdpr_' ) !== false) {
					continue;
				}
				if (in_array ( $key, array (
						'option',
						'task',
						'view',
						'controller',
						'reportformat'
				) )) {
					continue;
				}
				// Assignment if expected field
				$cleanArray[$key] = $value;
			}
			if(!empty($cleanArray)) {
				if(version_compare(PHP_VERSION, '5.4', '>=')) {
					$fulldata [] = json_encode ( $cleanArray, JSON_UNESCAPED_UNICODE );
				} else {
					$fulldata [] = json_encode ( $cleanArray );
				}
			} else {
				$fulldata [] = '-';
			}
			$hasProfileRawFields = true;
		}
		
		if($reportFormat == 'exportcsv_btn') {
			$componentConfig = $this->getModel()->getComponentParams();
			$delimiter = $componentConfig->get('csv_delimiter', ';');
			$enclosure = $componentConfig->get('csv_enclosure', '"');
			
			// Clean dirty buffer
			ob_end_clean();
			// Open buffer
			ob_start();
			// Open out stream
			$outstream = fopen("php://output", "w");
			// Funzione di scrittura nell'output stream
			function __outputCSV(&$vals, $key, $userData) {
				fputcsv($userData[0], $vals, $userData[1], $userData[2]); // add parameters if you want
			}
			__outputCSV($headerFields, null, array($outstream, $delimiter, $enclosure));
			__outputCSV($fulldata, null, array($outstream, $delimiter, $enclosure));
			fclose($outstream);
			
			// Recupero output buffer content
			$contents = ob_get_clean();
			$exportFileExtension = '.csv';
			$contentType = 'text/plain';
		} elseif ($reportFormat == 'exportxls_btn') {
			if($cParams->get('xls_format', 1)) {
				$exportFileExtension = '.xls';
				$contentType = 'application/vnd.ms-excel';
			} else {
				$exportFileExtension = '.html';
				$contentType = 'text/html';
			}
			$indexIncrement = 0;
			$reportTitle = JText::sprintf('COM_GDPR_LOGS_REPORT_XLS_TITLE', str_replace('_', ' ', $dataExport));

			// Additional admin fields
			if($hasAdminFields) {
				$adminFieldsHeader = "<td><font color='#FFFFFF'>{$headerFields[10]}</font></td>" .
									 "<td><font color='#FFFFFF'>{$headerFields[11]}</font></td>" .
									 "<td><font color='#FFFFFF'>{$headerFields[12]}</font></td>";
				$adminFieldsRow = "<td>{$fulldata[10]}</td>" .
								  "<td>{$fulldata[11]}</td>" .
								  "<td>{$fulldata[12]}</td>";
				$indexIncrement += 3;
			} else {
				$adminFieldsHeader = '';
				$adminFieldsRow = '';
			}

			// Additional Joomla 3.9+ privacy admin fields
			if($hasPrivacyFields) {
				$privacyFieldsHeader = "<td><font color='#FFFFFF'>{$headerFields[10 + $indexIncrement]}</font></td>" .
									   "<td><font color='#FFFFFF'>{$headerFields[11 + $indexIncrement]}</font></td>";
				$privacyFieldsRow = "<td>{$fulldata[10 + $indexIncrement]}</td>" .
								    "<td>{$fulldata[11 + $indexIncrement]}</td>";
				$indexIncrement += 2;
			} else {
				$privacyFieldsHeader = '';
				$privacyFieldsRow = '';
			}

			// Privacy policy field
			if($revokablePrivacyPolicy) {
				$privacyPolicyHeader = "<td><font color='#FFFFFF'>{$headerFields[10 + $indexIncrement]}</font></td>";
				$privacyPolicyRow = "<td>{$fulldata[10 + $indexIncrement]}</td>";
				$indexIncrement += 1;
			} else {
				$privacyPolicyHeader = '';
				$privacyPolicyRow = '';
			}

			$profileFields = '';
			$profileValues = '';
			if($hasProfileFields) {
				for($i=10+$indexIncrement;$i<count($headerFields);$i++) {
					$profileFields .= "<td><font color='#FFFFFF'>{$headerFields[$i]}</font></td>";
					$profileValues .= "<td>{$fulldata[$i]}</td>";
					$indexIncrement++;
				}
			}
			
			// Add support for custom fields
			$profileCustomFields = '';
			$profileCustomValues = '';
			if($hasProfileCustomFields) {
				for($i=10+$indexIncrement;$i<count($headerFields);$i++) {
					$profileCustomFields .= "<td><font color='#FFFFFF'>{$headerFields[$i]}</font></td>";
					$profileCustomValues .= "<td>{$fulldata[$i]}</td>";
					$indexIncrement++;
				}
			}
			
			// Add final raw fields
			$rawFieldsHeader = '';
			$rawFieldsRow = '';
			if($hasProfileRawFields && isset($headerFields[10 + $indexIncrement])) {
				$rawFieldsHeader = "<td><font color='#FFFFFF'>{$headerFields[10 + $indexIncrement]}</font></td>";
				$rawFieldsRow = "<td>{$fulldata[10 + $indexIncrement]}</td>";
				$indexIncrement += 1;
			}
			
			$contents = <<<EOT
			<html>
			<head>
			<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
			</head>
			<body>
			<table>
				<tr><td><font size="4" color="#CE1300">$reportTitle</font></td></tr>
				<tr><td></td></tr>
				<tr bgcolor="#0066ff">
					<td><font color="#FFFFFF">{$headerFields[0]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[1]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[2]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[3]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[4]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[5]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[6]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[7]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[8]}</font></td>
					<td><font color="#FFFFFF">{$headerFields[9]}</font></td>
					$adminFieldsHeader
					$privacyFieldsHeader
					$privacyPolicyHeader
					$profileFields
					$profileCustomFields
					$rawFieldsHeader
				</tr>
					
				<tr>
					<td>{$fulldata[0]}</td>
					<td>{$fulldata[1]}</td>
					<td>{$fulldata[2]}</td>
					<td>{$fulldata[3]}</td>
					<td>{$fulldata[4]}</td>
					<td>{$fulldata[5]}</td>
					<td>{$fulldata[6]}</td>
					<td>{$fulldata[7]}</td>
					<td>{$fulldata[8]}</td>
					<td>{$fulldata[9]}</td>
					$adminFieldsRow
					$privacyFieldsRow
					$privacyPolicyRow
					$profileValues
					$profileCustomValues
					$rawFieldsRow
				</tr>
			</table>
			</body>	
			</html>
EOT;
		}
	
		// Recupero output buffer content
		$exportedFileName = 'profile_data_' . $dataExport . $exportFileExtension;
		
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="' . $exportedFileName . '"' );
		header ( 'Content-Type: ' . $contentType );
		echo $contents;
			
		exit ();
	}
	
	/**
	 * Returns the contents of the cookie policy to an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function getCookiePolicy() {
		$cookiePolicyText = $this->getModel ()->getComponentParams()->get('cookie_policy_contents', null);
		
		$compatModuleRendererLessThreeEight = JPATH_ROOT . '/libraries/joomla/document/html/renderer/module.php';
		if($this->app->input->get('format') == 'raw' && file_exists( $compatModuleRendererLessThreeEight )) {
			require_once $compatModuleRendererLessThreeEight;
		}
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dummyParams = new JRegistry();
		$elm = new stdClass();
		$elm->text = $cookiePolicyText;
		$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$elm, &$dummyParams, 0));
		
		echo '<div>' . JText::_($elm->text) . '</div>';
	}
	
	/**
	 * Returns the contents of the privacy policy to an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function getPrivacyPolicy() {
		$privacyPolicyText = $this->getModel ()->getComponentParams()->get('privacy_policy_contents', null);
		
		$compatModuleRendererLessThreeEight = JPATH_ROOT . '/libraries/joomla/document/html/renderer/module.php';
		if($this->app->input->get('format') == 'raw' && file_exists( $compatModuleRendererLessThreeEight )) {
			require_once $compatModuleRendererLessThreeEight;
		}
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dummyParams = new JRegistry();
		$elm = new stdClass();
		$elm->text = $privacyPolicyText;
		$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$elm, &$dummyParams, 0));
		
		echo '<div>' . JText::_($elm->text) . '</div>';
	}
	
	/**
	 * Returns the contents of the checkbox privacy policy to an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function getCheckboxPolicy() {
		$checkboxPolicyText = $this->getModel ()->getComponentParams()->get('checkbox_contents', null);
	
		$compatModuleRendererLessThreeEight = JPATH_ROOT . '/libraries/joomla/document/html/renderer/module.php';
		if($this->app->input->get('format') == 'raw' && file_exists( $compatModuleRendererLessThreeEight )) {
			require_once $compatModuleRendererLessThreeEight;
		}
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dummyParams = new JRegistry();
		$elm = new stdClass();
		$elm->text = $checkboxPolicyText;
		$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$elm, &$dummyParams, 0));
	
		echo '<div>' . JText::_($elm->text) . '</div>';
	}
	
	/**
	 * Store the consent for a given form checkbox of the privacy policy
	 *
	 * @access public
	 * @return void
	 */
	public function getConsent() {
		$model = $this->getModel();
	
		// Retrieve, sanitize and build posted data
		$data = array();
		$data['url'] = urldecode($this->app->input->post->getString ('url', null));
		if($formId = $this->app->input->post->getString('formid', null)) {
			$data['formid'] = $formId;
		}
		if($formName = $this->app->input->post->get('formname', null)) {
			$data['formname'] = $formName;
		}
	
		try {
			$consented = $model->loadConsentEntity($data);
		} catch(Exception $e) {
			// No exception thrown
		}
	
		echo $consented;
		jexit();
	}
	
	/**
	 * Store the consent for a given form checkbox of the privacy policy
	 *
	 * @access public
	 * @return void
	 */
	public function storeConsent() {
		$model = $this->getModel();
		$lastId = 0;
		
		// Retrieve, sanitize and build posted data
		$data = array();
		$data['url'] = urldecode($this->app->input->post->getString ('url', null));
		if($formId = $this->app->input->post->getString('formid', null)) {
			$data['formid'] = $formId; 
		}
		if($formName = $this->app->input->post->get('formname', null)) {
			$data['formname'] = $formName;
		}
		if($formFields = $this->app->input->post->get('formfields', array (), 'array' )) {
			$data['formfields'] = json_encode($formFields);
		}
		
		try {
			$lastId = $model->storeConsentEntity($data);
		} catch(Exception $e) {
			// No exception thrown
		}
		
		echo $lastId;
		jexit();
	}
	
	/**
	 * Delete the consent for a given form checkbox of the privacy policy
	 *
	 * @access public
	 * @return void
	 */
	public function deleteConsent() {
		$model = $this->getModel();
		
		// Retrieve, sanitize and build posted data
		$data = array();
		$data['url'] = urldecode($this->app->input->post->getString ('url', null));
		if($formId = $this->app->input->post->getString('formid', null)) {
			$data['formid'] = $formId;
		}
		if($formName = $this->app->input->post->get('formname', null)) {
			$data['formname'] = $formName;
		}
		
		try {
			// Notify admins that someone revoked a consent
			if($model->getComponentParams()->get('notify_revoked_consents', 0)) {
				$this->sendEmailConsentsRevoked($data, $model);
			}
			
			$model->deleteConsentEntity($data);
		} catch(Exception $e) {
			// No exception thrown
		}
		
		jexit();
	}
	
	/**
	 * Returns the contents of the cookie policy to an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function getCookieCategoryDescription() {
		$category = $this->app->input->getInt('gdpr_cookie_category');
		
		$cookieCategoryDescription = $this->getModel ()->getComponentParams()->get('cookie_category' . $category . '_description', null);
	
		$compatModuleRendererLessThreeEight = JPATH_ROOT . '/libraries/joomla/document/html/renderer/module.php';
		if($this->app->input->get('format') == 'raw' && file_exists( $compatModuleRendererLessThreeEight )) {
			require_once $compatModuleRendererLessThreeEight;
		}
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dummyParams = new JRegistry();
		$elm = new stdClass();
		$elm->text = $cookieCategoryDescription;
		$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$elm, &$dummyParams, 0));
	
		echo '<div>';
		echo '<div class="cc-cookie-category-title">' . JText::_($this->getModel ()->getComponentParams()->get('cookie_category' . $category . '_name')) . '</div>';
		echo '<div class="cc-cookie-category-description">' . JText::_($elm->text) . '</div>';
		
		// Output the cookies in this category
		$cookiesStringInThisCategory = trim($this->getModel ()->getComponentParams()->get('cookie_category' . $category . '_list', null));
		if($cookiesStringInThisCategory) {
			$cookiesInThisCategory = explode(PHP_EOL, $cookiesStringInThisCategory);
			if(!empty($cookiesInThisCategory)) {
				echo '<fieldset class="cc-cookie-list-title"><legend>' . JText::_('COM_GDPR_COOKIE_LIST') . '</legend>';
				echo '<ul class="cc-cookie-category-list">';
				foreach ($cookiesInThisCategory as &$cookieInThisCategory) {
					$cookieInThisCategory = trim($cookieInThisCategory);
					if($cookieInThisCategory == '') {
						continue;
					}
					echo '<li>' . $cookieInThisCategory . '</li>';
				}
				echo '</ul></fieldset>';
			}
		} else {
			echo '<fieldset class="cc-cookie-list-title"><legend>' . JText::_('COM_GDPR_NO_COOKIE_IN_THIS_CATEGORY') . '</legend></fieldset>';
		}
		
		// Output the domains in this category
		$domainsStringInThisCategory = trim($this->getModel ()->getComponentParams()->get('domains_category' . $category . '_list', null));
		if($domainsStringInThisCategory) {
			$domainsInThisCategory = explode(PHP_EOL, $domainsStringInThisCategory);
			if(!empty($domainsInThisCategory)) {
				echo '<fieldset class="cc-cookie-list-title"><legend>' . JText::_('COM_GDPR_DOMAINS_LIST') . '</legend>';
				echo '<ul class="cc-cookie-category-list">';
				foreach ($domainsInThisCategory as &$domainInThisCategory) {
					$domainInThisCategory = trim($domainInThisCategory);
					if($domainInThisCategory == '') {
						continue;
					}
					echo '<li>' . $domainInThisCategory . '</li>';
				}
				echo '</ul></fieldset>';
			}
		} else {
			echo '<fieldset class="cc-cookie-list-title"><legend>' . JText::_('COM_GDPR_NO_DOMAINS_IN_THIS_CATEGORY') . '</legend></fieldset>';
		}
		echo '</div>';
	}
	
	/**
	 * Process the cookie categories on an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function processCookieCategory() {
		$category = $this->app->input->getInt('gdpr_cookie_category');
		$categoryState = $this->app->input->getInt('gdpr_cookie_category_state');
		
		$session = $this->app->getSession();
		switch ($categoryState) {
			case 0:
				$session->set('gdpr_cookie_category_disabled_' . $category, -1);
				break;
				
			case 1:
				$session->set('gdpr_cookie_category_disabled_' . $category, 1);
				break;
		}
		
		// Cookie consent tracking
		$model = $this->getModel();
		
		// Retrieve, sanitize and build posted data based on reverse logic
		$cookieCategory = $this->app->input->post->getInt ('gdpr_cookie_category', 0);
		$cookieCategoryState = !$this->app->input->post->getInt ('gdpr_cookie_category_state', 0);
		try {
			$model->storeCookieConsentEntity($cookieCategory, $cookieCategoryState);
		} catch(Exception $e) {
			// No exception thrown
		}
		
		jexit();
	}
	
	/**
	 * Process the cookie categories on an ajax request
	 *
	 * @access public
	 * @return void
	 */
	public function processGenericCookieCategories() {
		// Check if cookie consent tracking is enabled as well
		$model = $this->getModel();
			
		// Retrieve, sanitize and build posted data based on reverse logic
		$cookieGenericState = $this->app->input->post->getInt ('gdpr_generic_cookie_consent', 0);
		try {
			$model->storeCookieGenericConsentEntity($cookieGenericState);
		} catch(Exception $e) {
			// No exception thrown
		}
	
		jexit();
	}
	
	/**
	 * Retrieve data for a given dynamic checkbox starting from a unique identifier 'placeholder'
	 *
	 * @access public
	 * @return mixed
	 */
	public function getCheckbox() {
		$model = $this->getModel();
		
		// Retrieve, sanitize and build posted data
		$placeholder = $this->app->input->post->getString ('checkbox_placeholder', null);
		$currentUrl = urldecode($this->app->input->post->getString ('url', null));
		
		try {
			$checkboxData = $model->getCheckboxData($placeholder, $currentUrl);
		} catch(Exception $e) {
			// No exception thrown
		}
		
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		echo json_encode($checkboxData);
		jexit();
	}
	
	/**
	 * Retrieve description for a given dynamic checkbox starting from a unique identifier 'placeholder'
	 *
	 * @access public
	 * @return mixed
	 */
	public function getCheckboxDescription() {
		$model = $this->getModel();
	
		// Retrieve, sanitize and build posted data
		$placeholder = $this->app->input->getString ('checkbox_placeholder', null);
	
		try {
			$checkboxDescription = $model->getCheckboxDescription($placeholder);
		} catch(Exception $e) {
			// No exception thrown
		}
	
		$compatModuleRendererLessThreeEight = JPATH_ROOT . '/libraries/joomla/document/html/renderer/module.php';
		if($this->app->input->get('format') == 'raw' && file_exists( $compatModuleRendererLessThreeEight )) {
			require_once $compatModuleRendererLessThreeEight;
		}
		JPluginHelper::importPlugin('content');
		$dispatcher = JEventDispatcher::getInstance();
		$dummyParams = new JRegistry();
		$elm = new stdClass();
		$elm->text = $checkboxDescription;
		$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$elm, &$dummyParams, 0));
		
		echo '<div>' . JText::_($elm->text) . '</div>';
	}
}