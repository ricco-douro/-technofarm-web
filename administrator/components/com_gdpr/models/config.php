<?php
// namespace administrator\components\com_gdpr\models;
/**
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport('joomla.application.component.modelform');

/**
 * Config model responsibilities
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
interface IConfigModel {
	
	/**
	 * Ottiene i dati di configurazione da db params field record component
	 *
	 * @access public
	 * @return Object
	 */
	public function &getData();
	
	/**
	 * Effettua lo store dell'entity config
	 *
	 * @access public
	 * @return boolean
	 */
	public function storeEntity();
	
	/**
	 * Reset all consents for #__user_profiles table to request a new privacy policy update greement
	 *
	 * @access public
	 * @return boolean
	 */
	public function resetAllConsents();
}

/**
 * Config model concrete implementation
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage models
 * @since 1.6
 */
class GdprModelConfig extends JModelForm implements IConfigModel {
	/**
	 * Variables in request array
	 *
	 * @access protected
	 * @var Object
	 */
	protected $requestArray;
	
	/**
	 * Variables in request array name
	 *
	 * @access protected
	 * @var Object
	 */
	protected $requestName;
	
	/**
	 * Clean the cache
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 * @return  void
	 * @since   11.1
	 */
	private function cleanComponentCache($group = null, $client_id = 0) {
		// Initialise variables;
		$conf = JFactory::getConfig();
		$dispatcher = JDispatcher::getInstance();
	
		$options = array(
				'defaultgroup' => ($group) ? $group : $this->app->input->get('option'),
				'cachebase' => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'));
	
		$cache = JCache::getInstance('callback', $options);
		$cache->clean();
	
		// Trigger the onContentCleanCache event.
		$dispatcher->trigger('onContentCleanCache', $options);
	}
	
	/**
	 * Ottiene i dati di configurazione da db params field record component
	 *
	 * @access public
	 * @return Object
	 */
	private function &getConfigData() { 
		$instance = JComponentHelper::getParams('com_gdpr'); 
		return $instance;
	}
	
	/**
	 * Effettua lo storing dell'asset delle permissions sul component level
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function storePermissionsAsset($data) {
		// Save the rules.
		if (isset ( $data ['params'] ) && isset ( $data ['params'] ['rules'] )) {
			$form = $this->getForm ( $data );
			// Validate the posted data.
			$postedRules = $this->validate ( $form, $data ['params'] );
				
			$rules = new JAccessRules ( $postedRules ['rules'] );
			$asset = JTable::getInstance ( 'asset' );
				
			if (! $asset->loadByName ( $data ['option'] )) {
				$root = JTable::getInstance ( 'asset' );
				$root->loadByName ( 'root.1' );
				$asset->name = $data ['option'];
				$asset->title = $data ['option'];
				$asset->setLocation ( $root->id, 'last-child' );
			}
			$asset->rules = ( string ) $rules;
				
			if (! $asset->check () || ! $asset->store ()) {
				$this->setError ( $asset->getError () );
				return false;
			}
		}
	
		return true;
	}
	
	/**
	 * Kill CB redirection to avoid redirect loop
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function killCBRedirectionPlugin() {
		if($cbPlugin = JPluginHelper::getPlugin('system', 'communitybuilder')) {
			$cbParams = json_decode($cbPlugin->params);
			$cbParams->redirect_urls = 0;
			$cbParams->rewrite_urls = 0;
			$updatedParams = json_encode($cbParams);
			$query = "UPDATE " . $this->_db->quoteName('#__extensions') .
					 "\n SET " . $this->_db->quoteName('params') . " = " . $this->_db->quote($updatedParams) .
					 "\n WHERE " . $this->_db->quoteName('type') . " = " . $this->_db->quote('plugin') .
					 "\n AND " . $this->_db->quoteName('folder') . " = " . $this->_db->quote('system') . 
					 "\n AND " . $this->_db->quoteName('element') . " = " . $this->_db->quote('communitybuilder');
			try {
				$this->_db->setQuery($query)->execute();
			} catch (Exception $e) {
				// No errors go on
			}
		}
		return true;
	}
	
	/**
	 * Method to get a form object.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		jimport ( 'joomla.form.form' );
		JForm::addFormPath ( JPATH_ADMINISTRATOR . '/components/com_gdpr' );
	
		// Get the form.
		$form = $this->loadForm ( 'com_gdpr.component', 'config', array ('control' => 'params', 'load_data' => $loadData ), false, '/config' );
	
		if (empty ( $form )) {
			return false;
		}
	
		return $form;
	}
	
	/**
	 * Ottiene i dati di configurazione del componente
	 *
	 * @access public
	 * @return Object
	 */
	public function &getData() {
		return $this->getConfigData ();
	}
	/**
	 * Effettua lo store dell'entity config
	 *
	 * @access public
	 * @return boolean
	 */
	public function storeEntity() {
		$table = JTable::getInstance('extension');

		try {
			// Found as installed extension
			if (!$extensionID = $table->find(array('element' => 'com_gdpr'))) {
				throw new GdprException($table->getError (), 'error');
			} 
			
			$table->load($extensionID);

			// Translate posted jform array to params for ORM table binding
			$post = $this->app->input->post;
			
			// Check if block users has been enabled and if CB is installed and redirecting
			if((int)@$this->requestArray[$this->requestName]['params']['block_privacypolicy'] == 1) {
				$currentParams = json_decode($table->params);
				$blockPrivacyPolicyCurrentStatus = $currentParams->block_privacypolicy;
				if(!$blockPrivacyPolicyCurrentStatus) {
					// Kill CB redirection plugin if any
					$this->killCBRedirectionPlugin();
				}
			}

			if (!$table->bind ($post->getArray($this->requestArray[$this->requestName]))) {
				throw new GdprException($table->getError (), 'error');
			}
			
			// Unserialize and replace offline_message param as RAW no filter - textareas
			$unserializedParams = json_decode($table->params);
			$unserializedParams->checkbox_template_container = strip_tags($this->requestArray[$this->requestName]['params']['checkbox_template_container'], '<div><span><p><dt><dd>');
			$unserializedParams->checkbox_template_label = strip_tags($this->requestArray[$this->requestName]['params']['checkbox_template_label'], '<div><span><p><a><dt><dd>');
			$unserializedParams->checkbox_template_controls = strip_tags($this->requestArray[$this->requestName]['params']['checkbox_template_controls'], '<div><span><p><dt><dd>');
			
			// Editors parameters, sanitize multilanguage strings if detected
			$unserializedParams->message = $this->requestArray[$this->requestName]['params']['message'];
			if(JString::strpos($unserializedParams->message, 'COM_GDPR_') !== false) {
				$unserializedParams->message = strip_tags($unserializedParams->message);
			}
			
			$unserializedParams->deny_message = $this->requestArray[$this->requestName]['params']['deny_message'];
			if(JString::strpos($unserializedParams->message, 'COM_GDPR_') !== false) {
				$unserializedParams->deny_message = strip_tags($unserializedParams->deny_message);
			}
			
			$unserializedParams->databreach_email_content = $this->requestArray[$this->requestName]['params']['databreach_email_content'];
			if(JString::strpos($unserializedParams->databreach_email_content, 'COM_GDPR_') !== false) {
				$unserializedParams->databreach_email_content = strip_tags($unserializedParams->databreach_email_content);
			}
			
			$unserializedParams->databreach_garante_email_content = $this->requestArray[$this->requestName]['params']['databreach_garante_email_content'];
			if(JString::strpos($unserializedParams->databreach_garante_email_content, 'COM_GDPR_') !== false) {
				$unserializedParams->databreach_garante_email_content = strip_tags($unserializedParams->databreach_garante_email_content);
			}
			
			$unserializedParams->cookie_policy_contents = $this->requestArray[$this->requestName]['params']['cookie_policy_contents'];
			if(JString::strpos($unserializedParams->cookie_policy_contents, 'COM_GDPR_') !== false) {
				$unserializedParams->cookie_policy_contents = strip_tags($unserializedParams->cookie_policy_contents);
			}
			
			$unserializedParams->privacy_policy_contents = $this->requestArray[$this->requestName]['params']['privacy_policy_contents'];
			if(JString::strpos($unserializedParams->privacy_policy_contents, 'COM_GDPR_') !== false) {
				$unserializedParams->privacy_policy_contents = strip_tags($unserializedParams->privacy_policy_contents);
			}
			
			$unserializedParams->checkbox_contents = $this->requestArray[$this->requestName]['params']['checkbox_contents'];
			if(JString::strpos($unserializedParams->checkbox_contents, 'COM_GDPR_') !== false) {
				$unserializedParams->checkbox_contents = strip_tags($unserializedParams->checkbox_contents);
			}
			
			$unserializedParams->cookie_category1_description = $this->requestArray[$this->requestName]['params']['cookie_category1_description'];
			if(JString::strpos($unserializedParams->cookie_category1_description, 'COM_GDPR_') !== false) {
				$unserializedParams->cookie_category1_description = strip_tags($unserializedParams->cookie_category1_description);
			}
			
			$unserializedParams->cookie_category2_description = $this->requestArray[$this->requestName]['params']['cookie_category2_description'];
			if(JString::strpos($unserializedParams->cookie_category2_description, 'COM_GDPR_') !== false) {
				$unserializedParams->cookie_category2_description = strip_tags($unserializedParams->cookie_category2_description);
			}
			
			$unserializedParams->cookie_category3_description = $this->requestArray[$this->requestName]['params']['cookie_category3_description'];
			if(JString::strpos($unserializedParams->cookie_category3_description, 'COM_GDPR_') !== false) {
				$unserializedParams->cookie_category3_description = strip_tags($unserializedParams->cookie_category3_description);
			}
			
			$unserializedParams->cookie_category4_description = $this->requestArray[$this->requestName]['params']['cookie_category4_description'];
			if(JString::strpos($unserializedParams->cookie_category4_description, 'COM_GDPR_') !== false) {
				$unserializedParams->cookie_category4_description = strip_tags($unserializedParams->cookie_category4_description);
			}

			$table->params = json_encode($unserializedParams);
			
			// Check for security safe no TEXT field exceed
			if(JString::strlen($table->params) >= 65536) {
				throw new GdprException(JText::_('COM_GDPR_MAX_CHARACTER_LIMIT_EXCEED'), 'error');
			}
			
			// pre-save checks
			if (!$table->check()) {
				throw new GdprException($table->getError (), 'error');
			}

			// save the changes
			if (!$table->store()) {
				throw new GdprException($table->getError (), 'error');
			}

			// save the changes
			if (! $this->storePermissionsAsset ( $post->getArray ( $this->requestArray[$this->requestName] ) )) {
				throw new GdprException ( JText::_ ( 'COM_GDPR_ERROR_STORING_PERMISSIONS' ), 'error' );
			}
		} catch (GdprException $e) {
			$this->setError($e);
			return false;
		} catch (Exception $e) {
			$gdprException = new GdprException($e->getMessage(), 'error');
			$this->setError($gdprException);
			return false;
		}

		// Clean the cache.
		$this->cleanComponentCache('_system', 0);
		$this->cleanComponentCache('_system', 1);
		return true;
	}
	
	/**
	 * Reset all consents for #__user_profiles table to request a new privacy policy update greement
	 *
	 * @access public
	 * @return boolean
	 */
	public function resetAllConsents(){
		$cParams = JComponentHelper::getParams('com_gdpr');
		
		try {
			$queryReset = "UPDATE " . $this->_db->quoteName('#__user_profiles') .
						  "\n SET " . $this->_db->quoteName('profile_value') . " = 0" .
						  "\n WHERE " . $this->_db->quoteName('profile_key') . " = " . $this->_db->quote('gdpr_consent_status') .
						  "\n AND " . $this->_db->quoteName('profile_value') . " = 1";
			$this->_db->setQuery($queryReset);
			$this->_db->execute();
			
			if($cParams->get('log_usernote_privacypolicy', 1)) {
				$query = "DELETE FROM " . $this->_db->quotename('#__user_notes') .
						 "\n WHERE " .  $this->_db->quotename('catid') . " = " .(int) $cParams->get('log_usernote_privacypolicy_category', 0) .
						 "\n AND " .  $this->_db->quotename('subject') . " = " . $this->_db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'));
				$this->_db->setQuery($query)->execute();
			}
			
			if(version_compare(JVERSION, '3.9', '>=') && $cParams->get('log_userconsent_privacypolicy', 1)) {
				$query = "UPDATE " . $this->_db->quotename('#__privacy_consents') .
						 "\n SET " .  $this->_db->quotename('state') . " = -1" .
						 "\n WHERE "  .  $this->_db->quotename('subject') . " = " . $this->_db->quote('COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT') .
						 "\n AND " .  $this->_db->quotename('state') . " = 1";
				$this->_db->setQuery($query)->execute();
			}
		} catch (GdprException $e) {
			$this->setError($e);
			return false;
		} catch (Exception $e) {
			$gdprException = new GdprException($e->getMessage(), 'error');
			$this->setError($gdprException);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Class contructor
	 *
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	
		// App reference
		$this->app = JFactory::getApplication();
		$this->requestArray = &$GLOBALS;
		$this->requestName = '_' . strtoupper('post');
	}
}