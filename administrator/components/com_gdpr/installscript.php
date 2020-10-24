<?php
//namespace administrator\components\com_gdpr;
/**
 * Application install script
 * @package GDPR::INSTALL::administrator::components::com_gdpr 
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html    
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.filesystem.file' );

/** 
 * Application install script class
 * @package GDPR::administrator::components::com_gdpr  
 */
class com_gdprInstallerScript {
	/*
	* Find mimimum required joomla version for this extension. It will be read from the version attribute (install tag) in the manifest file
	*/
	private $minimum_joomla_release = '3.0';
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	function preflight($type, $parent) {
		// Check for Joomla compatibility
		if(version_compare(JVERSION, '3', '<') || version_compare(JVERSION, '4', '>=')) {
			JFactory::getApplication()->enqueueMessage (JText::sprintf('COM_GDPR_INSTALLING_VERSION_NOTCOMPATIBLE', JVERSION), 'error');
			return false;
		}
	}
	
	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 */
	function install($parent) {
		$database = JFactory::getDBO ();
		echo ('<style type="text/css">div.alert-success, span.step_details {display: none;font-size: 12px;} span.step_details div{margin-top:0 !important;}.installcontainer{width: 720px;}</style>');
		echo ('<link rel="stylesheet" type="text/css" href="' . JUri::root ( true ) . '/administrator/components/com_gdpr/css/bootstrap-install.css' . '" />');
		echo ('<script type="text/javascript" src="' . JUri::root ( true ) . '/administrator/components/com_gdpr/js/installer.js' .'"></script>' );
		$lang = JFactory::getLanguage ();
		$lang->load ( 'com_gdpr' );
		
		$parentParent = $parent->getParent();
		
		// Component installer
		$componentInstaller = JInstaller::getInstance ();
		$pathToSystemPlugin = $componentInstaller->getPath ( 'source' ) . '/plugins/system';
		
		echo ('<div class="installcontainer">');
		$systemPluginInstaller = new JInstaller ();
		if (! $systemPluginInstaller->install ( $pathToSystemPlugin )) {
			echo '<p>' . JText::_ ( 'COM_GDPR_ERROR_INSTALLING_PLUGINS' ) . '</p>';
			// Install failed, rollback changes
			$parentParent->abort(JText::_('COM_GDPR_ERROR_INSTALLING_PLUGINS'));
			return false;
		} else {
			$query = "UPDATE #__extensions" . "\n SET enabled = 1" .
					 "\n WHERE type = 'plugin' AND element = " . $database->quote ( 'gdpr' ) .
					 "\n AND folder = " . $database->quote ( 'system' );
			$database->setQuery ( $query );
			if (! $database->execute ()) {
				echo '<p>' . JText::_ ( 'COM_GDPR_ERROR_PUBLISHING_PLUGIN' ) . '</p>';
			}?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_GDPR_OK_INSTALLING_SYSTEM_PLUGINS');?></span>
				</div>
			</div>
			<?php 
		}
		
		?>
		<div class="progress">
			<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
				<span class="step_details"><?php echo JText::_('COM_GDPR_OK_INSTALLING_COMPONENT');?></span>
		  	</div>
		</div>
		
		<div class="alert alert-success"><?php echo JText::_('COM_GDPR_ALL_COMPLETED');?></div>
		<div class="alert alert-info"><?php echo JText::_('COM_GDPR_CLEAR_BROWSER_CACHE');?></div>
		<?php 
		echo ('</div>');
		
		// DB UPDATES PROCESSING
		$database = JFactory::getDbo();
		$tablesToUpdate = array('#__gdpr_consent_registry'=>'session_id', '#__gdpr_logs'=>'email');
		foreach ($tablesToUpdate as $tableToUpdate=>$afterField) {
			$queryFields = 	"SHOW COLUMNS " .
							"\n FROM " . $database->quoteName($tableToUpdate);
			$database->setQuery($queryFields);
			try {
				$elements = $database->loadColumn();
				if(!in_array('ipaddress', $elements)) {
					$addFieldQuery = "ALTER TABLE " .  $database->quoteName($tableToUpdate) .
									 "\n ADD " . $database->quoteName('ipaddress') .
									 "\n VARCHAR(255) NULL AFTER " .  $database->quoteName($afterField);
					$database->setQuery($addFieldQuery)->execute();
				}
			} catch (Exception $e) { }
		}
		
		return true;
	}
	
	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update($parent) {
		// Execute always SQL install file to get added updates in that file, disregard DBMS messages and Joomla queue for user
		$parentParent = $parent->getParent();
		$parentManifest = $parentParent->getManifest();
		try {
			// Install/update always without error handlingm case legacy J Error
			$legacyClassName = 'J' . 'Error';
			$legacyClassName::setErrorHandling(E_ALL, 'ignore');
			if (isset($parentManifest->install->sql)) {
				$parentParent->parseSQLFiles($parentManifest->install->sql);
			}
		} catch (Exception $e) {
			// Do nothing for user for Joomla 3.x case, case Exception handling
		}
		
		$this->install($parent);
	}
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent) { 
		// Configuration
		// General settings
		$params ['registration_email'] = '';
		$params ['log_empty_save'] = '1';
		$params ['log_user_create'] = '1';
		$params ['log_user_delete'] = '1';
		$params ['log_user_ipaddress'] = '0';
		$params ['notify_user_self_delete'] = '0';
		$params ['notify_revoked_consents'] = '0';
		$params ['logs_mailfrom'] = '';
		$params ['logs_fromname'] = '';
		$params ['logs_emails'] = '';
		
		// Cookie consent
		$params ['enable_cookie_consent'] = '1';
		$params ['enable_log_cookie_consent'] = '1';
		$params ['compliance_type'] = 'opt-in';
		$params ['disable_first_reload'] = '0';
		$params ['block_joomla_session_cookie'] = '1';
		$params ['block_external_cookies_domains'] = '0';
		$params ['external_blocking_mode'] = 'simple';
		$params ['external_advanced_blocking_mode_tags'] = 'iframe,script,img,source,link';
		$params ['external_cookies_domains'] =  'googletagmanager.com' . PHP_EOL . 
												'google-analytics.com' . PHP_EOL .
												'adsbygoogle.js' . PHP_EOL .
												'googleadservices.com' . PHP_EOL .
												'googlesyndication.com' . PHP_EOL .
												'paypal.com' . PHP_EOL . 
												'facebook.com' . PHP_EOL .
												'facebook.net' . PHP_EOL .
												'facebook.it'. PHP_EOL .
												'google.com' . PHP_EOL .
												'google.it' . PHP_EOL .
												'googlecode.com' . PHP_EOL .
												'googleapis.com' . PHP_EOL .
												'doubleclick.net' . PHP_EOL .
												'twitter.com' . PHP_EOL .
												'twitterfeed.com' . PHP_EOL .
												'youtube.com' . PHP_EOL .
												'youtube-nocookie.com' . PHP_EOL .
												'dailymotion.com' . PHP_EOL .
												'vimeo.com' . PHP_EOL .
												'linkedin.com' . PHP_EOL .
												'pinterest.com' . PHP_EOL .
												'digg.com' . PHP_EOL .
												'instagram.com' . PHP_EOL .
												'addthis.com' . PHP_EOL .
												'eventbrite.it' . PHP_EOL .
												'eventbrite.com' . PHP_EOL .
												'addtoany.com' . PHP_EOL .
												'mixpanel.com' . PHP_EOL .
												'adform.net' . PHP_EOL .
												'performgroup.com';
		$params ['allow_local_cookies'] = '';
		$params ['block_cookie_define'] = '1';
		$params ['block_local_cookies_server_side'] = '0';
		$params ['auto_accept_on_next_page'] = '0';
		$params ['revokable'] = '1';
		$params ['lawbycountry'] = '0';
		$params ['checkboxlawbycountry'] = '0';
		$params ['open_always_declined'] = '1';
		$params ['default_closed_toolbar'] = '0';
		$params ['dismiss_onscroll'] = '0';
		$params ['dismiss_ontimeout'] = '0';
		$params ['container_selector'] = 'body';
		$params ['hide_on_mobile_devices'] = '0';
		$params ['placeholder_blocked_resources'] = '0';
		$params ['placeholder_blocked_resources_text'] = 'You must accept cookies and reload the page to view this content';
		$params ['layout'] = 'basic';
		$params ['theme'] = 'block';
		$params ['position'] = 'bottom';
		$params ['position_center_blur_effect'] = '1';
		$params ['positionment_type'] = '1';
		$params ['revokeposition'] = 'revoke-top';
		$params ['revocabletheme'] = 'basic';
		$params ['popup_background'] = '#000000';
		$params ['popup_fontsize'] = '16';
		$params ['revocable_button_fontsize'] = '16';
		$params ['popup_padding'] = '1';
		$params ['popup_background_opacity'] = '100';
		$params ['popup_text'] = '#FFFFFF';
		$params ['popup_link'] = '#FFFFFF';
		$params ['button_background'] = '#FFFFFF';
		$params ['button_border'] = '#FFFFFF';
		$params ['button_text'] = '#000000';
		$params ['highlight_background'] = '#333333';
		$params ['highlight_border'] = '#FFFFFF';
		$params ['highlight_text'] = '#FFFFFF';
		$params ['highlight_dismiss_background'] = '#333333';
		$params ['highlight_dismiss_border'] = '#FFFFFF';
		$params ['highlight_dismiss_text'] = '#FFFFFF';
		$params ['hide_revokable_button'] = '0';
		$params ['hide_revokable_button_onscroll'] = '0';
		$params ['custom_revokable_button'] = '0';
		$params ['header'] = 'Cookies used on the website!';
		$params ['message'] = 'This website uses cookies to ensure you get the best experience on our website.';
		$params ['dismiss_text'] = 'Got it!';
		$params ['allow_text'] = 'Allow cookies';
		$params ['deny_text'] = 'Decline';
		$params ['cookie_policy_link_text'] = 'Cookie policy';
		$params ['cookie_policy_link'] = '';
		$params ['cookie_policy_revocable_tab_text'] = 'Cookie policy';
		$params ['privacy_policy_link_text'] = 'Privacy policy';
		$params ['privacy_policy_link'] = '';
		$params ['show_links'] = '1';
		$params ['blank_links'] = '_blank';
		$params ['auto_open_privacy_policy'] = '0';
		$params ['deny_message_enabled'] = '0';
		$params ['deny_message'] = 'You have declined cookies, to ensure the best experience on this website please consent the cookie usage.';
		$params ['use_fancybox_links'] = '0';
		$params ['fancybox_width'] = '700';
		$params ['fancybox_height'] = '800';
		$params ['popup_format_template'] = '1';
		$params ['use_cookie_policy_contents'] = '0';
		$params ['cookie_policy_contents'] = '';
		$params ['use_privacy_policy_contents'] = '0';
		$params ['privacy_policy_contents'] = '';

		// Cookie categories
		$params ['cookie_settings_label'] = 'Cookie settings:';
		$params ['cookie_settings_desc'] = 'Choose which kind of cookies you want to disable by clicking on the checkboxes. Click on a category name for more informations about used cookies.';
		$params ['toggle_cookie_settings'] = '0';
		$params ['toggle_cookie_settings_text'] = 'Settings';
		$params ['propagate_categories_session'] = '1';
		$params ['always_reload_after_categories_change'] = '0';
		$params ['cookie_category1_enable'] = '0';
		$params ['cookie_category1_checked'] = '1';
		$params ['cookie_category1_locked'] = '0';
		$params ['cookie_category1_name'] = 'Necessary';
		$params ['cookie_category1_description'] = 'Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies.';
		$params ['cookie_category1_list'] =  '';
		$params ['domains_category1_list'] =  '';
		
		$params ['cookie_category2_enable'] = '0';
		$params ['cookie_category2_checked'] = '1';
		$params ['cookie_category2_locked'] = '0';
		$params ['cookie_category2_name'] = 'Preferences';
		$params ['cookie_category2_description'] = 'Preference cookies enable a website to remember information that changes the way the website behaves or looks, like your preferred language or the region that you are in.';
		$params ['cookie_category2_list'] =  'NID' . PHP_EOL .
											 'SID' . PHP_EOL .
											 'HSID' . PHP_EOL .
											 'lbcs';
		$params ['domains_category2_list'] = '';
		
		$params ['cookie_category3_enable'] = '0';
		$params ['cookie_category3_checked'] = '0';
		$params ['cookie_category3_locked'] = '0';
		$params ['cookie_category3_name'] = 'Statistics';
		$params ['cookie_category3_description'] = 'Statistic cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.';
		$params ['cookie_category3_list'] =  '_ga' . PHP_EOL .
											 '_gid' . PHP_EOL .
											 '_gat' . PHP_EOL .
											 '__utma' . PHP_EOL .
											 '__utmb' . PHP_EOL .
											 '__utmc' . PHP_EOL .
											 '__utmv' . PHP_EOL .
											 '__utmz' . PHP_EOL .
											 '__utm.gif' . PHP_EOL .
											 'rur' . PHP_EOL .
											 'urlgen' . PHP_EOL .
											 'uuid' . PHP_EOL .
											 'uuidc';
		$params ['domains_category3_list'] = 'addthis.com' . PHP_EOL . 
											 'admob.com' . PHP_EOL .
											 'adnxs.com' . PHP_EOL .
											 'adsensecustomsearchads.com' . PHP_EOL . 
											 'adwords.com' . PHP_EOL .
											 'crwdcntrl.net' . PHP_EOL .
											 'disqus.com' . PHP_EOL .
											 'doubleclick.net' . PHP_EOL .
											 'googleapis.com' . PHP_EOL . 
											 'googlesyndication.com' . PHP_EOL . 
											 'googletagmanager.com' . PHP_EOL . 
											 'googletagservices.com' . PHP_EOL . 
											 'googletraveladservices.com' . PHP_EOL . 
											 'googleusercontent.com' . PHP_EOL . 
											 'google-analytics.com' . PHP_EOL . 
											 'gstatic.com' . PHP_EOL .
											 'mathtag.com' . PHP_EOL .
											 'semasio.net' . PHP_EOL .
											 'tripadvisor.com' . PHP_EOL .
											 'urchin.com' . PHP_EOL . 
											 'youtube.com' . PHP_EOL . 
											 'ytimg.com';
		
		$params ['cookie_category4_enable'] = '0';
		$params ['cookie_category4_checked'] = '0';
		$params ['cookie_category4_locked'] = '0';
		$params ['cookie_category4_name'] = 'Marketing';
		$params ['cookie_category4_description'] = 'Marketing cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.';
		$params ['cookie_category4_list'] = 'IDE' . PHP_EOL .
											'ANID' . PHP_EOL .
											'DSID' . PHP_EOL .
											'FLC' . PHP_EOL .
											'AID' . PHP_EOL .
											'TAID' . PHP_EOL .
											'exchange_uid' . PHP_EOL .
											'__gads' . PHP_EOL .
											'__gac' . PHP_EOL .
											'Conversion' . PHP_EOL .
											'NID' . PHP_EOL .
											'SID' . PHP_EOL .
											'fr' . PHP_EOL .
											'tr' . PHP_EOL .
											'uuid' . PHP_EOL .
											'uuid2' . PHP_EOL .
											'uuidc' . PHP_EOL .
											'MUID' . PHP_EOL .
											'MUIDB';
		$params ['domains_category4_list'] = 'addthis.com' . PHP_EOL .
											 'adnxs.com' . PHP_EOL .
											 'adsrvr.org' . PHP_EOL .
											 'adtech.com' . PHP_EOL .
											 'advertising.com' . PHP_EOL .
											 'bidswitch.net' . PHP_EOL .
											 'casalemedia.com' . PHP_EOL .
											 'contextweb.com' . PHP_EOL .
											 'criteo.com' . PHP_EOL .
											 'demdex.net' . PHP_EOL .
											 'doubleclick.net' . PHP_EOL .
											 'googleadservices.com' . PHP_EOL .
											 'hubspot.com' . PHP_EOL .
											 'instagram.com' . PHP_EOL .
											 'openx.net' . PHP_EOL .
											 'pubmatic.com' . PHP_EOL .
											 'rlcdn.com' . PHP_EOL .
											 'yieldlab.net' . PHP_EOL .
											 'youtube.com' . PHP_EOL .
											 'smartadserver.com' . PHP_EOL .
											 'tradedoubler.com';
		
		// User profile
		$params ['userprofile_buttons_delete'] = '1';
		$params ['userprofile_buttons_export'] = '1';
		$params ['userprofile_delete_additional_contents'] = '0';
		$params ['userprofile_delete_mode'] = 'pseudonymisation';
		$params ['include_raw_post_fields'] = '0';
		$params ['userprofile_buttons_workingmode'] = '0';
		$params ['userprofile_self_delete_confirmation'] = '0';
		$params ['userprofile_form_action_workingmode'] = 'base';
		$params ['integrate_comprivacy'] = '1';
		$params ['3pdintegration'] = '';
		$params ['custom_components_view_userprofile_buttons'] = '';
		$params ['custom_components_view_userprofile_buttons_selector'] = '';
		$params ['custom_components_userprofile_buttons'] = '';
		$params ['custom_forms_userprofile_buttons'] = '';
		
		$params ['privacy_policy_checkbox'] = '1';
		$params ['custom_components_view_form_checkbox'] = '';
		$params ['custom_components_view_form_checkbox_selector'] = '';
		$params ['custom_components_form_checkbox'] = '';
		$params ['custom_forms_task_checkbox'] = '';
		$params ['privacy_policy_checkbox_link_text'] = 'Privacy policy';
		$params ['privacy_policy_checkbox_link'] = '';
		$params ['privacy_policy_checkbox_link_title'] = 'Please agree to our privacy policy, otherwise you will not be able to register.';
		$params ['log_usernote_privacypolicy'] = '1';
		$params ['log_usernote_privacypolicy_category'] = '0';
		$params ['log_userconsent_privacypolicy'] = '1';
		$params ['privacypolicy_serverside_validation'] = '0';
		$params ['revokable_privacypolicy'] = '0';
		$params ['block_privacypolicy'] = '0';
		$params ['use_checkbox'] = '1';
		$params ['use_dynamic_checkbox'] = '1';
		$params ['remove_attributes'] = '1';
		$params ['force_submit_button'] = '0';
		$params ['remove_submit_button_events'] = '0';
		$params ['checkbox_submission_method'] = 'form';
		$params ['custom_submission_method_selectors'] = 'input[type=submit],button[type=submit],button[type=button]';
		$params ['custom_append_method'] = '0';
		$params ['custom_append_method_selectors'] = 'input[type=submit],button[type=submit]';
		$params ['custom_append_method_target_element'] = 'parent';
		$params ['consent_logs_formfields'] = 'name,email,subject,message';
		$params ['consent_registry_format'] = 'csv';
		$params ['consent_registry_include_pseudonymised'] = '0';
		$params ['consent_registry_track_previous_consent'] = '1';
		$params ['consent_generic_bypage'] = '1';
		$params ['consent_dynamic_checkbox_bypage'] = '1';
		
		$params ['checkbox_template_container'] = "<div class='control-group'>{field}</div>";
		$params ['checkbox_template_label'] = "<div class='control-label' style='display:inline-block'>{label}</div>";
		$params ['checkbox_template_controls'] = "<div class='controls' style='display:inline-block;margin-left:20px'>{checkbox}</div>";
		$params ['privacy_policy_checkbox_order'] = 'right';
		$params ['checkbox_controls_class'] = '0';
		$params ['checkbox_controls_class_list'] = 'required';
		
		$params ['use_fancybox_checkbox'] = '0';
		$params ['fancybox_checkbox_width'] = '700';
		$params ['fancybox_checkbox_height'] = '800';
		$params ['use_checkbox_contents'] = '0';
		$params ['checkbox_contents'] = '';
		
		// Record of processing activities
		$params ['data_controller_company_name'] = '';
		$params ['data_controller_person_name'] = '';
		$params ['data_controller_address'] = '';
		$params ['data_controller_vat'] = '';
		$params ['data_controller_phone'] = '';
		$params ['data_controller_email'] = '';
		$params ['data_controller_digital_email'] = '';
		
		$params ['data_controller_representative_company_name'] = '';
		$params ['data_controller_representative_person_name'] = '';
		$params ['data_controller_representative_address'] = '';
		$params ['data_controller_representative_vat'] = '';
		$params ['data_controller_representative_phone'] = '';
		$params ['data_controller_representative_email'] = '';
		$params ['data_controller_representative_digital_email'] = '';
		
		$params ['data_processor_company_name'] = '';
		$params ['data_processor_person_name'] = '';
		$params ['data_processor_address'] = '';
		$params ['data_processor_vat'] = '';
		$params ['data_processor_phone'] = '';
		$params ['data_processor_email'] = '';
		$params ['data_processor_digital_email'] = '';
		
		// Data breach
		$params ['databreach_email_subject'] = 'A data breach occurred on: {sitename}';
		$params ['databreach_email_content'] = "<p style='font-weight:bold'>Data Breach notification on: {sitename} at: {siteurl}</p><p><em>{date}</em></p><p></p><p>Dear {nameofuser}:</p><p>We value your business and respect the privacy of your information, which is why, as a precautionary measure, we are writing to let you know about a data security incident that involves your personal information.In the last 72 hours, a data breach incident occurred on our website. The data accessed have included personal informations such as email address, name and username.</p><p></p><p><span style='font-weight:bold'>{sitename}</span> values your privacy and deeply regrets that this incident occurred.</p><p><span style='font-weight:bold'>{sitename}</span> has implemented additional security measures designed to prevent a recurrence of such an attack, and to protect the privacy of your data.</p><p>Moreover our company is working closely with the protection commissioner to ensure the incident is properly addressed.</p><p></p><p>For further information and assistance, please contact <span style='font-weight:bold'>{sitename}</span> at: <span style='font-weight:bold'>{emailaddress}</span>.</p><p></p><p>Sincerely, <span style='font-weight:bold'>{sitename}</span></p>";
		$params ['databreach_garante_notify'] = '0';
		$params ['databreach_garante_email_subject'] = 'A data breach occurred on: {sitename}';
		$params ['databreach_garante_email_content'] = "<p style='font-weight: bold;'>Data Breach notification on: {sitename} at: {siteurl}</p><p><em>{date}</em></p><p></p><p>A data breach for the user {nameofuser} on {sitename} occurred on <em>{date}</em>, which is why, as a precautionary measure, we are writing to let you know about a data security incident that involves personal information. In the last 72 hours, a data breach incident occurred on our website. The data accessed have included personal informations such as email address, name and username.</p><p></p><p>Our company is working hard to ensure that the incident is properly addressed.</p><p></p><p>For further information and assistance, please contact <span style='font-weight: bold;'>{sitename}</span> at: <span style='font-weight: bold;'>{emailaddress}</span>.</p><p></p><p>Sincerely, <span style='font-weight: bold;'>{sitename}</span></p>";
		$params ['databreach_mailfrom'] = '';
		$params ['databreach_fromname'] = '';
		
		// Advanced configuration
		$params ['disallow_cookie'] = array('0');
		$params ['disallow_privacypolicy'] = array('0');
		$params ['disallow_deleteprofile'] = array('8');
		$params ['disallow_exportprofile'] = array('0');
		$params ['disallow_logs'] = array('0');
		
		$params ['exclude_logs'] = array('0');
		$params ['exclude_cookie_consent'] = array('0');
		$params ['exclude_userprofile'] = array('0');
		$params ['exclude_privacycheckbox'] = array('0');
		$params ['menu_exclusions'] = array('0');
		
		$params ['custom_css_styles'] = '';
		$params ['csv_delimiter'] = ';';
		$params ['csv_enclosure'] = '"';
		$params ['xls_format'] = '1';
		
		$params ['jch_noconflict'] = '1';
		$params ['scripts_noconflict'] = '0';
		$params ['auto_manage_caching'] = '1';
		$params ['disable_dynamic_checkbox'] = '0';
		$params ['override_change_login_name'] = '1';
		$params ['jquery_include'] = '1';
		$params ['jquery_noconflict'] = '0';
		$params ['disable_version_checker'] = '0';
		$params ['debug'] = '0';
		
		// Insert all params settings default first time, merge and insert only new one if any on update, keeping current settings
		if ($type == 'install') {
			$this->setParams ( $params ); 
			
		} elseif ($type == 'update') {
			// Load and merge existing params, this let add new params default and keep existing settings one
			$db = JFactory::getDbo ();
			$query = $db->getQuery(true);
			$query->select('params');
			$query->from('#__extensions');
			$query->where($db->quoteName('name') . '=' . $db->quote('gdpr'));
			$db->setQuery($query);
			$existingParamsString = $db->loadResult();
			// store the combined new and existing values back as a JSON string
			$existingParams = json_decode ( $existingParamsString, true );
			
			// Merge params
			$updatedParams = array_merge($params, $existingParams);
			
			$this->setParams($updatedParams);
		}
	}
	
	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall($parent) {
		$database = JFactory::getDBO ();
		$lang = JFactory::getLanguage();
		$lang->load('com_gdpr');
		 
		// Check if system plugin exists
		$query = "SELECT extension_id" .
				 "\n FROM #__extensions" .
				 "\n WHERE type = 'plugin' AND element = " . $database->quote('gdpr') .
				 "\n AND folder = " . $database->quote('system');
		$database->setQuery($query);
		$pluginID = $database->loadResult();
		if(!$pluginID) {
			echo '<p>' . JText::_('COM_GDPR_PLUGIN_ALREADY_REMOVED') . '</p>';
		} else {
			// New plugin installer
			$systemPluginInstaller = new JInstaller ();
			if(!$systemPluginInstaller->uninstall('plugin', $pluginID)) {
				echo '<p>' . JText::_('COM_GDPR_ERROR_UNINSTALLING_PLUGINS') . '</p>';
			}
		}
		
		// Uninstall complete
		return true;
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam($name) {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT manifest_cache FROM #__extensions WHERE element = "com_gdpr"' );
		$manifest = json_decode ( $db->loadResult (), true );
		return $manifest [$name];
	}
	
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if (count ( $param_array ) > 0) { 
			$db = JFactory::getDbo (); 
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode ( $param_array );
			$db->setQuery ( 'UPDATE #__extensions SET params = ' . $db->quote ( $paramsString ) . ' WHERE element = "com_gdpr"' );
			$db->execute ();
		}
	}
}