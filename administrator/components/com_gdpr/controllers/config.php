<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Config controller concrete implementation
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage controllers
 * @since 1.6
 */
class GdprControllerConfig extends GdprController {

	/**
	 * Show configuration
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		parent::display($cachable);
	}

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function saveEntity() {
		$model = $this->getModel();
		$option = $this->option;
		
		// Access check
		if (!$this->allowConfigSave ( $option )) {
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
		
		if(!$model->storeEntity()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_('COM_GDPR_ERROR_SAVING_PARAMS'));
			return false;
		}
		$this->setRedirect( "index.php?option=$option&task=config.display", JText::_('COM_GDPR_SAVED_PARAMS'));
	}

	/**
	 * Reset all consents for #__user_profiles table to request a new privacy policy update greement
	 * @access public
	 * @return void
	 */
	public function resetConsents() {
		$model = $this->getModel();
		$option = $this->option;
	
		// Access check
		if (!$this->allowConfigSave ( $option )) {
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		if(!$model->resetAllConsents()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_('COM_GDPR_ERROR_RESET_CONSENTS'));
			return false;
		}
		$this->setRedirect( "index.php?option=$option&task=config.display", JText::_('COM_GDPR_SUCCESS_RESET_CONSENTS'));
	}
	
	/**
	 * Export sources as db table entities
	 *
	 * @access public
	 * @return void
	 */
	public function exportConfig() {
		$option = $this->option;
		// Access check
		if (! $this->allowEdit ( $option )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=config.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get the file manager instance with db connector dependency injection
		$filesManager = new GdprHelpersConfig( JFactory::getDbo (), $this->app );
	
		if (! $filesManager->export ()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$filesManagerException = $filesManager->getError ( null, false );
			$this->app->enqueueMessage ( $filesManagerException->getMessage (), $filesManagerException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_ERROR_CONFIG_EXPORT' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_SUCCESS_CONFIG_EXPORT' ) );
	}
	
	/**
	 * Import sources as db table entities
	 *
	 * @access public
	 * @return void
	 */
	public function importConfig() {
		$option = $this->option;
		// Access check
		if (! $this->allowEdit ( $option )) {
			$this->setRedirect ( 'index.php?option=com_gdpr&task=config.display', JText::_ ( 'COM_GDPR_ERROR_ALERT_NOACCESS' ), 'notice' );
			return false;
		}
	
		// Get the file manager instance with db connector dependency injection
		$filesManager = new GdprHelpersConfig ( JFactory::getDbo (), $this->app );
	
		if (! $filesManager->import ()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$filesManagerException = $filesManager->getError ( null, false );
			$this->app->enqueueMessage ( $filesManagerException->getMessage (), $filesManagerException->getErrorLevel () );
			$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_ERROR_CONFIG_IMPORT' ) );
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=config.display", JText::_ ( 'COM_GDPR_SUCCESS_CONFIG_IMPORT' ) );
	}
}