<?php
// namespace administrator\components\com_gdpr\framework\view;
/**
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage view
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.html.pagination' );
 
/**
 * Base view for all display core
 * 
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage view
 * @since 2.0
 */
class GdprView extends JViewLegacy {
	/**
	 * User object for ACL authorise check
	 *
	 * @access protected
	 * @var Object
	 */
	protected $user;
	
	/**
	 * Document object, needed by views to inject
	 * CSS/JS tags into document output
	 *
	 * @access public
	 * @var Object
	 */
	public $document;
	
	/**
	 * Reference to option executed
	 *
	 * @access public
	 * @var string
	 */
	public $option;
	
	/**
	 * Reference to application
	 *
	 * @access public
	 * @var Object
	 */
	public $app;
	
	/**
	 * Inject language constant into JS Domain maintaining same name mapping
	 * 
	 * @access protected
	 * @param $translations Object&
	 * @param $document Object&
	 * @return void
	 */
	protected function injectJsTranslations(&$translations, &$document) {
		$jsInject = null;
 		// Do translations
		foreach ( $translations as $translation ) {
			$jsTranslation = strtoupper ( $translation );
			$translated = JText::_( $jsTranslation, true);
			$jsInject .= <<<JS
				var $translation = '{$translated}'; 
JS;
		}
		$document->addScriptDeclaration($jsInject);
	}
	
	/**
	 * Manage injecting jQuery framework into document with class inheritance support
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadJQuery(&$document) {
		try { JHtml::_('behavior.core'); } catch (Exception $e){} // Compatibility fix ensured for Joomla 3.4+
		
		// jQuery foundation framework and class support
		JHtml::_('bootstrap.framework');
		$document->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/jstorage.min.js' );
	}
	
	/**
	 * Manage injecting Bootstrap framework into document
	 * 
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadBootstrap(&$document) {
		// Main styles for admin interface
		$document->addStylesheet ( JUri::root ( true ) . '/administrator/components/com_gdpr/css/bootstrap-interface.css' );
		
		// Main JS file for admin interface
		$document->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/bootstrap-interface.js' );
	}
	
	/**
	 * Manage injecting valildation plugin into document
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadValidation(&$document) {
		$document->addStylesheet ( JUri::root ( true ) . '/administrator/components/com_gdpr/css/simplevalidation.css' );
		$document->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/jquery.simplevalidation.js' );
	}
	
	/**
	 * Manage injecting jQuery UI framework into document
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadJQueryUI(&$document) {
		$document->addStylesheet ( JUri::root ( true ) . '/administrator/components/com_gdpr/css/jqueryui/jquery-ui.custom.min.css' );
		$document->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/jquery-ui.min.js' );
	}
	
	/**
	 * Class constructor
	 *
	 * @param array $config
	 *        	return Object
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	
		$this->app = JFactory::getApplication ();
		$this->user = JFactory::getUser ();
		$this->document = JFactory::getDocument();
		$this->option = $this->app->input->get ( 'option' );
	}
}