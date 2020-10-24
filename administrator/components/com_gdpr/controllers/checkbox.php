<?php
// namespace administrator\components\com_gdpr\controllers;
/**
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Controller for links entity tasks
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage controllers
 * @since 1.4
 */
class GdprControllerCheckbox extends GdprController {
	/**
	 * Set model state from session userstate
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default', $ordering = true) {
		$option = $this->option;
		
		$defaultModel = parent::setModelState ( $scope, false );
		
		$filter_order = $this->getUserStateFromRequest ( "$option.$scope.filter_order", 'filter_order', 's.id', 'cmd' );
		$filter_order_Dir = $this->getUserStateFromRequest ( "$option.$scope.filter_order_Dir", 'filter_order_Dir', 'desc', 'word' );
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
		$defaultModel = $this->setModelState('checkbox');
		
		if($defaultModel->getComponentparams()->get('disable_dynamic_checkbox', 0)) {
			$this->app->enqueueMessage(JText::_('COM_GDPR_DYNAMIC_CHECKBOX_DISABLED_WARN'), 'warning');
		}
		
		// Parent construction and view display
		parent::display($cachable);
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
		$this->registerTask ( 'applyEntity', 'saveEntity' );
		$this->registerTask ( 'unpublish', 'publishEntities' );
		$this->registerTask ( 'publish', 'publishEntities' );
	}
}