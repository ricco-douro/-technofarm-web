<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Users concrete implementation
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage controllers
 * @since 1.6
 */
class GdprControllerUsers extends GdprController { 
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
		
		$violated_user = $this->getUserStateFromRequest( "$option.$scope.violated_user", 'violated_user', '');
		$filter_order = $this->getUserStateFromRequest("$option.$scope.filter_order", 'filter_order', 'a.id', 'cmd');
		$filter_order_Dir = $this->getUserStateFromRequest("$option.$scope.filter_order_Dir", 'filter_order_Dir', 'desc', 'word');
		
		$defaultModel = parent::setModelState($scope);
		
		// Set model state
		$defaultModel->setState('fromPeriod', $fromPeriod);
		$defaultModel->setState('toPeriod', $toPeriod);
		$defaultModel->setState('violated_user', $violated_user);
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
		$this->setModelState();
		
		// Parent construction and view display
		parent::display();
	}

	/**
	 * Delete a db table entity
	 *
	 * @access public
	 * @return void
	 */
	public function violatedEntity() {
		$cids = $this->app->input->get ( 'cid', array (), 'array' );
		$option = $this->option;
		
		// Load della model e checkin before exit
		$model = $this->getModel ();
		
		$result = $model->violatedEntity ($cids, $this->task);
		
		if (! $result) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError ( null, false );
			$this->app->enqueueMessage ( $modelException->getMessage (), $modelException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=users.display", JText::_ ( 'COM_GDPR_ERROR_VIOLATEDUSER' ) );
			return false;
		}
		
		$this->setRedirect ( "index.php?option=$option&task=users.display", JText::_ ( 'COM_GDPR_SUCCESS_VIOLATEDUSER' ) );
	}
	
	/**
	 * Delete a db table entity
	 *
	 * @access public
	 * @return void
	 */
	public function notifyDataBreach() {
		$cids = $this->app->input->get ( 'cid', array (), 'array' );
		$option = $this->option;
	
		// Load della model e checkin before exit
		$model = $this->getModel ();
		
		// Access check
		if (!$this->allowEdit ( $model->getState ( 'option' ) )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=users.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		$result = $model->notifyDataBreach ($cids);
	
		if (! $result) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError ( null, false );
			$this->app->enqueueMessage ( $modelException->getMessage (), $modelException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=users.display", JText::_ ( 'COM_GDPR_ERROR_DATABREACH' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=users.display", JText::sprintf( 'COM_GDPR_SUCCESS_DATABREACH', implode(',', $result) ) );
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportCsvProfiles() {
		// Set model state
		$defaultModel = $this->setModelState('users');
	
		// Access check
		if (!$this->allowEdit ( $defaultModel->getState ( 'option' ) )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=users.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
		
		$data = $defaultModel->exportUsers();
	
		if(!$data) {
			$this->setRedirect('index.php?option=' . $this->option . '&task=users.display', JText::_('COM_GDPR_NODATA_EXPORT'));
			return false;
		}
	
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		
		if($this->task == 'exportCsvProfiles') {
			$view->sendCSVUsers($data);
		} else {
			$view->sendXLSUsers($data);
		}
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
		
		$this->registerTask('unviolatedEntity', 'violatedEntity');
		$this->registerTask('exportXlsProfiles', 'exportCsvProfiles');
	}
}
