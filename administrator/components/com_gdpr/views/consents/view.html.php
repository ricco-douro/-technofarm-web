<?php
// namespace administrator\components\com_gdpr\views\users;
/**
 *
 * @package GDPR::CONSENTS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage consents
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.utilities.date' );

/**
 * Consents view implementation
 *
 * @package GDPR::CONSENTS::administrator::components::com_gdpr
 * @subpackage views
 * @since 1.6
 */
class GdprViewConsents extends GdprView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since 1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title ( JText::_ ( 'COM_GDPR_MAINTITLE_TOOLBAR' ) . JText::_ ( 'COM_GDPR_LOGS_CONSENTS' ), 'gdpr' );
		
		$user = JFactory::getUser();
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::custom('consents.exportCsvRegistryLogs', 'download', 'download', 'COM_GDPR_EXPORT_LOGS_CONSENTS_CSV', false);
			JToolBarHelper::custom('consents.exportXlsRegistryLogs', 'download', 'download', 'COM_GDPR_EXPORT_LOGS_CONSENTS_XLS', false);
		}
		
		if ($user->authorise('core.delete', 'com_gdpr') && $user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::deleteList('COM_GDPR_DELETE_ENTITY', 'consents.deleteEntity');
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
					if (pressbutton == 'consents.exportCsvRegistryLogs' ||
						pressbutton == 'consents.exportXlsRegistryLogs') {
						jQuery('#adminForm input[name=task]').val('consents.display');
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
		$this->logUserIpaddress = $this->getModel()->getComponentParams()->get('log_user_ipaddress', 0);
		
		// Add toolbar
		$this->addDisplayToolbar ();
		
		parent::display ( $tpl );
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function sendCSVGenericRegistry($data) {
		$componentConfig = $this->getModel()->getComponentParams();
		$delimiter = $componentConfig->get('csv_delimiter', ';');
		$enclosure = $componentConfig->get('csv_enclosure', '"');
	
		// Clean dirty buffer
		ob_end_clean();
		// Open buffer
		ob_start();
		// Open out stream
		$outstream = fopen("php://output", "w");
		// Funzione di scrittura nell'output stream
		function __outputCSV(&$vals, $key, $userData) {
			$vals = array_map(function ($v, $k) use ($userData) {
				$paramsDelimiter = $userData[1];
				$paramsEnclosure = $userData[2];
				
				switch($v) {
					case null:
					case '0':
						$v = JText::_('COM_GDPR_LOGS_NA');
						break;
				}
					
				if($k === 'consent_date') {
					$v = JHtml::_('date', $v, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
				}
				
				if($v === '*') {
					$v = JText::_('COM_GDPR_CONSENTS_REGISTRY_URL_ALL_PAGES');
				}
				
				if($k === 'formfields') {
					$decodedParams = json_decode($v, true);
					if(is_array($decodedParams)) {
						$v = implode(' | ', array_map(
							function ($iv, $ik) use ($paramsEnclosure, $paramsDelimiter) {
								return sprintf("%s => %s", $ik, $iv);
							},
							$decodedParams,
							array_keys($decodedParams)
						));
					}
				}
				
				return $v;
			}, $vals, array_keys($vals));
					
			fputcsv($userData[0], $vals, $userData[1], $userData[2]); // add parameters if you want
		}
	
		// Echo delle intestazioni
		$headerFields = array(
				JText::_('COM_GDPR_CONSENTS_REGISTRY_URL'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMID'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMNAME'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_USERID'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_SESSIONID'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_NAME'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_USERNAME'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_EMAIL'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_CONSENTDATE'),
				JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMFIELDS')
		);
		
		$logUserIpaddress = (int)$componentConfig->get('log_user_ipaddress', 0);
		if($logUserIpaddress) {
			array_splice($headerFields, 5, 0, JText::_('COM_GDPR_CONSENTS_REGISTRY_IPADDRESS'));
		}
		
		__outputCSV($headerFields, null, array($outstream, $delimiter, $enclosure, true));
	
		// Output di tutti i records
		array_walk($data, "__outputCSV", array($outstream, $delimiter, $enclosure, false));
		fclose($outstream);
		// Recupero output buffer content
		$contents = ob_get_clean();
		$size = strlen($contents);
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
	
		// Recupero output buffer content
		$exportedFileName = 'consents_registry_' . $dataExport . '.csv';
	
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
	public function sendXlsGenericRegistry() {
		// Get main records
		$rows = $this->getModel()->getData();
	
		if(!$rows) {
			$this->app->enqueueMessage(JText::_('COM_GDPR_NODATA_EXPORT'), 'notice');
			$this->app->redirect('index.php?option=' . $this->option . '&task=consents.display');
			return false;
		}
	
		$componentConfig = $this->getModel()->getComponentParams();
	
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->items = $rows;
		$this->logUserIpaddress = $this->getModel()->getComponentParams()->get('log_user_ipaddress', 0);
		
		//Creazione buffer output
		ob_start ();
		// Parent construction and view display
		parent::display ( 'xls' );
		$bufferContent = ob_get_contents ();
		ob_end_clean ();
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
	
		// Recupero output buffer content
		$exportedFileName = 'consents_registry_' . $dataExport . '.xls';
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