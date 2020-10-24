<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 *
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Cookie concrete implementation
 *
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage controllers
 * @since 1.6
 */
class GdprControllerCookie extends GdprController { 
	/**
	 * Setta il model state a partire dallo userstate di sessione
	 * @access protected
	 * @param string $scope
	 * @param boolean $ordering
	 * @return void
	 */
	protected function setModelState($scope = 'default', $ordering = true) {
		$option = $this->option;
		
		// Get request state
		$fromPeriod = $this->getUserStateFromRequest( "$option.$scope.fromperiod", 'fromperiod');
		$toPeriod = $this->getUserStateFromRequest( "$option.$scope.toperiod", 'toperiod');
		
		$cookie_consent_type = $this->getUserStateFromRequest( "$option.$scope.cookie_consent_type", 'cookie_consent_type', '');
		$cookie_consent_user = $this->getUserStateFromRequest( "$option.$scope.cookie_consent_user", 'cookie_consent_user', '');
		$filter_order = $this->getUserStateFromRequest("$option.$scope.filter_order", 'filter_order', 'a.consent_date', 'cmd');
		$filter_order_Dir = $this->getUserStateFromRequest("$option.$scope.filter_order_Dir", 'filter_order_Dir', 'desc', 'word');
		
		$defaultModel = parent::setModelState($scope);
		
		// Set model state
		$defaultModel->setState('fromPeriod', $fromPeriod);
		$defaultModel->setState('toPeriod', $toPeriod);
		$defaultModel->setState('cookie_consent_type', $cookie_consent_type);
		$defaultModel->setState('cookie_consent_user', $cookie_consent_user);
		$defaultModel->setState('order', $filter_order);
		$defaultModel->setState('order_dir', $filter_order_Dir);
		
		return $defaultModel;
	}
	
	/**
	 * Default listEntities
	 * 
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		// Set model state 
		$this->setModelState('cookie');
		
		// Parent construction and view display
		parent::display();
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportCsvRegistryLogs() {
		$defaultModel = $this->setModelState('cookie');
	
		// Access check
		if (!$this->allowEdit ( 'com_gdpr' )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=cookie.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		$data = $defaultModel->getData('assoc_array');
	
		if(!$data) {
			$this->setRedirect('index.php?option=' . $this->option . '&task=cookie.display', JText::_('COM_GDPR_NODATA_EXPORT'));
			return false;
		}
	
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendCSVGenericRegistry($data);
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportXlsRegistryLogs() {
		$defaultModel = $this->setModelState('cookie');
	
		// Access check
		if (!$this->allowEdit ( 'com_gdpr' )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=cookie.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendXlsGenericRegistry();
	}

	/**
	 * Constructor.
	 *
	 * @access protected
	 * @param
	 *       	 array An optional associative array of configuration settings.
	 *       	 Recognized key values include 'name', 'default_task',
	 *       	 'model_path', and
	 *       	 'view_path' (this list is not meant to be comprehensive).
	 * @since 1.5
	 */
	function __construct($config = array()) {
		parent::__construct($config);
	}
}
