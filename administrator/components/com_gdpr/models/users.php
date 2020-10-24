<?php
// namespace administrator\components\com_gdpr\models;
/**
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Users model responsibilities
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
interface IGdprModelUsers {
	/**
	 * Update the entity status
	 *
	 * @access public
	 * @param array $ids
	 * @param string $task
	 * @return array[] &
	 */
	public function violatedEntity($ids, $task);
}

/**
 * Users model responsibilities
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
class GdprModelUsers extends GdprModel implements IGdprModelUsers {
	/**
	 * Restituisce la query string costruita per ottenere il wrapped set richiesto in base
	 * allo userstate, opzionalmente seleziona i campi richiesti
	 * 
	 * @access private
	 * @return string
	 */
	protected function buildListQuery($fields = 'a.*') {
		// WHERE
		$where = array("(a.username != '' AND a.name != '')");
		$whereString = null;
				
		//Filtro testo
		if($this->state->get('searchword')) {
			$where[] = "\n (a.email LIKE " .
						$this->_db->quote('%' . $this->state->get('searchword') . '%') .
						"\n OR a.name LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . ")";
		}
		
		if($this->state->get('violated_user', '') !== '') {
			$violatedUser = (int)$this->state->get('violated_user');
			if($violatedUser == 1) {
				$where[] = "\n (u.violated_user = " . $violatedUser . ")";
			} elseif($violatedUser == 0) {
				$where[] = "\n (u.violated_user = 0 OR ISNULL(u.violated_user))";
			}
		}
		
		//Filtro periodo
		if($this->state->get('fromPeriod')) {
			$where[] = "\n a.registerDate > " . $this->_db->quote($this->state->get('fromPeriod'));
		}
		
		if($this->state->get('toPeriod')) {
			$toPeriod = $this->state->get('toPeriod');
			$toPeriod = date ( "Y-m-d", strtotime ( "+1 day", strtotime ( $toPeriod ) ) );
			$where[] = "\n a.registerDate < " . $this->_db->quote($toPeriod);
		}
		
		if(version_compare(JVERSION, '3.9', '>=') && !$this->getComponentParams()->get('consent_registry_include_pseudonymised', 0)) {
			$where[] = "\n SUBSTRING(a.email, -7) != " .  $this->_db->quote('invalid');
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
		
		
		$query = "SELECT $fields, u.violated_user" .
				 "\n FROM #__users AS a" .
				 "\n LEFT JOIN #__gdpr_databreach_users AS u" .
				 "\n ON a.id = u.userid" .
				 $whereString . 
				 $orderString;
		return $query;
	}

	/**
	 * Main get data method
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
		$types[] = JHtml::_('select.option', '1', JText::_('COM_GDPR_USERS_VIOLATED' ) );
		$types[] = JHtml::_('select.option', '0', JText::_('COM_GDPR_USERS_NOTVIOLATED' ) );
		 
		$lists['violated_user'] = JHtml::_('select.genericlist', $types, 'violated_user', 'class="inputbox hidden-phone" size="1" onchange="document.adminForm.task.value=\'users.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('violated_user'));
			
		return $lists;
	}
	
	/**
	 * Purge the cache of all messages in a single operation
	 * 
	 * @access public
	 * @param array $cids
	 * @return boolean
	 */
	
	/**
	 * Update the entity status
	 *
	 * @access public
	 * @param array $ids
	 * @param string $task
	 * @return array[] &
	 */
	public function violatedEntity($ids, $task) {
		// Ciclo su ogni entity da cancellare
		if (is_array ( $ids ) && count ( $ids )) {
			// Determine the violated status for the user
			$statusVarValue = $task == 'violatedEntity' ? 1 : 0;
			
			foreach ($ids as $entityId) {
				try {
			 		// Delete session status still not active session for Joomla session lifetime
			 		$queryStatus = 	"INSERT INTO #__gdpr_databreach_users (userid, violated_user) VALUES (" .
									$entityId . ", " .
									$statusVarValue . ") " .
									"ON DUPLICATE KEY UPDATE " . $this->_db->quoteName('violated_user') . " = " . $this->_db->quote($statusVarValue);
			 		// Purge session status
			 		$this->_db->setQuery($queryStatus)->execute();
			 	} catch ( Exception $e ) {
			 		$gdprException = new GdprException ( $e->getMessage (), 'error' );
			 		$this->setError ( $gdprException );
			 		return false;
			 	}
			}
		}
	
		return true;
	}
	
	/**
	 * Update the entity status
	 *
	 * @access public
	 * @param array $ids
	 * @param string $task
	 * @return mixed array[]& on success, boolean on failure
	 */
	public function notifyDataBreach($ids) {
		if(!count($ids)) {
			$gdprException = new GdprException ( JText::_('COM_GDPR_ERROR_RECORDS_EMPTY'), 'error' );
			$this->setError ( $gdprException );
			return false;
		}
		
		// Build query
		$query = "SELECT a.name, a.email" .
				 "\n FROM #__users AS a" .
				 "\n WHERE a.id IN( " . implode(',', $ids) . ")";
		$this->_db->setQuery ( $query );
		try {
			$resultSet = $this->_db->loadObjectList ();
				
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
		
		// Check for notify email addresses
		$validEmailAddresses = array();
		foreach ($resultSet as $user) {
			$validName = $user->name;
			$validEmail = $user->email;
			if(filter_var(trim($validEmail), FILTER_VALIDATE_EMAIL)) {
				$validEmailAddresses[] = array('nameofuser'=>$validName, 'email'=>trim($validEmail));
			}
		}
		
		$sentEmailAddresses = array();
		if(!empty($validEmailAddresses)) {
			$cParams = $this->getComponentParams();
			
			// Joomla global configuration
			$jConfig = JFactory::getConfig();
			
			// Build the email subject and message, purify the language override for the editor message text
			$subject  = JText::_($cParams->get('databreach_email_subject'));
			$msg      = JText::_($cParams->get('databreach_email_content'));
		
			// Placeholder replacer and customized email for each user
			$sitename = $jConfig->get('sitename');
			$siteUrl = JUri::root(false);
			$date = JHtml::_('date', 'now', JText::_('DATE_FORMAT_LC1'));
			$fromEmail = $cParams->get('databreach_mailfrom', $jConfig->get('mailfrom'));
			$fromName = $cParams->get('databreach_fromname', $jConfig->get('fromname'));
			
			$subject = str_ireplace('{sitename}', $sitename, $subject);
			$msg = JString::str_ireplace('{sitename}', $sitename, $msg);
			$msg = JString::str_ireplace('{siteurl}', $siteUrl, $msg);
			$msg = JString::str_ireplace('{date}', $date, $msg);
			$msg = JString::str_ireplace('{emailaddress}', $fromEmail, $msg);
			
			// Build a separate email for the garante
			if($notifyGarante = $cParams->get('databreach_garante_notify', 0)) {
				$garanteSubject  = JText::_($cParams->get('databreach_garante_email_subject'));
				$garanteSubject = str_ireplace('{sitename}', $sitename, $garanteSubject);
				$garanteMsg = JText::_($cParams->get('databreach_garante_email_content'));
				$garanteMsg = JString::str_ireplace('{sitename}', $sitename, $garanteMsg);
				$garanteMsg = JString::str_ireplace('{siteurl}', $siteUrl, $garanteMsg);
				$garanteMsg = JString::str_ireplace('{date}', $date, $garanteMsg);
				$garanteMsg = JString::str_ireplace('{emailaddress}', $fromEmail, $garanteMsg);
			}
			
			// Send the email
			$mailer = JFactory::getMailer();
			$mailer->isHtml(true);
			
			$mailer->setSender(array($fromEmail, $fromName));
			$mailer->addReplyTo($fromEmail, $fromName);
		
			foreach ($validEmailAddresses as $userEmail) {
				// Set/Reset subject
				$mailer->setSubject($subject);
				// Add the recipient of this user
				$mailer->addRecipient($userEmail['email']);
				
				// Customize the email content
				$bodyMsg = JString::str_ireplace('{nameofuser}', $userEmail['nameofuser'], $msg);
				
				// Set the email body
				$mailer->setBody($bodyMsg);
				
				// The Send method will raise an error via JError on a failure, we do not need to check it ourselves here
				if($mailer->Send()) {
					// Store for controller redirect message
					$sentEmailAddresses[] = $userEmail['email'];
				}
				
				// Clear all recipients
				$mailer->clearAddresses();
				
				// Add the email send to the garante
				$validEmailGarante = $cParams->get('databreach_garante_email_address', '');
				if($notifyGarante && filter_var(trim($validEmailGarante), FILTER_VALIDATE_EMAIL)) {
					// Set/Reset subject
					$mailer->setSubject($garanteSubject);
					// Add the recipient of the garante
					$mailer->addRecipient($validEmailGarante);
					// Customize the email content
					$garanteBodyMsg = JString::str_ireplace('{nameofuser}', $userEmail['nameofuser'], $garanteMsg);
					$mailer->setBody($garanteBodyMsg);
					$mailer->Send();
					// Clear all recipients
					$mailer->clearAddresses();
				}
			}
		}
		
		return $sentEmailAddresses;
	}
	
	/**
	 * Esplica la funzione di esportazione della lista messaggi
	 * in formato CSV per i record estratti dai filtri userstate attivi
	 * @access public
	 * @return Object[]&
	 */
	public function exportUsers() {
		// Obtain query string
		$query = $this->buildListQuery('a.name, a.username, a.email, a.registerDate, a.lastvisitDate');
		$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit') );
		$resultSet = $this->_db->loadAssocList();
	
		if(!is_array($resultSet) || !count($resultSet)) {
			return false;
		}
	
		return $resultSet;
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