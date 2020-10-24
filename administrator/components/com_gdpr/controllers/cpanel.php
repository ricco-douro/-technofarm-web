<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * CPanel controller
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage controllers
 * @since 1.6
 */
class GdprControllerCpanel extends GdprController {
	/**
	 * Show Control Panel
	 * @access public
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		$view = $this->getView();
		
		// Dependency injection setter on view/model
		$HTTPClient = new GdprHttp();
		$view->set('httpclient', $HTTPClient);
		
		// No operations
		parent::display ($cachable); 
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportCsvRegistry() {
		$defaultModel = $this->getModel();
		
		// Access check
		if (!$this->allowEdit ( 'com_gdpr' )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=cpanel.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		$data = $defaultModel->exportRegistry();
	
		if(!$data) {
			$this->setRedirect('index.php?option=' . $this->option . '&task=cpanel.display', JText::_('COM_GDPR_NODATA_EXPORT'));
			return false;
		}
	
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendCSVRegistry($data);
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportXlsRegistry() {
		$defaultModel = $this->getModel();
		
		// Access check
		if (!$this->allowEdit ( 'com_gdpr' )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=cpanel.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
		
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendXlsRegistry();
	}
	
	/**
	 * Class Constructor
	 *
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
		// Register Extra tasks
		$this->registerTask ( 'purgeFileCache', 'purgeCaches' );
		$this->registerTask ( 'purgeDbCache', 'purgeCaches' );
	}
}
?>