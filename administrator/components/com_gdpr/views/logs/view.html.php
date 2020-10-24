<?php
// namespace administrator\components\com_gdpr\views\logs;
/**
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage logs
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage logs
 * * @since 1.0
 */
class GdprViewLogs extends GdprView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-gdpr{background-image:url("components/com_gdpr/images/icon-48-links.png")}');
	
		$user = JFactory::getUser();
		JToolBarHelper::title( JText::_('COM_GDPR_LOGS_TITLE' ), 'gdpr' );
	
		if ($user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::editList('logs.showentity', 'COM_GDPR_VIEW_LOG_DETAILS');
			JToolBarHelper::custom('logs.exportCsvLogs', 'download', 'download', 'COM_GDPR_EXPORT_LOGS_CSV', false);
			JToolBarHelper::custom('logs.exportXlsLogs', 'download', 'download', 'COM_GDPR_EXPORT_LOGS_XLS', false);
		}
	
		if ($user->authorise('core.delete', 'com_gdpr') && $user->authorise('core.edit', 'com_gdpr')) {
			JToolBarHelper::deleteList('COM_GDPR_DELETE_ENTITY', 'logs.deleteEntity');
		}
			
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_GDPR_CPANEL', false);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addShowEntityToolbar() {
		$doc = JFactory::getDocument();
		JToolBarHelper::title( JText::_('COM_GDPR_LOG_DETAILS_TITLE' ), 'gdpr' );
		JToolBarHelper::custom('logs.display', 'arrow-left-2', 'arrow-left-2', 'COM_GDPR_BACK_TO_LIST_LOGS', false);
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
		$this->loadJQueryUI($doc); // Required for draggable feature
		$this->loadBootstrap($doc);
		$doc->addScriptDeclaration("
				Joomla.submitbutton = function(pressbutton) {
					Joomla.submitform( pressbutton );
					if (pressbutton == 'logs.exportCsvLogs' ||
						pressbutton == 'logs.exportXlsLogs') {
						jQuery('#adminForm input[name=task]').val('logs.display');
					}
					return true;
				}
			");

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
		$this->logUserIpaddress = $this->getModel()->getComponentParams()->get('log_user_ipaddress', 0);
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
			
		parent::display ( 'list' );
	}
	
	/**
	 * Mostra la visualizzazione dettaglio del record singolo
	 * @param Object& $row
	 * @access public
	 */
	public function showEntity($row) {
		// Sanitize HTML Object2Form
		JFilterOutput::objectHTMLSafe( $row );
	
		// Add toolbar
		$this->addShowEntityToolbar();
	
		$doc = JFactory::getDocument ();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
	
		$this->option = $this->getModel()->getState('option');
		$this->record = $row;
		$this->revokablePrivacyPolicy = $this->getModel()->getComponentParams()->get('revokable_privacypolicy', 0);
		$this->logUserIpaddress = $this->getModel()->getComponentParams()->get('log_user_ipaddress', 0);

		parent::display('show');
	}
	
	/**
	 * Effettua l'output view del file in attachment al browser
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function sendCSVLogs($data) {
		$componentConfig = $this->getModel()->getComponentParams();
		$delimiter = $componentConfig->get('csv_delimiter', ';');
		$enclosure = $componentConfig->get('csv_enclosure', '"');
		$paramsDelimiter = $delimiter == ';' ? "," : ';';
		$paramsEnclosure = $enclosure == '"' ? "'" : '"';
		// Clean dirty buffer
		ob_end_clean();
		// Open buffer
		ob_start();
		// Open out stream
		$outstream = fopen("php://output", "w");
		// Funzione di scrittura nell'output stream
		function __outputCSV(&$vals, $key, $userData) {
			// Revokable privacy policy strip off if not headers
			if(!$userData[3] && $userData[4] === 0) {
				unset($vals['privacy_policy']);
			}
			$paramsEnclosure = $userData[5];
			$paramsDelimiter = $userData[6];
			
			$vals = array_map(function ($v, $k) use ($paramsEnclosure, $paramsDelimiter) {
				if($k === 'changes_structure') {
					$decodedParams = json_decode($v, true);
					$v = implode(' | ', array_map(
						function ($iv, $ik) use ($paramsEnclosure, $paramsDelimiter) {
								if($ik == 'change_params') {
									$cellOldValues = implode($paramsDelimiter . ' ', array_map(
											function ($iiv, $iik) use ($paramsEnclosure, $paramsDelimiter) { 
													$cycledFieldNameTranslation = JText::_('COM_GDPR_LOGS_' . strtoupper($iik) . '_PROFILE');
													if(strpos($cycledFieldNameTranslation, 'COM_GDPR_') !== false) {
														$cycledFieldNameTranslation = $iik;
													}
													if(is_array($iiv)) {
														$iiv = implode (', ', $iiv);
													}
													if($iiv == '1') {
														$iiv = JText::_('JYES');
													}
													if($iiv == '0') {
														$iiv = JText::_('JNO');
													}
													return sprintf("%s=$paramsEnclosure%s$paramsEnclosure", $cycledFieldNameTranslation, $iiv); 
												},
												$iv['oldvalue'],
												array_keys($iv['oldvalue'])
											));
									$cellNewValues = implode($paramsDelimiter . ' ', array_map(
										function ($iiv, $iik) use ($paramsEnclosure, $paramsDelimiter) { 
												$cycledFieldNameTranslation = JText::_('COM_GDPR_LOGS_' . strtoupper($iik) . '_PROFILE');
												if(strpos($cycledFieldNameTranslation, 'COM_GDPR_') !== false) {
													$cycledFieldNameTranslation = $iik;
												}
												if(is_array($iiv)) {
													$iiv = implode (', ', $iiv);
												}
												if($iiv == '1') {
													$iiv = JText::_('JYES');
												}
												if($iiv == '0') {
													$iiv = JText::_('JNO');
												}
												return sprintf("%s=$paramsEnclosure%s$paramsEnclosure", $cycledFieldNameTranslation, $iiv); 
											},
											$iv['newvalue'],
											array_keys($iv['newvalue'])
									));
								} elseif($ik == 'change_usergroups') {
									$cellOldValues = implode($paramsDelimiter, $iv['oldvalue']);
									$cellNewValues = implode($paramsDelimiter, $iv['newvalue']);
								} elseif($ik == 'change_block') {
									$cellOldValues = $iv['oldvalue'] == '1' ? JText::_('COM_GDPR_LOGS_BLOCKED_USER') : JText::_('COM_GDPR_LOGS_ENABLED_USER');
									$cellNewValues = $iv['newvalue'] == '1' ? JText::_('COM_GDPR_LOGS_BLOCKED_USER') : JText::_('COM_GDPR_LOGS_ENABLED_USER');
								} elseif($ik == 'change_activation') {
									$cellOldValues = $iv['oldvalue'];
									$cellNewValues = $iv['newvalue'] ? $iv['newvalue'] : "''";
								} else {
									switch ($iv['oldvalue']) {
										case '1':
											$cellOldValues = JText::_('JYES');
											break;
											
										case '0':
											$cellOldValues = JText::_('JNO');
											break;
											
										default:
											$cellOldValues = $iv['oldvalue'];
									}
									switch ($iv['newvalue']) {
										case '1':
											$cellNewValues = JText::_('JYES');
											break;
												
										case '0':
											$cellNewValues = JText::_('JNO');
											break;
												
										default:
											$cellNewValues = $iv['newvalue'];
									}
								}
								if($ik == 'change_params') {
									return sprintf("%s: (%s) => (%s)", JText::_('COM_GDPR_LOGS_' . strtoupper($ik)), $cellOldValues, $cellNewValues); 
								} else {
									return sprintf("%s: %s=>%s", JText::_('COM_GDPR_LOGS_' . strtoupper($ik)), $cellOldValues, $cellNewValues); 
								}
							},
							$decodedParams['changes'],
							array_keys($decodedParams['changes'])
					));
				} else {
					switch($v){
						case '1':
							$v = JText::_('JYES');
							break;
						case '0':
							$v = JText::_('JNO');
							break;
					}
					if($k === 'change_date') {
						$v = JHtml::_('date', $v, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
					}
				}
				return $v; 
			}, $vals, array_keys($vals));
			
			fputcsv($userData[0], $vals, $userData[1], $userData[2]); // add parameters if you want
		}
		
		// Echo delle intestazioni
		$headers = array( JText::_('COM_GDPR_NUM'),
						  JText::_('COM_GDPR_LOGS_USER_ID'),
						  JText::_('COM_GDPR_LOGS_NAME'),
						  JText::_('COM_GDPR_LOGS_USERNAME'),
						  JText::_('COM_GDPR_LOGS_EMAIL'),
						  JText::_('COM_GDPR_LOGS_CHANGE_NAME'),
						  JText::_('COM_GDPR_LOGS_CHANGE_USERNAME'),
						  JText::_('COM_GDPR_LOGS_CHANGE_PASSWORD'),
						  JText::_('COM_GDPR_LOGS_CHANGE_EMAIL'),
						  JText::_('COM_GDPR_LOGS_CHANGE_PARAMS'),
						  JText::_('COM_GDPR_LOGS_CHANGE_REQUIRERESET'),
						  JText::_('COM_GDPR_LOGS_CHANGE_BLOCK'),
						  JText::_('COM_GDPR_LOGS_CHANGE_SENDEMAIL'),
						  JText::_('COM_GDPR_LOGS_CHANGE_USERGROUPS'),
						  JText::_('COM_GDPR_LOGS_CHANGE_ACTIVATION'),
						  JText::_('COM_GDPR_LOGS_CREATED_USER'),
						  JText::_('COM_GDPR_LOGS_DELETED_USER'),
						  JText::_('COM_GDPR_LOGS_EDITOR_USER_ID'),
						  JText::_('COM_GDPR_LOGS_EDITOR_NAME'),
						  JText::_('COM_GDPR_LOGS_EDITOR_USERNAME'),
						  JText::_('COM_GDPR_LOGS_CHANGE_DATE'),
						  JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW')
		);
		$revokablePrivacyPolicy = (int)$componentConfig->get('revokable_privacypolicy', 0);
		if($revokablePrivacyPolicy) {
			array_splice($headers, 17, 0, JText::_('COM_GDPR_LOGS_PRIVACY_POLICY'));
		}
		
		$logUserIpaddress = (int)$componentConfig->get('log_user_ipaddress', 0);
		if($logUserIpaddress) {
			array_splice($headers, 5, 0, JText::_('COM_GDPR_LOGS_IPADDRESS'));
		}
		
		__outputCSV($headers, null, array($outstream, $delimiter, $enclosure, true, null, $paramsEnclosure, $paramsDelimiter));
		
		// Output di tutti i records
		array_walk($data, "__outputCSV", array($outstream, $delimiter, $enclosure, false, $revokablePrivacyPolicy, $paramsEnclosure, $paramsDelimiter));
		fclose($outstream);
		// Recupero output buffer content
		$contents = ob_get_clean();
		$size = strlen($contents);
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
		
		// Recupero output buffer content
		$exportedFileName = 'logs_user_changes_' . $dataExport . '.csv';
		
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
	public function sendXLSLogs() {
		// Get main records
		$rows = $this->get ( 'Data' );
		$lists = $this->get ( 'Filters' );
		
		$dates = array('start'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod'));
		
		$this->user = JFactory::getUser ();
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->search_editorword = $this->getModel ()->getState ( 'search_editorword' );
		$this->state = $this->getModel ()->getState ( 'state' );
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->dates = $dates;
		$this->items = $rows;
		$this->lists = $lists;
		$this->revokablePrivacyPolicy = $this->getModel()->getComponentParams()->get('revokable_privacypolicy', 0);
		$this->logUserIpaddress = $this->getModel()->getComponentParams()->get('log_user_ipaddress', 0);
		
		//Creazione buffer output
		ob_start ();
		// Parent construction and view display
		parent::display ( 'xls' );
		$bufferContent = ob_get_contents ();
		ob_end_clean ();
		
		// Set file date
		$dataExport = date ( 'Y-m-d H:i:s', time () );
		
		// Recupero output buffer content
		$exportedFileName = 'logs_user_changes_' . $dataExport . '.xls';
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="' . $exportedFileName . '"' );
		header ( 'Content-Type: application/vnd.ms-excel' );
		
		echo $bufferContent;
		
		exit ();
	}
}