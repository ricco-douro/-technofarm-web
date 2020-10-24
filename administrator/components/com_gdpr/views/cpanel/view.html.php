<?php
// namespace administrator\components\com_gdpr\views\cpanel;
/**
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage cpanel
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * CPanel view
 *
 * @package GDPR::CPANEL::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage cpanel
 * @since 1.6
 */
class GdprViewCpanel extends GdprView {
	/**
	 * Renderizza l'iconset del cpanel
	 *
	 * @param $link string
	 * @param $image string
	 * @access private
	 * @return string
	 */
	private function getIcon($link, $image, $text, $target = '', $title = null, $class = 'icons') {
		$mainframe = JFactory::getApplication ();
		$lang = JFactory::getLanguage ();
		$option = $this->option;
		?>
		<div class="<?php echo $class;?>" style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a <?php echo $title . $class;?> <?php echo $target;?>
					href="<?php echo $link; ?>">
					<div class="task <?php echo $image;?>"></div> <span class="task"><?php echo $text; ?></span>
				</a>
			</div>
		</div>
<?php
		}
		
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		JToolBarHelper::title( JText::_('COM_GDPR_CPANEL_TOOLBAR' ), 'gdpr' );
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_GDPR_CPANEL', false);
	}
	
	/**
	 * Effettua il rendering del pannello di controllo
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument ();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addStylesheet ( JUri::root ( true ) . '/administrator/components/com_gdpr/css/cpanel.css' );
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/chart.js' );
		$doc->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/cpanel.js' );
		
		// Inject js translations
		$translations = array(
				'COM_GDPR_START_CHART',
				'COM_GDPR_NEW_CHART',
				'COM_GDPR_DELETED_CHART',
				'COM_GDPR_BREACHED_CHART',
				'COM_GDPR_END_CHART' 
		);
		$this->injectJsTranslations($translations, $doc);
		$this->componentParams = $this->getModel()->getComponentParams();
		
		// Buffer delle icons
		ob_start ();
		$this->getIcon ( 'index.php?option=com_gdpr&task=logs.display', 'icon-list', JText::_ ( 'COM_GDPR_LOGS' ) );
		$this->getIcon ( 'index.php?option=com_gdpr&task=consents.display', 'icon-database', JText::_ ( 'COM_GDPR_LOGS_CONSENTS' ) );
		$this->getIcon ( 'index.php?option=com_gdpr&task=cookie.display', 'icon-cube', JText::_ ( 'COM_GDPR_LOGS_COOKIE_CONSENTS' ) );
		
		if ($this->user->authorise('core.edit', 'com_gdpr')) {
			switch($this->componentParams->get('consent_registry_format', 'csv')) {
				case 'csv':
					$this->getIcon ( 'index.php?option=com_gdpr&task=cpanel.exportCsvRegistry', 'icon-printer', JText::_ ( 'COM_GDPR_EXPORT_REGISTRY' ) );
					break;
					
				case 'xls':
					$this->getIcon ( 'index.php?option=com_gdpr&task=cpanel.exportXlsRegistry', 'icon-printer', JText::_ ( 'COM_GDPR_EXPORT_REGISTRY' ) );
					break;
			}
		}

		$this->getIcon ( 'index.php?option=com_gdpr&task=checkbox.display', 'icon-checkbox-partial', JText::_ ( 'COM_GDPR_DYNAMIC_CHECKBOX' ) );
		$this->getIcon ( 'index.php?option=com_gdpr&task=users.display', 'icon-users', JText::_ ( 'COM_GDPR_USERS' ) );
		
		// Access check.
		if ($this->user->authorise('core.admin', 'com_gdpr')) {
			$this->getIcon ( 'index.php?option=com_gdpr&task=record.display', 'icon-calendar-2', JText::_ ( 'COM_GDPR_RECORD' ) );
			$this->getIcon ( 'index.php?option=com_gdpr&task=config.display#_cookieconsent', 'icon-checkmark', JText::_ ( 'COM_GDPR_CONFIGURATION_COOKIECONSENT' ) );
			$this->getIcon ( 'index.php?option=com_gdpr&task=config.display#_userprofile', ' icon-pencil', JText::_ ( 'COM_GDPR_USERPROFILE' ) );
			$this->getIcon ( 'index.php?option=com_gdpr&task=config.display#_privacycheckbox', '  icon-checkbox', JText::_ ( 'COM_GDPR_CHECKBOX' ) );
			$this->getIcon ( 'index.php?option=com_gdpr&task=config.display#_permissions', 'icon-lock', JText::_ ( 'COM_GDPR_PERMISSIONS' ) );
			$this->getIcon ( 'index.php?option=com_gdpr&task=config.display', 'icon-cog', JText::_ ( 'COM_GDPR_CONFIG' ) );
		}
		$this->getIcon ( 'http://storejextensions.org/gdpr_documentation.html', 'icon-help', JText::_ ( 'COM_GDPR_HELP' ) );
		
		$contents = ob_get_clean ();
		
		$infoData = $this->getModel()->getData();
		$doc->addScriptDeclaration('var gdprChartData = ' . json_encode($infoData));
		
		// Assign reference variables
		$this->icons = $contents;
		$this->updatesData = $this->getModel()->getUpdates($this->get('httpclient'));
		$this->infodata = $infoData;
		$this->currentVersion = strval(simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR . '/gdpr.xml')->version);
		
		// Add toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display ();
	}
	
	/**
	 * Effettua l'output view del file in attachment al browser
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function sendCSVRegistry($data) {
		$componentConfig = $this->getModel()->getComponentParams();
		$delimiter = $componentConfig->get('csv_delimiter', ';');
		$enclosure = $componentConfig->get('csv_enclosure', '"');
		$userNotes = $componentConfig->get('log_usernote_privacypolicy', 1);
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
					switch($v) {
						case null:
							$v = JText::_('COM_GDPR_LOGS_NA');
							break;
						case '1':
							$v = JText::_('COM_GDPR_LOGS_ACCEPTED');
							break;
						case '0':
						case null:
							$v = JText::_('COM_GDPR_LOGS_NOT_ACCEPTED');
							break;
					}
					
					if($k === 'registerDate') {
						$v = $v == $userData[4] ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $v, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME'));
					}
					if($k === 'body' && !$userData[5]) {
						$v = JText::_('COM_GDPR_LOGS_NA');
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
				JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_STATUS'),
				JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_DESCRIPTION')
		);
		__outputCSV($headerFields, null, array($outstream, $delimiter, $enclosure, true, $nullDate, $userNotes));
	
		// Output di tutti i records
		array_walk($data, "__outputCSV", array($outstream, $delimiter, $enclosure, false, $nullDate, $userNotes));
		fclose($outstream);
		// Recupero output buffer content
		$contents = ob_get_clean();
		$size = strlen($contents);
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
	
		// Recupero output buffer content
		$exportedFileName = 'account_consent_registry_' . $dataExport . '.csv';
	
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
	public function sendXLSRegistry() {
		// Get main records
		$rows = $this->getModel()->exportRegistry('objects_array');

		if(!$rows) {
			$this->app->enqueueMessage(JText::_('COM_GDPR_NODATA_EXPORT'), 'notice');
			$this->app->redirect('index.php?option=' . $this->option . '&task=cpanel.display');
			return false;
		}

		$componentConfig = $this->getModel()->getComponentParams();
		$userNotes = $componentConfig->get('log_usernote_privacypolicy', 1);
		$nullDate = $this->getModel()->getDbo()->getNullDate();
		
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->nullDate = $nullDate;
		$this->showUserNotes = $userNotes;
		$this->items = $rows;
	
		//Creazione buffer output
		ob_start ();
		// Parent construction and view display
		parent::display ( 'xls' );
		$bufferContent = ob_get_contents ();
		ob_end_clean ();
	
		// Set file date
		$dataExport = JHtml::_('date', time (), 'Y-m-d_H:i:s');
	
		// Recupero output buffer content
		$exportedFileName = 'account_consent_registry_' . $dataExport . '.xls';
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="' . $exportedFileName . '"' );
		header ( 'Content-Type: application/vnd.ms-excel' );
	
		echo $bufferContent;
	
		exit ();
	}
}
?>