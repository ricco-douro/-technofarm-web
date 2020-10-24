<?php
// namespace administrator\components\com_gdpr\views\cpanel;
/**
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage config
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * Config view
 *
 * @package GDPR::CONFIG::administrator::components::com_gdpr
 * @subpackage views
 * @since 1.6
 */
class GdprViewConfig extends GdprView {

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		JToolBarHelper::title( JText::_('COM_GDPR_MAINTITLE_TOOLBAR') . JText::_('COM_GDPR_CONFIG' ), 'gdpr' );
		
		$user = JFactory::getUser();
		if ($user->authorise('core.manage.save', 'com_gdpr')) {
			JToolBarHelper::save('config.saveentity', 'COM_GDPR_SAVECONFIG');
		}
		
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::custom('config.exportConfig', 'download', 'download', 'COM_GDPR_EXPORT_CONFIG', false);
			JToolBarHelper::custom('config.importConfig', 'upload', 'upload', 'COM_GDPR_IMPORT_CONFIG', false);
		}
		
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_GDPR_CPANEL', false);
	}
	
	/**
	 * Effettua il rendering dei tabs di configurazione del componente
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$this->loadValidation($doc);
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/fileconfig.js' );
		
		// Load specific JS App
		$doc->addScriptDeclaration("
				Joomla.submitbutton = function(pressbutton) {
					if(!jQuery.fn.validation) {
						jQuery.extend(jQuery.fn, gdprjQueryBackup.fn);
					}
			
					jQuery('#adminForm').validation();
	
					if (pressbutton == 'cpanel.display') {
						jQuery('#adminForm').off();
						Joomla.submitform( pressbutton );
						return true;
					}
	
					if(jQuery('#adminForm').validate()) {
						Joomla.submitform( pressbutton );
				
						if (pressbutton == 'config.exportConfig') {
							jQuery('#adminForm input[name=task]').val('config.display');
						}
				
						return true;
					}
					var parentId = jQuery('ul.errorlist').parents('div.tab-pane').attr('id');
					jQuery('#tab_configuration a[data-element=' + parentId + ']').tab('show');
					return false;
				};
			");
		
		// Inject js translations
		$translations = array(
				'COM_GDPR_REQUIRED',
				'COM_GDPR_PICKFILE',
				'COM_GDPR_STARTIMPORT',
				'COM_GDPR_CANCELIMPORT',
				'COM_GDPR_OPEN_COOKIE_TOOLBAR',
				'COM_GDPR_CUSTOM_COPY_CODE',
				'COM_GDPR_CUSTOM_COPIED_CODE',
				'COM_GDPR_RESET_ALL_CONSENTS',
				'COM_GDPR_RESET_ALL_CONSENTS_TITLE',
				'COM_GDPR_RESET_ALL_CONSENTS_DESC'
		);
		$this->injectJsTranslations($translations, $doc);
		
		$params = $this->get('Data');
		$form = $this->get('form');
		
		// Bind the form to the data.
		if ($form && $params) {
			$form->bind($params);
		}
		
		$this->params_form = $form;
		$this->params = $params;
		$this->fieldset = $this->getModel()->getState('fieldset');
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display();
	}
}
?>