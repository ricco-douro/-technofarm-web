<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Controller for links entity tasks
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage controllers
 * * @since 1.0
 */
class GdprControllerLogs extends GdprController {
	/**
	 * Set model state from session userstate
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default', $ordering = true) {
		$option = $this->option;
		
		$defaultModel = parent::setModelState ( $scope, false );
		
		// Get request state
		$fromPeriod = $this->getUserStateFromRequest( "$option.$scope.fromperiod", 'fromperiod');
		$toPeriod = $this->getUserStateFromRequest( "$option.$scope.toperiod", 'toperiod');
		
		$filter_order = $this->getUserStateFromRequest ( "$option.$scope.filter_order", 'filter_order', 's.id', 'cmd' );
		$filter_order_Dir = $this->getUserStateFromRequest ( "$option.$scope.filter_order_Dir", 'filter_order_Dir', 'desc', 'word' );
		$filter_state = $this->getUserStateFromRequest ( "$option.$scope.filterstate", 'filter_state', '' );
		$searchEditor = $this->getUserStateFromRequest ( "$option.$scope.search_editorword", 'search_editor', null );
		
		// Set model ordering state
		$defaultModel->setState ( 'fromPeriod', $fromPeriod);
		$defaultModel->setState ( 'toPeriod', $toPeriod);
		$defaultModel->setState ( 'order', $filter_order );
		$defaultModel->setState ( 'order_dir', $filter_order_Dir );
		$defaultModel->setState ( 'state', $filter_state );
		$defaultModel->setState ( 'search_editorword', $searchEditor );
		
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
		$defaultModel = $this->setModelState('logs');
		
		// Parent construction and view display
		parent::display($cachable);
	}
	
	/**
	 * Edit entity
	 *
	 * @access public
	 * @return void
	 */
	public function showEntity() {
		$this->app->input->set ( 'hidemainmenu', 1 );
		$cid = $this->app->input->get ( 'cid', array (
				0
		), 'array' );
		$idEntity = ( int ) $cid [0];
		$model = $this->getModel ();
		$model->setState ( 'option', $this->option );
	
		// Try to load record from model
		if (! $record = $model->loadEntity ( $idEntity )) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelExceptions = $model->getErrors ();
			foreach ( $modelExceptions as $exception ) {
				$this->app->enqueueMessage ( $exception->getMessage (), $exception->getErrorLevel () );
			}
			$this->setRedirect ( "index.php?option=" . $this->option . "&task=" . $this->corename . ".display", JText::_ ( 'COM_GDPR_ERROR_EDITING' ) );
			return false;
		}
	
		// Access check
		if ($record->id && ! $this->allowEdit ( $this->option )) {
			$this->setRedirect ( "index.php?option=" . $this->option . "&task=" . $this->corename . ".display", JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get view and pushing model
		$view = $this->getView ();
		$view->setModel ( $model, true );
	
		// Call edit view
		$view->showEntity ( $record );
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportCsvLogs() {
		// Set model state
		$defaultModel = $this->setModelState('logs');
		
		// Access check
		if (!$this->allowEdit ( $defaultModel->getState ( 'option' ) )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=logs.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		$data = $defaultModel->exportLogs();
	
		if(!$data) {
			$this->setRedirect('index.php?option=' . $this->option . '&task=logs.display', JText::_('COM_GDPR_NODATA_EXPORT'));
			return false;
		}
	
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendCSVLogs($data);
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportXlsLogs() {
		// Set model state
		$defaultModel = $this->setModelState('logs');
	
		// Access check
		if (!$this->allowEdit ( $defaultModel->getState ( 'option' ) )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=logs.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
		
		// Get view
		$view = $this->getView();
		$view->setModel($defaultModel, true);
		$view->sendXlsLogs();
	}
}