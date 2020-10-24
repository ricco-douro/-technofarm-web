<?php
// namespace administrator\components\com_gdpr\views\users;
/**
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage users
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.utilities.date' );

/**
 * Users view implementation
 *
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage views
 * @since 1.6
 */
class GdprViewUsers extends GdprView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title ( JText::_ ( 'COM_GDPR_MAINTITLE_TOOLBAR' ) . JText::_ ( 'COM_GDPR_LIST_USERS' ), 'gdpr' );
		
		$user = JFactory::getUser();
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::custom('users.notifyDataBreach', 'envelope', 'envelope', 'COM_GDPR_NOTIFY_DATA_BREACH_BTN', true);
		}
		
		if ($user->authorise('core.edit.state', 'com_gdpr')) {
			JToolBarHelper::custom('users.violatedEntity', 'user', 'user', 'COM_GDPR_MARK_AS_VIOLATED_PROFILE_BTN', true);
			JToolBarHelper::custom('users.unviolatedEntity', 'user', 'user', 'COM_GDPR_UNMARK_AS_VIOLATED_PROFILE_BTN', true);
		}
		
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::custom('users.exportCsvProfiles', 'download', 'download', 'COM_GDPR_EXPORT_USERS_CSV', false);
			JToolBarHelper::custom('users.exportXlsProfiles', 'download', 'download', 'COM_GDPR_EXPORT_USERS_XLS', false);
		}
		
		JToolBarHelper::custom ( 'cpanel.display', 'home', 'home', 'COM_GDPR_CPANEL', false );
	}
	
	/**
	 * Default listEntities
	 *
	 * @access public
	 */
	public function display($tpl = 'list') {
		$doc = JFactory::getDocument ();
		$this->loadJQuery ( $doc );
		$this->loadJQueryUI ( $doc ); // Required for draggable feature
		$this->loadBootstrap ( $doc );

		// Get main records
		$rows = $this->get ( 'Data' );
		$lists = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		$dates = array('start'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod'));
		
		// Inject js translations
		$translations = array(
				'COM_GDPR_ERROR_RECORDS_EMPTY_JSMESSAGE',
				'COM_GDPR_SURE_TO_SEND_EMAIL'
		);
		$this->injectJsTranslations($translations, $doc);
		
		$doc->addScriptDeclaration("
				Joomla.submitbutton = function(pressbutton) {
					Joomla.submitform( pressbutton );
					if (pressbutton == 'users.exportCsvProfiles' ||
						pressbutton == 'users.exportXlsProfiles') {
						jQuery('#adminForm input[name=task]').val('users.display');
					}
					return true;
				}
			");
		
		$this->pagination = $pagination;
		$this->order = $this->getModel ()->getState ( 'order' );
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->lists = $lists;
		$this->orders = $orders;
		$this->dates = $dates;
		$this->items = $rows;
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->nullDate = $this->getModel()->getDbo()->getNullDate();
		
		// Add toolbar
		$this->addDisplayToolbar ();
		
		parent::display ( $tpl );
	}
	
	/**
	 * Effettua l'output view del file in attachment al browser
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function sendCSVUsers($data) {
		$componentConfig = $this->getModel()->getComponentParams();
		$delimiter = $componentConfig->get('csv_delimiter', ';');
		$enclosure = $componentConfig->get('csv_enclosure', '"');
		$nullDate = $this->getModel()->getDbo()->getNullDate();
		
		// Clean dirty buffer
		ob_end_clean();
		// Open buffer
		ob_start();
		// Open out stream
		$outstream = fopen("php://output", "w");
		// Funzione di scrittura nell'output stream
		function __outputCSV(&$vals, $key, $userData) {
			$vals = array_map(function ($v, $k) use ($userData) {
					switch($v){
						case '1':
							$v = JText::_('JYES');
							break;
						case '0':
						case null:
							$v = JText::_('JNO');
							break;
					}
					
					if($k === 'registerDate' || $k === 'lastvisitDate') {
						$v = $v == $userData[4] ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $v, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
					}
				return $v;
			}, $vals, array_keys($vals));
					
			fputcsv($userData[0], $vals, $userData[1], $userData[2]); // add parameters if you want
		}
	
		// Echo delle intestazioni
		$headerFields = array(
				JText::_('COM_GDPR_LOGS_NAME'),
				JText::_('COM_GDPR_LOGS_USERNAME'),
				JText::_('COM_GDPR_LOGS_EMAIL'),
				JText::_('COM_GDPR_LOGS_REGISTERDATE'),
				JText::_('COM_GDPR_LOGS_LASTVISITDATE'),
				JText::_('COM_GDPR_VIOLATEDUSER')
		);
		__outputCSV($headerFields, null, array($outstream, $delimiter, $enclosure, true, $nullDate));
	
		// Output di tutti i records
		array_walk($data, "__outputCSV", array($outstream, $delimiter, $enclosure, false, $nullDate));
		fclose($outstream);
		// Recupero output buffer content
		$contents = ob_get_clean();
		$size = strlen($contents);
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
	
		// Recupero output buffer content
		$exportedFileName = 'data_breach_users_' . $dataExport . '.csv';
	
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="' . $exportedFileName . '"' );
		header ( 'Content-Type: text/plain' );
		header ( "Content-Length: " . $size );
		echo $contents;
			
		exit ();
	}
	
	/**
	 * Effettua l'output view del file in attachment al browser
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function sendXLSUsers($data) {
		// Get main records
		$rows = $this->get ( 'Data' );
		
		$this->user = JFactory::getUser ();
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->state = $this->getModel ()->getState ( 'state' );
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->items = $rows;
		$this->nullDate = $this->getModel()->getDbo()->getNullDate();
		
		//Creazione buffer output
		ob_start ();
		// Parent construction and view display
		parent::display ( 'xls' );
		$bufferContent = ob_get_contents ();
		ob_end_clean ();
		
		// Set file date
		$dataExport = date ( 'Y-m-d H:i:s', time () );
		
		// Recupero output buffer content
		$exportedFileName = 'data_breach_users_' . $dataExport . '.xls';
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="' . $exportedFileName . '"' );
		header ( 'Content-Type: application/vnd.ms-excel' );
		
		echo $bufferContent;
		
		exit ();
	}
	
	/**
	 * Class constructor
	 *
	 * @param array $config        	
	 */
	public function __construct($config = array()) {
		// Parent view object
		parent::__construct ( $config );
	}
}