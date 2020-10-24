<?php
/** 
 * App runner
 * @package JCHAT::plugins::system
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.plugin.plugin' );

class plgSystemJChat extends JPlugin {	
	/**
	 * JS App Inject
	 *
	 * @access	private
	 * @param Object $cParams
	 * @return void
	 */
	private function injectApp ($cParams, $app) {
		// Ottenimento document
		$doc = JFactory::getDocument ();
		// Output JS APP nel Document
		if($doc->getType() !== 'html' || $app->input->getCmd ( 'tmpl' ) === 'component') {
			return false;
		}
		
		$user = JFactory::getUser();
		if(!$user->id && !$cParams->get('guestenabled', false)) {
			return;
		}
		
		// Check access levels intersection to ensure that users has access usage permission for chat
		// Get users access levels based on user groups belonging
		$userAccessLevels = $user->getAuthorisedViewLevels();
		
		// Get chat access level from configuration, if set AKA param != array(0) go on with intersection
		$chatAccessLevels = $cParams->get('chat_accesslevels', array(0));
		if(is_array($chatAccessLevels) && !in_array(0, $chatAccessLevels, false)) {
			$intersectResult = array_intersect($userAccessLevels, $chatAccessLevels);
			$hasChatAccess = (bool)(count($intersectResult));
			// Return if user has no access
			if(!$hasChatAccess) {
				return;
			}
		}
		
		// Check for menu exclusion
		$menu = $app->getMenu()->getActive();
		if(is_object($menu)) {
			$menuItemid = $menu->id;
			$menuExcluded = $cParams->get('chat_exclusions');
			if(is_array($menuExcluded) && !in_array(0, $menuExcluded, false) && in_array($menuItemid, $menuExcluded)) {
				return;
			}
		}
		
		// Check for IP multiple ranges exclusions
		if($cParams->get ( 'ipbanning', false)) {
			$ipAddressRegex = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/i';
			$clientIP = $_SERVER ['REMOTE_ADDR'];
			$clientIpDec = ( float ) sprintf ( "%u", ip2long ( $clientIP ) );
			$ipRanges = $cParams->get ( 'iprange_multiple', null);
			// Check if data are not null
			if($ipRanges) {
				// Try to load every range, one per row
				$explodeRows = explode(PHP_EOL, $ipRanges);
				if(!empty($explodeRows)) {
					foreach ($explodeRows as $singleRange) {
						// Try to detect single range
						$explodeRange = explode('-', $singleRange);
						if(!empty($explodeRange) && count($explodeRange) == 2) {
							$ipStart = trim($explodeRange[0]);
							$ipEnd = trim($explodeRange[1]);
							$validIpRangeStart = preg_match ( $ipAddressRegex, $ipStart );
							$validIpRangeEnd = preg_match ( $ipAddressRegex, $ipEnd );
							if ($validIpRangeStart && $validIpRangeEnd) {
								$lowerIpDec = ( float ) sprintf ( "%u", ip2long ( $ipStart ) );
								$upperIpDec = ( float ) sprintf ( "%u", ip2long ( $ipEnd ) );
								if (($clientIpDec >= $lowerIpDec) && ($clientIpDec <= $upperIpDec)) {
									return false;
								}
							}
						}
					}
				}
			}
		}
		
		require_once JPATH_BASE . '/administrator/components/com_jchat/framework/helpers/language.php';
		
		//load the translation
		$base = JUri::base();
		
		// Manage partial language translations
		$jLang = JFactory::getLanguage();
		$jLang->load('com_jchat', JPATH_SITE . '/components/com_jchat', 'en-GB', true, true);
		if($jLang->getTag() != 'en-GB') {
			$jLang->load('com_jchat', JPATH_SITE, null, true, false);
			$jLang->load('com_jchat', JPATH_SITE . '/components/com_jchat', null, true, false);
		}
		
		$chatLanguage = JChatHelpersLanguage::getInstance();
		
		// Inject js translations
		$translations = array(	'chat',
								'privatechat',
								'nousers',
								'nousers_filter',
								'gooffline',
								'available',
								'statooccupato',
								'statooffline',
								'defaultstatus',
								'sent_file',
								'received_file',
								'sent_file_waiting',
								'sent_file_downloaded',
								'sent_file_downloaded_realtime',
								'sent_file_download',
								'error_deleted_file',
								'error_notfound_file',
								'groupchat_filter',
								'addfriend',
								'optionsbutton',
								'maximizebutton_maximized',
								'maximizebutton_minimized',
								'closesidebarbutton',
								'spacer',  
								'scegliemoticons',
								'wall_msgs',
								'wall_msgs_refresh',
								'manage_avatars',
								'seconds',
								'minutes',
								'hours',
								'days',
								'years',
								'groupchat_request_sent',
								'groupchat_request_received',
								'groupchat_request_accepted',
								'groupchat_request_removed',
								'groupchat_request_received',
								'groupchat_request_accepted_owner',
								'groupchat_nousers',
								'groupchat_allusers',
								'audio_onoff',
								'trigger_emoticon',
								'trigger_fileupload',
								'trigger_export',
								'trigger_delete',
								'trigger_refresh',
								'trigger_skypesave',
								'trigger_skypedelete',
								'trigger_infoguest',
								'trigger_room',
								'trigger_history',
								'trigger_history_wall',
								'search',
								'invite',
								'pending',
								'remove',
								'userprofile_link',
								'you',
								'me',
								'seen',
								'banning',
								'banneduser',
								'startskypecall',
								'startskypedownload',
								'insert_skypeid',
								'skypeidsaved',
								'skypeid_deleted',
								'roomname',
								'roomcount',
								'available_rooms',
								'chatroom_users',
								'chatroom_join',
								'chatroom_joined',
								'noavailable_rooms',
								'chatroom',
								'insert_override_name',
								'trigger_override_name',
								'override_name_saved',
								'override_name_deleted',
								'select_period',
								'nomessages_available',
								'period_1d',
								'period_1w',
								'period_1m',
								'period_3m',
								'period_6m',
								'period_1y',
								'skype',
								'newmessage_tab'
		);
		$chatLanguage->injectJsTranslations($translations, $doc);
				
		// Output JS APP nel Document
		$baseTemplate = $cParams->get('chat_template', 'default.css');
		switch ($baseTemplate) {
			case 'custom.css':
				JHtml::stylesheet('com_jchat/css/templates/default.css', array(), true, false, false, false);
			break;
				
			case 'default.css';
			default:
				$doc->addStyleSheet(JURI::root(true) . '/components/com_jchat/css/templates/default.css');
			break;
		}
		
		$directTemplates = array('default.css', 'custom.css');
		if(!in_array($baseTemplate, $directTemplates)) {
			$doc->addStyleSheet(JURI::root(true) . '/components/com_jchat/css/templates/' . $baseTemplate);
		}
		
		// Scripts loading
		$defer = $cParams->get('scripts_loading', null) == 'defer' ? true : false;
		$async = $cParams->get('scripts_loading', null) == 'async' ? true : false;
		
		if($cParams->get('includejquery', 1)) {
			JHtml::_('jquery.framework');
		}
		if($cParams->get('noconflict', 1)) {
			$doc->addScript(JURI::root(true) . '/components/com_jchat/js/jquery.noconflict.js');
		}
		$doc->addScriptDeclaration("var jchat_livesite='$base';");
		$doc->addScriptDeclaration("var jchat_excludeonmobile='" . $cParams->get('exclude_onmobile', 0) . "';");
		
		// Manage by plugin append the chat target element based on rendering mode and related overridden styles
		$renderingMode = $cParams->get('rendering_mode', 'auto');
		$targetElement = $renderingMode == 'auto' ? 'body' : '#jchat_target';
		$doc->addScriptDeclaration("var jchatTargetElement='$targetElement';");
		// Add styles for module displacement
		if($renderingMode == 'module') {
			$doc->addStyleDeclaration('
				#jchat_base, #jchat_wall_popup, #jchat_userstab_popup {
						position: relative;
					}
				#jchat_base, #jchat_wall_popup, #jchat_userstab_popup {
						width: ' . ($cParams->get('sidebar_width', 250)) . 'px;
					}
				#jchat_userstab, #jchat_userstab.jchat_userstabclick {
						width: ' . ($cParams->get('sidebar_width', 250) - 2) . 'px;
					}
				#jchat_target {
						width: ' . $cParams->get('sidebar_width', 250) . 'px;
						height: ' . $cParams->get('sidebar_height', 600) . 'px;
					}
				#jchat_users_search {
						width: ' . $cParams->get('search_width', 100) . 'px;
					}
				#jchat_roomstooltip {
						width: ' . $cParams->get('chatroom_width', 400) . 'px;
					}
				#jchat_roomsdragger {
						width: ' . (int)($cParams->get('chatroom_width', 400) + 2) . 'px;
					}
				#jchat_users_search {
						padding: 2px 6px 0 16px;
					}
				#jchat_wall_popup.jchat_wall_minimized {
						top: 0;
					}
			');
		}
		
		$doc->addScript(JURI::root(true) . '/components/com_jchat/js/utility.js', 'text/javascript', $defer, $async);
		$doc->addScript(JURI::root(true) . '/components/com_jchat/js/jstorage.min.js', 'text/javascript', $defer, $async);
		$doc->addScript(JURI::root(true) . '/components/com_jchat/sounds/soundmanager2.js', 'text/javascript', $defer, $async);
		$doc->addScript(JURI::root(true) . '/components/com_jchat/js/sounds.js', 'text/javascript', $defer, $async);
		$doc->addScript(JURI::root(true) . '/components/com_jchat/js/main.js', 'text/javascript', $defer, $async);
		$doc->addScript(JURI::root(true) . '/components/com_jchat/js/emoticons.js', 'text/javascript', $defer, $async);
	}
	
	
	/**
	 * onAfterInitialise handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterInitialise() {
		$app = JFactory::getApplication(); 
		$component = JComponentHelper::getComponent('com_jchat');
		$cParams = $component->params;
		if(!$app->getClientId() && $cParams->get('includeevent', 'afterdispatch') == 'afterinitialize') {
			$this->injectApp($cParams, $app);
		}
	}
 
	/**
	 * onAfterInitialise handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterDispatch() {
		$app = JFactory::getApplication(); 
		$component = JComponentHelper::getComponent('com_jchat');
		$cParams = $component->params;
		if(!$app->getClientId() && $cParams->get('includeevent', 'afterdispatch') == 'afterdispatch') {
			$this->injectApp($cParams, $app);
		}
	} 
	
	/**
	 * Class Constructor 
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	public function __construct(& $subject, $config) {
		parent::__construct ( $subject, $config );
	} 
}
