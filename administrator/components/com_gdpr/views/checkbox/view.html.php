<?php
// namespace administrator\components\com_gdpr\views\checkbox;
/**
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage checkbox
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage checkbox
 * @since 1.4
 */
class GdprViewCheckbox extends GdprView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-gdpr{background-image:url("components/com_gdpr/images/icon-48-links.png")}');
	
		$user = JFactory::getUser();
		JToolBarHelper::title( JText::_('COM_GDPR_CHECKBOX_TITLE' ), 'gdpr' );
	
		// Access check.
		if ($user->authorise('core.create', 'com_gdpr')) {
			JToolBarHelper::addNew('checkbox.editEntity', 'COM_GDPR_NEW_CHECKBOX');
		}
		
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::editList('checkbox.editEntity', 'COM_GDPR_EDIT_CHECKBOX');
		}
	
		if ($user->authorise('core.delete', 'com_gdpr') && $user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::deleteList('COM_GDPR_DELETE_ENTITY', 'checkbox.deleteEntity');
		}
			
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_GDPR_CPANEL', false);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addEditEntityToolbar() {
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->record->id == 0);
		$checkedOut	= !($this->record->checked_out == 0 || $this->record->checked_out == $userId);
		$toolbarHelperTitle = $isNew ? 'COM_GDPR_CHECKBOX_NEW' : 'COM_GDPR_CHECKBOX_EDIT';
	
		$doc = JFactory::getDocument();
		JToolBarHelper::title( JText::_( $toolbarHelperTitle ), 'gdpr' );
	
		if ($isNew)  {
			// For new records, check the create permission.
			if ($isNew && ($user->authorise('core.create', 'com_gdpr'))) {
				JToolBarHelper::apply( 'checkbox.applyEntity', 'JAPPLY');
				JToolBarHelper::save( 'checkbox.saveEntity', 'JSAVE');
			}
		} else {
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($user->authorise('core.edit', 'com_gdpr')) {
					JToolBarHelper::apply( 'checkbox.applyEntity', 'JAPPLY');
					JToolBarHelper::save( 'checkbox.saveEntity', 'JSAVE');
				}
			}
		}
			
		JToolBarHelper::custom('checkbox.cancelEntity', 'cancel', 'cancel', 'JCANCEL', false);
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		// Get main records
		$rows = $this->get ( 'Data' );
		$total = $this->get ( 'Total' );
		$lists = $this->get ( 'Filters' );
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);

		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		$dates = array('start'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod'));
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->search_editorword = $this->getModel ()->getState ( 'search_editorword' );
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->orders = $orders;
		$this->dates = $dates;
		$this->lists = $lists;
		$this->items = $rows;
		$this->revokablePrivacyPolicy = $this->getModel()->getComponentParams()->get('revokable_privacypolicy', 0);
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
			
		parent::display ( 'list' );
	}
	
	/**
	 * Mostra la visualizzazione dettaglio del record singolo
	 * @param Object& $row
	 * @access public
	 */
	public function editEntity($row) {
		// Sanitize HTML Object2Form
		JFilterOutput::objectHTMLSafe( $row );
	
		$doc = JFactory::getDocument ();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$this->loadValidation($doc);

		$doc->addScriptDeclaration("
					Joomla.submitbutton = function(pressbutton) {
						if(!jQuery.fn.validation) {
							jQuery.extend(jQuery.fn, gdprjQueryBackup.fn);
						}
		
						jQuery('#adminForm').validation();
		
						if (pressbutton == 'checkbox.cancelEntity') {
							jQuery('#adminForm').off();
							Joomla.submitform( pressbutton );
							return true;
						}
		
						if(jQuery('#adminForm').validate()) {
							Joomla.submitform( pressbutton );
							return true;
						}
						return false;
					};
				");
		
		$lists = $this->getModel()->getLists($row);
		$this->lists = $lists;
		$this->option = $this->getModel()->getState('option');
		$this->record = $row;
	
		// Add toolbar
		$this->addEditEntityToolbar();
		
		parent::display('edit');
	}
}