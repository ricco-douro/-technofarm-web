<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 * @package GDPR::RECORD::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Controller for links entity tasks
 * @package GDPR::RECORD::administrator::components::com_gdpr
 * @subpackage controllers
 * * @since 1.0
 */
class GdprControllerRecord extends GdprController {
	/**
	 * Set model state from session userstate
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default', $ordering = true) {
		$option = $this->option;
		
		$defaultModel = parent::setModelState ( $scope, false );
		
		
		$filter_order = $this->getUserStateFromRequest ( "$option.$scope.filter_order", 'filter_order', 's.ordering', 'cmd' );
		$filter_order_Dir = $this->getUserStateFromRequest ( "$option.$scope.filter_order_Dir", 'filter_order_Dir', 'asc', 'word' );
		$filter_state = $this->getUserStateFromRequest ( "$option.$scope.filterstate", 'filter_state', '' );
		
		// Set model ordering state
		$defaultModel->setState ( 'order', $filter_order );
		$defaultModel->setState ( 'order_dir', $filter_order_Dir );
		$defaultModel->setState ( 'state', $filter_state );
		
		return $defaultModel;
	}
	
	/**
	 * Default listEntities
	 *
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		// Set model state
		$defaultModel = $this->setModelState('record');
		
		// Parent construction and view display
		parent::display($cachable);
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportXlsRecord() {
		// Set model state
		$defaultModel = $this->setModelState('record');
	
		// Access check
		if (!$this->allowEdit ( $defaultModel->getState ( 'option' ) )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=record.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
		
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendXlsRecord($this->app->input->get('task'));
	}
	
	/**
	 *
	 * Class Constructor
	 *
	 * @access public
	 * @param $config
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	
		// Register Extra tasks
		$this->registerTask ( 'exportOdsRecord', 'exportXlsRecord' );
		$this->registerTask ( 'moveorder_up', 'moveOrder' );
		$this->registerTask ( 'moveorder_down', 'moveOrder' );
		$this->registerTask ( 'applyEntity', 'saveEntity' );
		$this->registerTask ( 'unpublish', 'publishEntities' );
		$this->registerTask ( 'publish', 'publishEntities' );
	}
}