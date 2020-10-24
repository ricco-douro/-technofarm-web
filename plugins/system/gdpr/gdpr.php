<?php
/**
 * @author Joomla! Extensions Store
 * @package GDPR::plugins::system
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.plugin.plugin' );

/**
 * Observer class notified on events
 *
 * @author Joomla! Extensions Store
 * @package GDPR::plugins::system
 * * @since 1.0
 */
class plgSystemGdpr extends JPlugin {
	/**
	 * Parameters object for component and plugin
	 * 
	 * @access private
	 * @var Object
	 */
	private $cParams;
	
	/**
	 * App instance
	 *
	 * @access private
	 * @var Object
	 */
	private $app;
	
	/**
	 * Init state
	 *
	 * @access private
	 * @var bool
	 */
	private $hasCookieCategory;
	
	/**
	 * Init state
	 *
	 * @access private
	 * @var bool
	 */
	private $unsetCategoriesCookies = array();
	
	/**
	 * Load manifest file for this type of data source
	 * @access private
	 * @return mixed
	 */
	private function loadManifest($option) {
		// Load configuration manifest file
		$fileName = JPATH_ROOT . '/components/com_gdpr/manifests/' . $option . '.json';
	
		// Check if file exists and is valid manifest
		if(!file_exists($fileName)) {
			return false;
		}
	
		// Load the manifest serialized file and assign to local variable
		$manifest = file_get_contents($fileName);
		$manifestConfiguration = json_decode($manifest);
	
		return $manifestConfiguration;
	}
	
	/**
	 * Force a first top head append of the configuration script
	 * @access private
	 * @return void
	 */
	private function addConfigToHead() {
		static $headAdded;

		if(!$headAdded) {
			$body = $this->app->getBody ();

			// Replace buffered main view contents at the body end
			$body = preg_replace ( '/<head>/i', '<head>' . '<script>'. $this->configurationOptionsScript . '</script>', $body, 1 );

			// Set the new JResponse contents
			$this->app->setBody ( $body );
			
			$headAdded = true;
		}
	}
	
	/**
	 * Function to kill al external resources to prevent loading of third party cookies
	 * 
	 * @access private
	 * @param string $categoryDomains
	 * @return void
	 */
	private function killExternalResources ($categoryDomains = null) {
		// Support for custom component forms
		$domainsToKill = array();
		$advancedKillResourceMethod = false;
		
		if($this->cParams->get('external_blocking_mode', 'simple') == 'advanced') {
			static $simpleHtmlDomInstance, $domElements;
			if(!$simpleHtmlDomInstance) {
				require_once (JPATH_ROOT . '/plugins/system/gdpr/simplehtmldom.php');
				$killResourceSelector = trim($this->cParams->get('external_advanced_blocking_mode_tags', 'iframe,script,img,source,link'), ',');
				$simpleHtmlDomInstance = new GdprSimpleHtmlDom();
				$simpleHtmlDomInstance->load( $this->app->getBody () );
				$domElements = $simpleHtmlDomInstance->find( $killResourceSelector );
			}
			$advancedKillResourceMethod = true;
		}
		
		// Kill all domains
		if(!$categoryDomains) {
			$defaultDomains = 	'googletagmanager.com' . PHP_EOL .
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
			$domainsParameter = trim($this->cParams->get('external_cookies_domains', $defaultDomains));
		} else {
			$domainsParameter = $categoryDomains;
		}
		
		if($domainsParameter) {
			$domainsToKill = explode(PHP_EOL, $domainsParameter);
			if(!empty($domainsToKill)) {
				$sameBaseDomain = JUri::base(false);
				$body = $this->app->getBody ();
				foreach ($domainsToKill as &$domainToKill) {
					$domainToKill = trim($domainToKill);
					// Ensure to never kill local resources!!!
					if(stripos($sameBaseDomain, $domainToKill) !== false) {
						continue;
					}
					
					if(!$advancedKillResourceMethod) {
						// Replace buffered main view contents at the body end
						$body = JString::str_ireplace( $domainToKill, md5($domainToKill) . '-gdprlock', $body );
					} else {
						// Advanced DOM mode
						foreach ( $domElements as $element ) {
							// Skip invalid resources without the src attribute
							$hasSrc = $element->hasAttribute( 'src' );
							$hasHref = $element->hasAttribute( 'href' );
							if(!$hasSrc && !$hasHref) {
								// If it's an no-src inline script tag, ensure that there are no matched domain in the text node
								if($element->tag == 'script') {
									$nodeText = $element->text(true);
									$nodeText = JString::str_ireplace( $domainToKill, '', $nodeText );
									$element->innertext = $nodeText;
								}
								continue;
							}
							// Tags with src attribute
							if($hasSrc) {
								$elementSrc = $element->getAttribute('src');
								if(stripos($elementSrc, $domainToKill) !== false) {
									$element->removeAttribute('src');
									$element->setAttribute('data-source', 'gdprlock');
								}
							}
							// tags with href attribute
							if($hasHref) {
								$elementHref = $element->getAttribute('href');
								if(stripos($elementHref, $domainToKill) !== false) {
									$element->removeAttribute('href');
									$element->setAttribute('data-source', 'gdprlock');
								}
							}
						}
					}
				}

				// Set the new JResponse contents
				if($advancedKillResourceMethod) {
					$body = $simpleHtmlDomInstance->save();
				}
				
				// Final assignment
				$this->app->setBody ( $body );
			}
		}
	}
	
	/**
	 * Must even local resources be killed PHP server side?
	 *
	 * @access private
	 * @param string $categoryCookie
	 * @return void
	 */
	private function killLocalCookies($categoryCookie = null) {
		$excludedLocalCookies = explode(',', trim($this->cParams->get('allow_local_cookies', '')));
		
		if(!$this->cParams->get('block_joomla_session_cookie', 1) || $this->hasCookieCategory) {
			$excludedLocalCookies[] = $this->app->getSession()->getName();
			$excludedLocalCookies[] = md5(JApplicationHelper::getHash('administrator'));
		}
		
		$cookies = $this->app->input->cookie->getArray();
		if(!empty($cookies)) {
			// Block all cookies
			if(!$categoryCookie) {
				foreach ($cookies as $cookieName=>$cookieValue) {
					if(!in_array($cookieName, $excludedLocalCookies)) {
						$this->app->input->cookie->set($cookieName, '', -1, '/');
					}
				}
			} else {
				// Block only cookies in the not accepted category
				$categoryCookieArray = explode(PHP_EOL, $categoryCookie);
				foreach ($categoryCookieArray as &$singleCookieInCategory) {
					$singleCookieInCategory = trim($singleCookieInCategory);
				}

				foreach ($cookies as $cookieName=>$cookieValue) {
					if(in_array($cookieName, $categoryCookieArray) && !in_array($cookieName, $excludedLocalCookies)) {
						$this->app->input->cookie->set($cookieName, '', -1, '/');
						$this->unsetCategoriesCookies[] = $cookieName;
					}
				}
			}
		}
	}
	
	/**
	 * Check for the valid filtered execution of the plugin based on option and view selected
	 *
	 * @param string $feature
	 * @access private
	 * @return bool
	 */
	private function validateExecution($feature) {
		static $isBot, $menuExcluded;
		
		// Get the dispatched option, view and id
		$option = $this->app->input->get('option');
		$excludedExtensions = $this->cParams->get($feature, array('0'));
	
		// An invalid execution detected for this component?
		if(in_array($option, $excludedExtensions, true)) {
			return false;
		}
		
		// Add invalid execution check for menu page exclusions
		if(!isset($menuExcluded)) {
			$menu = $this->app->getMenu ()->getActive ();
			if (is_object ( $menu )) {
				$menuItemid = $menu->id;
				$menuExclusions = $this->cParams->get ( 'menu_exclusions' );
				if (is_array ( $menuExclusions ) && ! in_array ( 0, $menuExclusions, false ) && in_array ( $menuItemid, $menuExclusions )) {
					$menuExcluded = true;
				} else {
					$menuExcluded = false;
				}
			}
		}
		if($menuExcluded) {
			return false;
		}
		
		// Check for user agent exclusion
		if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
			if(!isset($isBot)) {
				$user_agent = $_SERVER ['HTTP_USER_AGENT'];
				$botRegexPattern = "(Googlebot\/|Googlebot\-Mobile|Googlebot\-Image|Googlebot\-Video|Google favicon|JSitemapbot|Mediapartners\-Google|bingbot|slurp|java|wget|curl|Commons\-HttpClient|Python\-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST\-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub\.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum\.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips\-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail\.RU_Bot|discobot|heritrix|findthatfile|europarchive\.org|NerdByNature\.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb\-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web\-archive\-net\.com\.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks\-robot|it2media\-domain\-crawler|ip\-web\-crawler\.com|siteexplorer\.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki\-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e\.net|GrapeshotCrawler|urlappendbot|brainobot|fr\-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf\.fr_bot|A6\-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive\.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j\-asr|Domain Re\-Animator Bot|AddThis)";
				$isBot = preg_match("/{$botRegexPattern}/i", $user_agent);
			}
			if($isBot) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Check a specific feature exclusion by group
	 *
	 * @param string $feature
	 * @access private
	 * @return bool
	 */
	private function checkExclusionPermissions($feature) {
		static $userGroups;
		
		$isExcluded = false;
		
		if(!$userGroups) {
			$user = JFactory::getUser();
			$userGroups = $user->getAuthorisedGroups();
		}
		
		$featureExcludedGroups = $this->cParams->get($feature, array(0));
		
		if(is_array($featureExcludedGroups) && !in_array(0, $featureExcludedGroups, false)) {
			$intersectResult = array_intersect($userGroups, $featureExcludedGroups);
			$isExcluded = (int)(count($intersectResult));
		}
		
		return $isExcluded;
	}
	
	/**
	 * Load the main component frontend language strings
	 *
	 * @param string
	 * @access private
	 * @return void
	 */
	private function loadComponentLanguage() {
		static $languageLoaded = false;
		
		// Manage partial language translations
		if(!$languageLoaded) {
			$jLang = JFactory::getLanguage();
			$jLang->load('com_gdpr', JPATH_SITE . '/components/com_gdpr', 'en-GB', true, true);
			if($jLang->getTag() != 'en-GB') {
				$jLang->load('com_gdpr', JPATH_SITE, null, true, false);
				$jLang->load('com_gdpr', JPATH_SITE . '/components/com_gdpr', null, true, false);
			}
		}
		
		$languageLoaded = true;
	}
	
	/**
	 * Method to be called after the app initialise to kill the page caching preventing third party cookies refresh
	 *
	 * @return void
	 */
	public function onAfterInitialise () {
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			return;
		}

		// Only if page caching plugin is enabled
		if(!JPluginHelper::isEnabled('system', 'cache') && !JPluginHelper::isEnabled('system', 'jotcache')) {
			return;
		}

		// Avoid for logged in users, no page cache for them
		if(JFactory::getUser()->id) {
			return;
		}

		// Output JS APP nel Document
		if(stripos($_SERVER['REQUEST_URI'], '/rss/')) { return; } // Fix for sh404sef RSS routing issue

		// Disable auto caching management
		if(!$this->cParams->get('auto_manage_caching', 1)) {
			return;
		}
		
		$document = JFactory::getDocument();
		if($document->getType() !== 'html' || $this->app->input->getCmd ( 'tmpl' ) === 'component') {
			return;
		}
		// Reset document
		JFactory::$document = null;
		
		// Not enabled feature
		if(!$this->cParams->get('enable_cookie_consent', 1)) {
			return;
		}

		// Validate execution for this component
		if(!$this->validateExecution('exclude_cookie_consent')) {
			return;
		}

		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_cookie')) {
			return;
		}

		// If the cookie is already set and the revocable mode is not enabled just skip
		if($this->app->input->cookie->get('cookieconsent_status') && !$this->cParams->get('revokable', 1)) {
			return;
		}
		
		$cookieCategories = false;
		if(	$this->cParams->get('compliance_type', 'opt-in') != 'info' &&
		   ((int)$this->cParams->get('cookie_category1_enable', 0) ||
			(int)$this->cParams->get('cookie_category2_enable', 0) ||
			(int)$this->cParams->get('cookie_category3_enable', 0) ||
			(int)$this->cParams->get('cookie_category4_enable', 0))) {
				$cookieCategories = true;
		}

		if($this->cParams->get('block_external_cookies_domains', 0) || $cookieCategories) {
			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-in' || $this->cParams->get('compliance_type', 'opt-in') == 'opt-out') {
				$dispatcher = JEventDispatcher::getInstance();
				
				// Kill the Joomla page cache
				$pluginClassName = 'PlgSystemCache';
				// Manage plugins exclusions at a early stage in the Joomla CMS app execution lifecycle
				if(class_exists($pluginClassName)) {
					// Get plugin observer object based on type/name
					$plugin = JPluginHelper::getPlugin('system', 'cache');
					// Instantiate the observer object and inject the subject for the attach
					$pluginInstanceToExclude = new $pluginClassName($dispatcher, (array) $plugin);
					// Now search and detach it
					$dispatcher->detach($pluginInstanceToExclude);
				}
				
				// Kill the JotCache
				$pluginClassName = 'plgSystemJotCache';
				// Manage plugins exclusions at a early stage in the Joomla CMS app execution lifecycle
				if(class_exists($pluginClassName)) {
					// Get plugin observer object based on type/name
					$plugin = JPluginHelper::getPlugin('system', 'jotcache');
					// Instantiate the observer object and inject the subject for the attach
					$pluginInstanceToExclude = new $pluginClassName($dispatcher, (array) $plugin);
					// Now search and detach it
					$dispatcher->detach($pluginInstanceToExclude);
				}
			}
		}
	}
	
	/**
	 * Method to be called everytime a head section has to be compiled and manipulated
	 *
	 * @return void
	 */
	public function onBeforeCompileHead() {
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			return;
		}
		
		// Output JS APP nel Document
		$document = JFactory::getDocument();
		if($document->getType() !== 'html' || $this->app->input->getCmd ( 'tmpl' ) === 'component') {
			return;
		}
		
		// Scripts loading
		$jQueryInclusion = $this->cParams->get ( 'jquery_include', true );
			
		if ($jQueryInclusion) {
			if (version_compare ( JVERSION, '3.0', '>=' )) {
				JHtml::_ ( 'jquery.framework' );
			} else {
				$document->addScript ( JUri::root ( true ) . '/plugins/system/gdpr/assets/js/jquery.js' );
			}
		}
		
		if ($this->cParams->get ( 'jquery_noconflict', 0 )) {
			$document->addScript ( JUri::root ( true ) . '/plugins/system/gdpr/assets/js/jquery.noconflict.js' );
		}
		
		// Not enabled feature
		if(!$this->cParams->get('enable_cookie_consent', 1)) {
			return;
		}
		
		// Validate execution for this component
		if(!$this->validateExecution('exclude_cookie_consent')) {
			return;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_cookie')) {
			return;
		}
		
		// Ensure that the website is not offline, otherwise allows the app and document rendering by Joomla to complete
		if ($this->app->get('offline') && !JFactory::getUser()->authorise('core.login.offline')) {
			return false;
		}
		
		// Special container selector override for the modal center position, always outside the body + blurred modal if blocked status
		if($this->cParams->get('position', 'bottom') == 'center' && $this->cParams->get('open_always_declined', 1)) {
			$this->cParams->set('container_selector', 'html');
			$blurredEffect = 'body{filter:brightness(50%) blur(5px);}div.fancybox-container.fancybox-is-open{z-index:99999999}';
			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-in' ) {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if(!$cookieConsentComplianceCookie || $cookieConsentComplianceCookie == 'deny') {
					if($this->cParams->get('position_center_blur_effect', '1')) {
						$document->addStyleDeclaration($blurredEffect);
					}
				}
			}
			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-out') {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if($cookieConsentComplianceCookie == 'deny') {
					if($this->cParams->get('position_center_blur_effect', '1')) {
						$document->addStyleDeclaration($blurredEffect);
					}
				}
			}
		}
		
		// Override the toolbar font size and padding
		$document->addStyleDeclaration('div.cc-window, span.cc-cookie-settings-toggler{font-size:' . $this->cParams->get('popup_fontsize', 16) . 'px}');
		$document->addStyleDeclaration('div.cc-revoke{font-size:' . $this->cParams->get('revocable_button_fontsize', 16) . 'px}');
		$document->addStyleDeclaration('div.cc-settings-label,span.cc-cookie-settings-toggle{font-size:' . intval($this->cParams->get('popup_fontsize', 16) - 2) . 'px}');
		$document->addStyleDeclaration('div.cc-window.cc-banner{padding:' . $this->cParams->get('popup_padding', '1') . 'em 1.8em}');
		$document->addStyleDeclaration('div.cc-window.cc-floating{padding:' . floatval($this->cParams->get('popup_padding', '1') * 2) . 'em 1.8em}');
		
		$document->addStyleSheet(JUri::root(true) . '/plugins/system/gdpr/assets/css/cookieconsent.min.css');
		$this->configurationOptionsScript = "var gdprConfigurationOptions = { complianceType: '" . $this->cParams->get('compliance_type', 'opt-in') . "',
																			  disableFirstReload: " . $this->cParams->get('disable_first_reload', 0)  . ",
																	  		  blockJoomlaSessionCookie: " . $this->cParams->get('block_joomla_session_cookie', 1)  . ",
																			  blockExternalCookiesDomains: " . $this->cParams->get('block_external_cookies_domains', 0)  . ",
																			  allowedCookies: '" . addcslashes($this->cParams->get('allow_local_cookies', ''), "'") . "',
																			  blockCookieDefine: " . $this->cParams->get('block_cookie_define', 1)  . ",
																			  autoAcceptOnNextPage: " . $this->cParams->get('auto_accept_on_next_page', 0)  . ",
																			  revokable: " . $this->cParams->get('revokable', 1) . ",
																			  lawByCountry: " . $this->cParams->get('lawbycountry', 0) . ",
																			  checkboxLawByCountry: " . $this->cParams->get('checkboxlawbycountry', 0) . ",
																			  dismissOnScroll: " . (int)$this->cParams->get('dismiss_onscroll', 0) . ",
																			  dismissOnTimeout: " . (int)$this->cParams->get('dismiss_ontimeout', 0) . ",
																			  containerSelector: '" . addcslashes($this->cParams->get('container_selector', 'body'), "'") . "',
																			  hideOnMobileDevices: " . (int)$this->cParams->get('hide_on_mobile_devices', 0) . ",
																			  defaultClosedToolbar: " . (int)$this->cParams->get('default_closed_toolbar', 0) . ",
																			  toolbarLayout: '" . $this->cParams->get('layout', 'basic') . "',
																			  toolbarTheme: '" . $this->cParams->get('theme', 'block') . "',
																			  revocableToolbarTheme: '" . $this->cParams->get('revocabletheme', 'basic') . "',
																			  toolbarPosition: '" . $this->cParams->get('position', 'bottom') . "',
																			  revokePosition: '" . $this->cParams->get('revokeposition', 'revoke-top') . "',
																			  toolbarPositionmentType: " . $this->cParams->get('positionment_type', 1) . ",
																			  popupBackground: '" . $this->cParams->get('popup_background', '#000000') . "',
																			  popupText: '" . $this->cParams->get('popup_text', '#FFFFFF') . "',
																			  popupLink: '" . $this->cParams->get('popup_link', '#FFFFFF') . "',
																			  buttonBackground: '" . $this->cParams->get('button_background', '#FFFFFF') . "',
																			  buttonBorder: '" . $this->cParams->get('button_border', '#FFFFFF') . "',
																			  buttonText: '" . $this->cParams->get('button_text', '#000000') . "',
																			  highlightOpacity: '" . $this->cParams->get('popup_background_opacity', '100') . "',
																			  highlightBackground: '" . $this->cParams->get('highlight_background', '#333333') . "',
																			  highlightBorder: '" . $this->cParams->get('highlight_border', '#FFFFFF') . "',
																			  highlightText: '" . $this->cParams->get('highlight_text', '#FFFFFF') . "',
																			  highlightDismissBackground: '" . $this->cParams->get('highlight_dismiss_background', '#333333') . "',
																		  	  highlightDismissBorder: '" . $this->cParams->get('highlight_dismiss_border', '#FFFFFF') . "',
																		 	  highlightDismissText: '" . $this->cParams->get('highlight_dismiss_text', '#FFFFFF') . "',
																			  hideRevokableButton: " . $this->cParams->get('hide_revokable_button', 0) . ",
																			  hideRevokableButtonOnscroll: " . $this->cParams->get('hide_revokable_button_onscroll', 0) . ",
																			  customRevokableButton: " . $this->cParams->get('custom_revokable_button', 0) . ",
																			  headerText: '" . str_replace(array("\r\n", "\n", "\r"), ' ', JText::_($this->cParams->get('header', 'Cookies used on the website!'), true)) . "',
																			  messageText: '" . trim(str_replace(array("\r\n", "\n", "\r", "<p>", "</p>"), ' ', JText::_($this->cParams->get('message', 'This website uses cookies to ensure you get the best experience on our website.'), true))) . "',
																			  denyMessageEnabled: " . $this->cParams->get('deny_message_enabled', 0) . ", 
																			  denyMessage: '" . trim(str_replace(array("\r\n", "\n", "\r", "<p>", "</p>"), ' ', JText::_($this->cParams->get('deny_message', 'You have declined cookies, to ensure the best experience on this website please consent the cookie usage.'), true))) . "',
																			  placeholderBlockedResources: " . $this->cParams->get('placeholder_blocked_resources', 0) . ", 
																	  		  placeholderBlockedResourcesText: '" . JText::_($this->cParams->get('placeholder_blocked_resources_text', 'You must accept cookies and reload the page to view this content'), true) . "',
																			  dismissText: '" . JText::_($this->cParams->get('dismiss_text', 'Got it!'), true) . "',
																			  allowText: '" . JText::_($this->cParams->get('allow_text', 'Allow cookies'), true) . "',
																			  denyText: '" . JText::_($this->cParams->get('deny_text', 'Decline'), true) . "',
																			  cookiePolicyLinkText: '" . JText::_($this->cParams->get('cookie_policy_link_text', ''), true) . "',
																			  cookiePolicyLink: '" . JText::_($this->cParams->get('cookie_policy_link', 'javascript:void(0)'), true) . "',
																			  cookiePolicyRevocableTabText: '" . JText::_($this->cParams->get('cookie_policy_revocable_tab_text', 'Cookie policy'), true) . "',
																			  privacyPolicyLinkText: '" . JText::_($this->cParams->get('privacy_policy_link_text', ''), true) . "',
																			  privacyPolicyLink: '" . JText::_($this->cParams->get('privacy_policy_link', 'javascript:void(0)'), true) . "',
																			  toggleCookieSettings: " . (int)$this->cParams->get('toggle_cookie_settings', 0) . ",
																	  		  toggleCookieSettingsText: '<span class=\"cc-cookie-settings-toggle\">" . JText::_($this->cParams->get('toggle_cookie_settings_text', 'Settings'), true) . " <span class=\"cc-cookie-settings-toggler\">&#x25EE;</span></span>',
																			  showLinks: " . (int)$this->cParams->get('show_links', 1) . ",
																			  blankLinks: '" . $this->cParams->get('blank_links', '_blank') . "',
																			  autoOpenPrivacyPolicy: " . (int)$this->cParams->get('auto_open_privacy_policy', 0) . ",
																			  openAlwaysDeclined: " . (int)$this->cParams->get('open_always_declined', 1) . ",
																			  cookieSettingsLabel: '" . JText::_($this->cParams->get('cookie_settings_label', 'Cookie settings:'), true) . "',
															  				  cookieSettingsDesc: '" . JText::_($this->cParams->get('cookie_settings_desc', 'Choose which kind of cookies you want to disable by clicking on the checkboxes. Click on a category name for more informations about used cookies.'), true) . "',
																			  cookieCategory1Enable: " . (int)$this->cParams->get('cookie_category1_enable', 0) . ",
																			  cookieCategory1Name: '" . JText::_($this->cParams->get('cookie_category1_name', 'Necessary'), true) . "',
																			  cookieCategory1Locked: " . (int)$this->cParams->get('cookie_category1_locked', 0) . ",
																			  cookieCategory2Enable: " . (int)$this->cParams->get('cookie_category2_enable', 0) . ",
																			  cookieCategory2Name: '" . JText::_($this->cParams->get('cookie_category2_name', 'Preferences'), true) . "',
																			  cookieCategory2Locked: " . (int)$this->cParams->get('cookie_category2_locked', 0) . ",
																			  cookieCategory3Enable: " . (int)$this->cParams->get('cookie_category3_enable', 0) . ",
																			  cookieCategory3Name: '" . JText::_($this->cParams->get('cookie_category3_name', 'Statistics'), true) . "',
																			  cookieCategory3Locked: " . (int)$this->cParams->get('cookie_category3_locked', 0) . ",
																			  cookieCategory4Enable: " . (int)$this->cParams->get('cookie_category4_enable', 0) . ",
																			  cookieCategory4Name: '" . JText::_($this->cParams->get('cookie_category4_name', 'Marketing'), true) . "',
																			  cookieCategory4Locked: " . (int)$this->cParams->get('cookie_category4_locked', 0) . ",
																			  alwaysReloadAfterCategoriesChange: " . (int)$this->cParams->get('always_reload_after_categories_change', 0) . ",
																			  debugMode: " . (int)$this->cParams->get('debug', 0) . "
																		};";
		$document->addScriptDeclaration($this->configurationOptionsScript);
		$document->addScriptDeclaration("var gdpr_ajax_livesite='" . JUri::base() . "';");
		$document->addScriptDeclaration("var gdpr_enable_log_cookie_consent=" . (int)$this->cParams->get('enable_log_cookie_consent', 1) . ";");
		
		// Load ajax endpoint for cookie categories
		$cookieCategories = false;
		if(	$this->cParams->get('compliance_type', 'opt-in') != 'info' &&
		   ((int)$this->cParams->get('cookie_category1_enable', 0) ||
			(int)$this->cParams->get('cookie_category2_enable', 0) ||
			(int)$this->cParams->get('cookie_category3_enable', 0) ||
			(int)$this->cParams->get('cookie_category4_enable', 0))) {
				$document->addScriptDeclaration("var gdprUseCookieCategories=1;");
				$document->addScriptDeclaration("var gdpr_ajaxendpoint_cookie_category_desc='" . JUri::base() . "index.php?option=com_gdpr&task=user.getCookieCategoryDescription&format=raw';");
				$cookieConsentComplianceCookie = true;
				$cookieCategories = true;
				$this->hasCookieCategory = true;

				$session = $this->app->getSession();
				if($session->get('gdpr_cookie_category_disabled_1', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_1', 0) && !$this->cParams->get('cookie_category1_checked', 1))) {
					$document->addScriptDeclaration("var gdprCookieCategoryDisabled1=1;");
					if(!$session->get('gdpr_cookie_category_disabled_1', 0) && !$this->cParams->get('cookie_category1_checked', 1)) {
						$session->set('gdpr_cookie_category_disabled_1', 1);
					}
				}
				if($session->get('gdpr_cookie_category_disabled_2', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_2', 0) && !$this->cParams->get('cookie_category2_checked', 1))) {
					$document->addScriptDeclaration("var gdprCookieCategoryDisabled2=1;");
					if(!$session->get('gdpr_cookie_category_disabled_2', 0) && !$this->cParams->get('cookie_category2_checked', 1)) {
						$session->set('gdpr_cookie_category_disabled_2', 1);
					}
				}
				if($session->get('gdpr_cookie_category_disabled_3', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_3', 0) && !$this->cParams->get('cookie_category3_checked', 0))) {
					$document->addScriptDeclaration("var gdprCookieCategoryDisabled3=1;");
					if(!$session->get('gdpr_cookie_category_disabled_3', 0) && !$this->cParams->get('cookie_category3_checked', 0)) {
						$session->set('gdpr_cookie_category_disabled_3', 1);
					}
				}
				if($session->get('gdpr_cookie_category_disabled_4', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_4', 0) && !$this->cParams->get('cookie_category4_checked', 0))) {
					$document->addScriptDeclaration("var gdprCookieCategoryDisabled4=1;");
					if(!$session->get('gdpr_cookie_category_disabled_4', 0) && !$this->cParams->get('cookie_category4_checked', 0)) {
						$session->set('gdpr_cookie_category_disabled_4', 1);
					}
				}
				
				if($this->hasCookieCategory) {
					$document->addScriptDeclaration("var gdprJSessCook='" . $this->app->getSession()->getName() . "';");
					$document->addScriptDeclaration("var gdprJSessVal='" . $this->app->getSession()->getId() . "';");
					$document->addScriptDeclaration("var gdprJAdminSessCook='" . md5(JApplicationHelper::getHash('administrator')) . "';");
					$document->addScriptDeclaration("var gdprPropagateCategoriesSession=" . (int)$this->cParams->get('propagate_categories_session', 1) . ";");
				}
			}

		if($this->cParams->get('use_fancybox_links', 0) || $cookieCategories) {
			$this->loadComponentLanguage();
			$document->addStyleSheet(JUri::root(true) . '/plugins/system/gdpr/assets/css/jquery.fancybox.min.css');
			$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/jquery.fancybox.min.js', 'text/javascript', true);
			$document->addScriptDeclaration("var gdprFancyboxWidth=" . (int)$this->cParams->get('fancybox_width', 700) . ";");
			$document->addScriptDeclaration("var gdprFancyboxHeight=" . (int)$this->cParams->get('fancybox_height', 800) . ";");
			$document->addScriptDeclaration("var gdprCloseText='" . JText::_('COM_GDPR_CLOSE_POPUP_TEXT', true) . "';");
		}

		// Load popup
		if($this->cParams->get('use_fancybox_links', 0)) {
			$formatPopup = $this->cParams->get('popup_format_template', 1) ? 'tmpl=component' : 'format=raw';
			$document->addScriptDeclaration("var gdprUseFancyboxLinks=1;");
			if($this->cParams->get('use_cookie_policy_contents', 0)) {
				$document->addScriptDeclaration("var gdpr_ajaxendpoint_cookie_policy='" . JUri::base() . "index.php?option=com_gdpr&task=user.getCookiePolicy&$formatPopup';");
			}
			if($this->cParams->get('use_privacy_policy_contents', 0)) {
				$document->addScriptDeclaration("var gdpr_ajaxendpoint_privacy_policy='" . JUri::base() . "index.php?option=com_gdpr&task=user.getPrivacyPolicy&$formatPopup';");
			}
		}
		
		// Add custom styles if any
		if($customCssStyles = trim($this->cParams->get('custom_css_styles', 0))) {
			$document->addStyleDeclaration($customCssStyles);
		}
		
		// Load cookieconsent
		$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/cookieconsent.min.js', 'text/javascript', true);
		$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/init.js', 'text/javascript', true);
	}

	/**
	 * Application event after rendering
	 * Here is the place to manipulate the Joomla output and remove external resources
	 * Keep in mind that the ordering of execution of system plugins matters
	 *
	 * @access public
	 */
	public function onAfterRender() {
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			return;
		}
		
		// Output JS APP nel Document
		$document = JFactory::getDocument();
		if($document->getType() !== 'html' || $this->app->input->getCmd ( 'tmpl' ) === 'component') {
			return;
		}
		
		// Not enabled feature
		if(!$this->cParams->get('enable_cookie_consent', 1)) {
			return;
		}
		
		// Validate execution for this component
		if(!$this->validateExecution('exclude_cookie_consent')) {
			return;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_cookie')) {
			return;
		}

		// Block external cookies by domain
		if($this->cParams->get('block_external_cookies_domains', 0)) {
			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-in' ) {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if(!$cookieConsentComplianceCookie || $cookieConsentComplianceCookie == 'deny') {
					$this->killExternalResources();
				}
			}

			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-out') {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if($cookieConsentComplianceCookie == 'deny') {
					$this->killExternalResources();
				}
			}
		}
		
		// Block local cookies by name
		if($this->cParams->get('block_local_cookies_server_side', 0)) {
			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-in' ) {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if(!$cookieConsentComplianceCookie || $cookieConsentComplianceCookie == 'deny') {
					$this->killLocalCookies();
				}
			}

			if($this->cParams->get('compliance_type', 'opt-in') == 'opt-out') {
				$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
				if($cookieConsentComplianceCookie == 'deny') {
					$this->killLocalCookies();
				}
			}
		}
		
		// Block cookies by category if blocking compliance type and there is a 'deny all', an explicit category disable or an implicit category disabled
		$cookieConsentStatus = $this->app->input->cookie->get('cookieconsent_status');
		if($this->cParams->get('compliance_type', 'opt-in') != 'info') {
			$session = $this->app->getSession();
			if(	(int)$this->cParams->get('cookie_category1_enable', 0) && ($cookieConsentStatus == 'deny' || $session->get('gdpr_cookie_category_disabled_1', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_1', 0) && !$this->cParams->get('cookie_category1_checked', 1)))) {
				$cookieCategory1List = trim($this->cParams->get('cookie_category1_list'));
				if($cookieCategory1List) {
					$this->killLocalCookies($cookieCategory1List);
				}
				$domainsCategory1List = trim($this->cParams->get('domains_category1_list'));
				if($domainsCategory1List) {
					$this->killExternalResources($domainsCategory1List);
				}
			}
			if(	(int)$this->cParams->get('cookie_category2_enable', 0) && ($cookieConsentStatus == 'deny' || $session->get('gdpr_cookie_category_disabled_2', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_2', 0) && !$this->cParams->get('cookie_category2_checked', 1)))) {
				$cookieCategory2List = trim($this->cParams->get('cookie_category2_list'));
				if($cookieCategory2List) {
					$this->killLocalCookies($cookieCategory2List);
				}
				$domainsCategory2List = trim($this->cParams->get('domains_category2_list'));
				if($domainsCategory2List) {
					$this->killExternalResources($domainsCategory2List);
				}
			}
			if(	(int)$this->cParams->get('cookie_category3_enable', 0) && ($cookieConsentStatus == 'deny' || $session->get('gdpr_cookie_category_disabled_3', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_3', 0) && !$this->cParams->get('cookie_category3_checked', 0)))) {
				$cookieCategory3List = trim($this->cParams->get('cookie_category3_list'));
				if($cookieCategory3List) {
					$this->killLocalCookies($cookieCategory3List);
				}
				$domainsCategory3List = trim($this->cParams->get('domains_category3_list'));
				if($domainsCategory3List) {
					$this->killExternalResources($domainsCategory3List);
				}
			}
			if(	(int)$this->cParams->get('cookie_category4_enable', 0) && ($cookieConsentStatus == 'deny' || $session->get('gdpr_cookie_category_disabled_4', 0) == 1 || (!$session->get('gdpr_cookie_category_disabled_4', 0) && !$this->cParams->get('cookie_category4_checked', 0)))) {
				$cookieCategory4List = trim($this->cParams->get('cookie_category4_list'));
				if($cookieCategory4List) {
					$this->killLocalCookies($cookieCategory4List);
				}
				$domainsCategory4List = trim($this->cParams->get('domains_category4_list'));
				if($domainsCategory4List) {
					$this->killExternalResources($domainsCategory4List);
				}
			}
			
			// Add JS var domain for unset categories cookies
			if($this->cParams->get('block_local_cookies_server_side', 0) && count($this->unsetCategoriesCookies)) {
				$body = $this->app->getBody ();
				// Replace buffered main view contents at the body end
				$body = preg_replace ( "/<\/head>/i", "<script>var gdpr_unset_categories_cookies=" . json_encode($this->unsetCategoriesCookies) . ";</script></head>", $body, 1 );
				// Set the new JResponse contents
				$this->app->setBody ( $body );
			}
		}
		
		// If JCHOptimize detected and enable noconflict JCH enabled
		if ($this->cParams->get('jch_noconflict', 1) && $jchPlugin = JPluginHelper::getPlugin('system', 'jch_optimize')) {
			$jchParams = json_decode($jchPlugin->params);
			if($jchParams->combine_files_enable){
				$this->addConfigToHead();
			}
		}
		// If scripts no conflict mode is enabled
		if ($this->cParams->get('scripts_noconflict', 0)) {
			$this->addConfigToHead();
		}
	}
	
	/** Add supports for custom component views, custom manifest and forms not triggering onContentPrepareForm event
	 *
	 * @access public
	 * @return void
	 */
	public function onAfterRoute() {
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			return;
		}
		
		$document = JFactory::getDocument();
		if($document->getType() !== 'html') {
			return;
		}
		
		// Manage the session cookie destroy if cookie consent is not accepted/enabled and the Joomla! session cookie is blocked
		if($this->validateExecution('exclude_cookie_consent') && !$this->checkExclusionPermissions('disallow_cookie') && !$this->app->get('offline')) {
			// Check if the Joomla! session cookie block option is enabled
			if($this->cParams->get('block_joomla_session_cookie', 1)) {
				$option = $this->app->input->get('option');
				$task = $this->app->input->get('task');
				$op2 = $this->app->input->get('op2');
				if($this->cParams->get('compliance_type', 'opt-in') == 'opt-in' ) {
					$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
					if(!$cookieConsentComplianceCookie || $cookieConsentComplianceCookie == 'deny') {
						if(( $option == 'com_users' && $task == 'user.login') ||
							($option == 'com_easysocial' && $task == 'login') ||
							($option == 'com_comprofiler' && $op2 == 'login') ||
							($option == 'com_kunena' && $task == 'login')) {
							$session = $this->app->getSession();
							$session->destroy();
							$this->app->redirect ( JRoute::_('index.php?gdprcookielogin=1') );
						}
					}
				}
				if($this->cParams->get('compliance_type', 'opt-in') == 'opt-out') {
					$cookieConsentComplianceCookie = $this->app->input->cookie->get('cookieconsent_status');
					if($cookieConsentComplianceCookie == 'deny') {
						if(( $option == 'com_users' && $task == 'user.login') ||
							($option == 'com_easysocial' && $task == 'login') ||
							($option == 'com_comprofiler' && $op2 == 'login') ||
							($option == 'com_kunena' && $task == 'login')) {
							$session = $this->app->getSession();
							$session->destroy();
							$this->app->redirect ( JRoute::_('index.php?gdprcookielogin=1') );
						}
					}
				}
			}
		}
	
		// Trigger simulate the onContentPrepareForm for third parties extensions not using JForm
		// Get the dispatched option and view
		$option = $this->app->input->get('option');
		$view = $this->app->input->get('view');
	
		// Fallback for old extension using a task mapping
		if(!$view && $this->app->input->get('task')) {
			$view = $this->app->input->get('task');
		}
	
		// Fallback for old extension using a func mapping
		if(!$view && $this->app->input->get('func')) {
			$view = $this->app->input->get('func');
		}
		
		// Fallback for old extension using a ctrl mapping
		if(!$view && $this->app->input->get('ctrl')) {
			$view = $this->app->input->get('ctrl');
		}
		
		// Fallback to the whole component
		if(!$view) {
			$view = '*';
		}
		
		$dispatchedFirm = $option . '.' . $view;

		// If debug mode is enabled show the form name for configuration purpouse
		if($this->cParams->get('debug', 0) && $document->getType() === 'html') {
			echo '<label style="font-size:14px;background-color:#8d0000;color:#FFF;border-radius:5px;padding:10px;display:inline-block;margin:2px"><span style="font-size:16px;font-weight:bold">Component.View: </span>' . $dispatchedFirm . '</label>';
		}

		// Support for custom component forms
		$customComponentsUserprofileButtonsArray = array();
		if($customComponentsViewUserprofileButtons = trim($this->cParams->get('custom_components_view_userprofile_buttons', null))) {
			$customComponentsUserprofileButtons = trim($this->cParams->get('custom_components_userprofile_buttons'), null);
			$customComponentsUserprofileButtons .= PHP_EOL . $customComponentsViewUserprofileButtons;
			$this->cParams->set('custom_components_userprofile_buttons', $customComponentsUserprofileButtons);
				
			$customComponentsUserprofileButtonsArray = explode(PHP_EOL, trim($this->cParams->get('custom_components_userprofile_buttons'), null));
			if(!empty($customComponentsUserprofileButtonsArray)) {
				foreach ($customComponentsUserprofileButtonsArray as &$customComponentUserprofileButtonsArray) {
					$customComponentUserprofileButtonsArray = trim($customComponentUserprofileButtonsArray);
				}
			}
		}
	
		$customComponentsFormCheckboxArray = array();
		if($customComponentsViewFormCheckbox = trim($this->cParams->get('custom_components_view_form_checkbox', null))) {
			$customComponentsFormCheckbox = trim($this->cParams->get('custom_components_form_checkbox'), null);
			$customComponentsFormCheckbox .= PHP_EOL . $customComponentsViewFormCheckbox;
			$this->cParams->set('custom_components_form_checkbox', $customComponentsFormCheckbox);
				
			$customComponentsFormCheckboxArray = explode(PHP_EOL, trim($this->cParams->get('custom_components_form_checkbox'), null));
			if(!empty($customComponentsFormCheckboxArray)) {
				foreach ($customComponentsFormCheckboxArray as &$customComponentFormCheckboxArray) {
					$customComponentFormCheckboxArray = trim($customComponentFormCheckboxArray);
				}
			}
		}
	
		// Check for custom third parties integrations manifest
		if($tpdIntegrations = $this->cParams->get('3pdintegration', array())) {
			foreach ($tpdIntegrations as $integratedExtension) {
				$manifest = $this->loadManifest($integratedExtension);
				if($manifest && is_object($manifest)) {
					// Inject the custom component view name for user profile buttons
					if(isset($manifest->custom_components_userprofile_buttons)) {
						if(is_array($manifest->custom_components_userprofile_buttons)) {
							$customComponentsUserprofileButtonsArray = array_merge ($customComponentsUserprofileButtonsArray, $manifest->custom_components_userprofile_buttons);
						} else {
							$customComponentsUserprofileButtonsArray[] = trim($manifest->custom_components_userprofile_buttons);
						}
					}
					// Inject the custom component form selector for user profile buttons
					if(isset($manifest->custom_components_view_userprofile_buttons_selector)) {
						$customComponentsViewUserprofileButtonsSelector = trim($this->cParams->get('custom_components_view_userprofile_buttons_selector'), null);
						if($customComponentsViewUserprofileButtonsSelector) {
							$customComponentsViewUserprofileButtonsSelector .= ',' . $manifest->custom_components_view_userprofile_buttons_selector;
						} else {
							$customComponentsViewUserprofileButtonsSelector = $manifest->custom_components_view_userprofile_buttons_selector;
						}
						$this->cParams->set('custom_components_view_userprofile_buttons_selector', $customComponentsViewUserprofileButtonsSelector);
					}
						
					// Inject the custom component view name for privacy checkbox
					if(isset($manifest->custom_components_form_checkbox)) {
						if(is_array($manifest->custom_components_form_checkbox)) {
							$customComponentsFormCheckboxArray = array_merge ($customComponentsFormCheckboxArray, $manifest->custom_components_form_checkbox);
						} else {
							$customComponentsFormCheckboxArray[] = trim($manifest->custom_components_form_checkbox);
						}
					}
					// Inject the custom component form selector or privacy checkbox
					if(isset($manifest->custom_components_view_form_checkbox_selector)) {
						$customComponentsViewFormCheckboxSelector = trim($this->cParams->get('custom_components_view_form_checkbox_selector'), null);
						if($customComponentsViewFormCheckboxSelector) {
							$customComponentsViewFormCheckboxSelector .= ',' . $manifest->custom_components_view_form_checkbox_selector;
						} else {
							$customComponentsViewFormCheckboxSelector = $manifest->custom_components_view_form_checkbox_selector;
						}
						$this->cParams->set('custom_components_view_form_checkbox_selector', $customComponentsViewFormCheckboxSelector);
					}
				}
			}
		}
	
		if(in_array($dispatchedFirm, $customComponentsUserprofileButtonsArray) || in_array($dispatchedFirm, $customComponentsFormCheckboxArray)) {
			$dummyForm = new JForm($dispatchedFirm);
			$dummyForm->load('<config><params></params></config>');
			$currentCustomComponentsFormCheckbox = $this->cParams->get('custom_components_form_checkbox', null);
			$currentCustomComponentsFormCheckbox .= PHP_EOL . $dispatchedFirm;
			$this->cParams->set('custom_components_form_checkbox', $currentCustomComponentsFormCheckbox);
			$this->onContentPrepareForm($dummyForm, array());
		}
		
		/**
		 * Set the users component in order to allow the username to be editable
		 */
		if($this->cParams->get('override_change_login_name', 1)) {
			$userCParams = JComponentHelper::getParams('com_users');
			$userCParams->set('change_login_name', 1);
		}
		
		/**
		 * Evaluate if the user has revoked the privacy policy, in such case force a redirect to the user edit screen
		 * 1- The user must me logged in
		 * 2- The parameter must be enabled
		 * 3- The user profile value must be 0 = revoked
		 */
		$userId = JFactory::getUser()->id;
		if($this->cParams->get('block_privacypolicy', 0) && 
		   $this->cParams->get('privacy_policy_checkbox', 1) && 
		   $this->app->input->get('option') != 'com_users' &&
		   $this->app->input->get('option') != 'com_comprofiler' &&
		   $this->app->input->get('option') != 'com_gdpr' &&
		   !$this->app->input->get('tmpl') == 'component' &&
		   (int)$userId > 0 &&
		   $this->validateExecution('exclude_privacycheckbox') &&
		   !$this->checkExclusionPermissions('disallow_privacypolicy')) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
						->select($db->quoteName('profile_value'))
						->from($db->quoteName('#__user_profiles'))
						->where($db->quoteName('user_id') . ' = ' . (int) $userId)
						->where($db->quoteName('profile_key') . ' = ' . $db->quote('gdpr_consent_status'));
			$db->setQuery($query);
			$privacyPolicyConsent = $db->loadResult();
			
			if ((int)$privacyPolicyConsent == 0) {
				$this->loadComponentLanguage();
				$this->app->enqueueMessage(JText::_('COM_GDPR_REQUIRED_TOACCEPT_PRIVACY_POLICY'), 'notice');
				
				$query = $db->getQuery(true)
							->select($db->quoteName('requireReset'))
							->from($db->quoteName('#__users'))
							->where($db->quoteName('id') . ' = ' . (int) $userId);
				$db->setQuery($query);
				$requirePasswordReset = $db->loadResult();
				if($requirePasswordReset) {
					$this->app->enqueueMessage(JText::_('JGLOBAL_PASSWORD_RESET_REQUIRED'), 'notice');
				}
				
				// Core component or integration with CB
				if(in_array('cbuilder', $this->cParams->get('3pdintegration', array()))) {
					$this->app->redirect(JRoute::_('index.php?option=com_comprofiler&task=userDetails&gdprprivacyrequest=1', false));
				} else {
					$this->app->redirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&gdprprivacyrequest=1', false));
				}
			}
		}
		
		// Add a message for the user failed login because of cookies declined
		if($this->app->input->get('gdprcookielogin', 0) && !$userId) {
			$this->loadComponentLanguage();
			$this->app->enqueueMessage(JText::_('COM_GDPR_REQUIRED_TOACCEPT_COOKIES_TOLOGIN'), 'notice');
		}
	}
	
	/**
	 * onAfterInitialise handler
	 * Check if some dynamic checkbox are enabled and the app must be injected and started
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterDispatch() {
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			return;
		}
		
		// Output JS APP nel Document
		$document = JFactory::getDocument();
		if($document->getType() !== 'html') {
			return;
		}
		
		if($this->app->input->get('fancybox')) {
			return;
		}
		
		if($this->cParams->get('disable_dynamic_checkbox', 0)) {
			return;
		}
		
		// Check if any of the dynamic checkbox is enabled, if so inject script and start JS app
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->select('placeholder');
		$query->from($db->quoteName('#__gdpr_checkbox'));
		$results = $db->setQuery($query)->loadObjectList('placeholder');
		
		// Found active dynamic checkbox
		if(count($results)) {
			$this->loadComponentLanguage();
			
			//load the translation
			require_once JPATH_ROOT . '/administrator/components/com_gdpr/framework/helpers/language.php';
			$gdprLanguage = GdprHelpersLanguage::getInstance();
			$translations = array(
					'COM_GDPR_DYNAMIC_PRIVACY_POLICY_ACCEPT',
					'COM_GDPR_DYNAMIC_PRIVACY_POLICY_NOACCEPT'
			);
			$gdprLanguage->injectJsTranslations($translations, $document);
			
			$document->addStyleSheet(JUri::root(true) . '/plugins/system/gdpr/assets/css/jquery.fancybox.min.css');
			$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/jquery.fancybox.min.js', 'text/javascript', true);
			$document->addScriptDeclaration("var gdpr_livesite='" . JUri::base() . "';");
			$document->addScriptDeclaration("var gdprDynamicFancyboxWidth=" . (int)$this->cParams->get('fancybox_checkbox_width', 700) . ";");
			$document->addScriptDeclaration("var gdprDynamicFancyboxHeight=" . (int)$this->cParams->get('fancybox_checkbox_height', 800) . ";");
			$document->addScriptDeclaration("var gdprDynamicFancyboxCloseText='" . JText::_('COM_GDPR_CLOSE_POPUP_TEXT', true) . "';");
			$document->addScriptDeclaration("var gdprDynamicCheckboxRequiredText='" . JText::_('COM_GDPR_PRIVACY_POLICY_REQUIRED', true) . "';");
			$document->addScriptDeclaration("var gdprDynamicCheckboxArray='" . json_encode(array_keys($results)) . "';");
			$document->addScriptDeclaration("var gdprDynamicCheckboxOrder = '" . $this->cParams->get('privacy_policy_checkbox_order', 'right') . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyDynamicCheckboxContainerTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_container', "<div class='control-group'>{field}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyDynamicCheckboxLabelTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_label', "<div class='control-label' style='display:inline-block'>{label}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyDynamicCheckboxCheckboxTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_controls', "<div class='controls' style='display:inline-block;margin-left:20px'>{checkbox}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprDynamicCheckboxRemoveAttributes = " . (int)$this->cParams->get('remove_attributes', 1)  . ";");
			$document->addScriptDeclaration("var gdprDynamicForceSubmitButton = " . (int)$this->cParams->get('force_submit_button', 0)  . ";");
			$document->addScriptDeclaration("var gdprDynamicRemoveSubmitButtonEvents = " . (int)$this->cParams->get('remove_submit_button_events', 0)  . ";");
			$document->addScriptDeclaration("var gdprDynamicCheckboxCustomSubmissionMethodSelector = '" . addcslashes($this->cParams->get('custom_submission_method_selectors', 'input[type=submit],button[type=submit],button[type=button]'), "'") . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyDynamicControl = " . (int)$this->cParams->get('use_dynamic_checkbox', 1)  . ";");
			$document->addScriptDeclaration("var gdprDynamicCheckboxControlsClass = " . (int)$this->cParams->get('checkbox_controls_class', 0)  . ";");
			$document->addScriptDeclaration("var gdprDynamicCheckboxControlsClassList = '" . addcslashes(trim($this->cParams->get('checkbox_controls_class_list', 'required'), ' '), "'") . "';");

			$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/checkbox.js', 'text/javascript', true);
		}
	}
	
	/**
	 * Log for user changes, optionally validating privacy policy checkbox server side
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was successfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onUserBeforeSave($oldUser, $isnew, $newUser) {
		// Load component language
		$this->loadComponentLanguage();
		
		// Skip always a new user creation
		if($isnew) {
			if($this->app->isSite() && $this->cParams->get('privacypolicy_serverside_validation', 0)) {
				// Check if the privacy policy field is in the request and is not checked
				$privacyPolicy = $this->app->input->getInt('gdpr_privacy_policy_checkbox');
				// Exclusions only for checkbox - $this->cParams->set('privacy_policy_checkbox', 0)
				if(!$privacyPolicy &&
						$this->cParams->get('privacy_policy_checkbox', 1) &&
						$this->validateExecution('exclude_privacycheckbox') &&
						!$this->checkExclusionPermissions('disallow_privacypolicy')) {
							throw new InvalidArgumentException(JText::_('COM_GDPR_PRIVACY_POLICY_NOT_ACCEPTED'));
						}
			}
				
			return;
		}
		
		// Manage the revokable privacy policy status here
		$privacyPolicy = 1;
		if(!$isnew) {
			if($this->app->isSite() && !in_array($this->app->input->get('task'), array('activate', 'confirm', 'login')) && ($this->cParams->get('revokable_privacypolicy', 0) || $this->app->input->get('gdprprivacyrequest', 0) || isset($newUser['privacyconsent']))) {
				// Exclusions only for checkbox - $this->cParams->set('privacy_policy_checkbox', 0)
				if(($this->cParams->get('privacy_policy_checkbox', 1) || isset($newUser['privacyconsent'])) &&
				   in_array($this->app->input->get('option'), array('com_users', 'com_comprofiler')) &&
				   $this->validateExecution('exclude_privacycheckbox') &&
				   !$this->checkExclusionPermissions('disallow_privacypolicy')) {
						// Check if the privacy policy field is in the request and is not checked. Missing = no more accepted and to flag 0
						$privacyPolicy = $this->app->input->getInt('gdpr_privacy_policy_checkbox', 0);
						if(!isset($_REQUEST['gdpr_privacy_policy_checkbox']) && !$this->cParams->get('revokable_privacypolicy', 0) && isset($newUser['privacyconsent'])) {
							$privacyPolicy = $newUser['privacyconsent']['privacy'];
						}
						
						$db = JFactory::getDbo();
						$query = "UPDATE " . $db->quotename('#__user_profiles') . 
								 "\n SET " .  $db->quotename('profile_value') . " = " . (int)$privacyPolicy .
								 "\n WHERE " .  $db->quotename('profile_key') . " = " .  $db->quote('gdpr_consent_status') .
								 "\n AND " .  $db->quotename('user_id') . " = " .  (int)$newUser['id'];
						try {
							$db->setQuery($query)->execute();
						} catch(Exception $e) {
							// No errors during the create log record phase
						}
						
						// UPDATE the content of the already generated user note if any
						if($this->cParams->get('log_usernote_privacypolicy', 1)) {
							$noteBody = $privacyPolicy ? JText::sprintf('COM_GDPR_PRIVACY_UPDATED_BODY', $newUser['name'], $newUser['email'], JDate::getInstance()->toSql()) : 
														 JText::sprintf('COM_GDPR_PRIVACY_REVOKED_BODY', $newUser['name'], $newUser['email'], JDate::getInstance()->toSql());
							$query = "UPDATE " . $db->quotename('#__user_notes') .
									 "\n SET " .  $db->quotename('body') . " = " . $db->quote($noteBody) .
									 "\n WHERE " .  $db->quotename('user_id') . " = " . (int)$newUser['id'] .
									 "\n AND " .  $db->quotename('catid') . " = " .(int) $this->cParams->get('log_usernote_privacypolicy_category', 0) .
									 "\n AND " .  $db->quotename('subject') . " = " . $db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'));
							try {
								$db->setQuery($query)->execute();
							} catch(Exception $e) {
								// No errors during the create log record phase
							}
						}
						
						// Integration with Joomla 3.9+ Privacy tool suite
						if(version_compare(JVERSION, '3.9', '>=') && $this->cParams->get('log_userconsent_privacypolicy', 1)) {
							$privacyConsentBody = $privacyPolicy ? JText::sprintf('COM_GDPR_PRIVACY_GDPR_UPDATED_BODY', $newUser['name'], $newUser['email'], JDate::getInstance()->toSql()) :
														 		   JText::sprintf('COM_GDPR_PRIVACY_GDPR_REVOKED_BODY', $newUser['name'], $newUser['email'], JDate::getInstance()->toSql());
							
							// If log IP address
							if($this->cParams->get('log_user_ipaddress', 0)) {
								$privacyConsentBody .= JText::sprintf('COM_GDPR_PRIVACY_GDPR_BODY_IP_ADDRESS', $_SERVER['REMOTE_ADDR']);
							}
									 		   
							$query = "UPDATE " . $db->quotename('#__privacy_consents') .
									 "\n SET " .  $db->quotename('body') . " = " . $db->quote($privacyConsentBody) .
									 "\n WHERE " .  $db->quotename('user_id') . " = " . (int)$newUser['id'] .
									 "\n AND " .  $db->quotename('subject') . " = " . $db->quote('COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT') .
									 "\n AND " .  $db->quotename('state') . " = 1";
							try {
								$db->setQuery($query)->execute();
							} catch(Exception $e) {
								// No errors during the create log record phase
							}
						}
						
						/**
						 * Special handling for old users having never inserted records
						 */
						if($this->app->input->get('gdprprivacyrequest', 0) || isset($newUser['privacyconsent'])) {
							//Check if the user_profiles status exists
							$query = $db->getQuery(true)
										->select('1')
										->from($db->quoteName('#__user_profiles'))
										->where($db->quoteName('user_id') . ' = ' . (int) $newUser['id'])
										->where($db->quoteName('profile_key') . ' = ' . $db->quote('gdpr_consent_status'));
							$db->setQuery($query);
							$consent = $db->loadObjectList();
							if (!count($consent)) {
								// Add a profile key for the consent confirmation status
								$userProfileKey = (object) array(
										'user_id'		=> (int) $newUser['id'],
										'profile_key'	=> 'gdpr_consent_status',
										'profile_value'	=> (int)$privacyPolicy
								);
								try {
									$db->insertObject('#__user_profiles', $userProfileKey);
								}
								catch (Exception $e) {
									// No errors during the create log record phase
								}
							}
							
							//Check if the user_note exists
							if($this->cParams->get('log_usernote_privacypolicy', 1)) {
								$query = $db->getQuery(true)
											->select('1')
											->from($db->quoteName('#__user_notes'))
											->where($db->quoteName('user_id') . ' = ' . (int) $newUser['id'])
											->where($db->quoteName('catid') . ' = ' . (int)$this->cParams->get('log_usernote_privacypolicy_category', 0))
											->where($db->quoteName('subject') . ' = ' . $db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT')));
								$db->setQuery($query);
								$userConsentNote = $db->loadObjectList();
								if (!count($userConsentNote)) {
									$userNote = (object) array(
											'user_id'         => (int) $newUser['id'],
											'catid'           => $this->cParams->get('log_usernote_privacypolicy_category', 0),
											'subject'         => JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'),
											'body'            => $noteBody,
											'state'           => 1,
											'created_user_id' => (int) $newUser['id'],
											'modified_user_id' => (int) $newUser['id'],
											'created_time'    => JDate::getInstance()->toSql()
									);
										
									try {
										$db->insertObject('#__user_notes', $userNote);
									} catch(Exception $e) {
										// No errors during the create log record phase
									}
								}
								
							}
							
							// Integration with Joomla 3.9+ Privacy tool suite
							if(version_compare(JVERSION, '3.9', '>=') && $this->cParams->get('log_userconsent_privacypolicy', 1)) {
								// Check and insert new record for never consented users on first login
								$userConsentRecord = array();
								$query = $db->getQuery(true)
											 ->select('1')
											 ->from($db->quoteName('#__privacy_consents'))
											 ->where($db->quoteName('user_id') . ' = ' . (int) $newUser['id'])
											 ->where($db->quoteName('subject') . ' = ' . $db->quote('COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT'))
											 ->where($db->quoteName('state') . ' = 1');
								try {
									$db->setQuery($query);
									$userConsentRecord = $db->loadObjectList();
								} catch (Exception $e) {
									// No errors during the create log record phase
								}
								if (!count($userConsentRecord)) {
									// Create the user note
									$userPrivacyConsent = (object) array(
											'user_id' => (int) $newUser['id'],
											'subject' => 'COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT',
											'body'    => $privacyConsentBody,
											'created' => JDate::getInstance()->toSql(),
											'state' => 1
									);
								
									try {
										$db->insertObject('#__privacy_consents', $userPrivacyConsent);
									} catch(Exception $e) {
										// No errors during the create log record phase
									}
								}
							}
						}
					}
			}
		}
		
		// Validate execution for this component
		if(!$this->validateExecution('exclude_logs')) {
			return;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_logs')) {
			return;
		}
		
		// Comparisons to find changes applied to this user
		$somethingChanged = false;
		$changes = array();
		$db = JFactory::getDbo();
		$editorUser = JFactory::getUser();
		
		// Check for change_name rule
		$changeName = 0;
		if($oldUser['name'] != $newUser['name']) {
			$changes['change_name'] = array('oldvalue' => $oldUser['name'], 'newvalue' => $newUser['name']);
			$changeName = 1;
			$somethingChanged = true;
		}
		
		// Check for change_username rule
		$changeUsername = 0;
		if($oldUser['username'] != $newUser['username']) {
			$changes['change_username'] = array('oldvalue' => $oldUser['username'], 'newvalue' => $newUser['username']);
			$changeUsername = 1;
			$somethingChanged = true;
		}
		
		// Check for change_password rule
		$changePassword = 0;
		if($newUser['password'] && $oldUser['password'] != $newUser['password']) {
			$changePassword = 1;
			$somethingChanged = true;
		}
		
		// Check for change_email rule
		$changeEmail = 0;
		if($oldUser['email'] != $newUser['email']) {
			$changes['change_email'] = array('oldvalue' => $oldUser['email'], 'newvalue' => $newUser['email']);
			$changeEmail = 1;
			$somethingChanged = true;
		}
		
		// Check for change_email rule
		$changeParams = 0;
		if($oldUser['params'] != $newUser['params']) {
			// Decode oldParams
			$oldUserParams = json_decode($oldUser['params'], true);
			$newUserParams = json_decode($newUser['params'], true);
			$changedOldParams = array();
			$changedNewParams = array();
			foreach ($newUserParams as $paramName => $newParamValue) {
				if(!isset($oldUserParams[$paramName]) && $newParamValue) {
					$changedOldParams[$paramName] = null;
					$changedNewParams[$paramName] = $newParamValue;
				} else {
					if(isset($oldUserParams[$paramName])) {
						$oldParamValue = $oldUserParams[$paramName];
						if($oldParamValue != $newParamValue) {
							$changedOldParams[$paramName] = $oldParamValue;
							$changedNewParams[$paramName] = $newParamValue;
						}
					}
				}
			}
			
			if(count($changedNewParams)) {
				$changes['change_params'] = array('oldvalue' => $changedOldParams, 'newvalue' => $changedNewParams);
				$changeParams = 1;
				$somethingChanged = true;
			}
		}
		
		// Check for change_requirereset rule
		$changeRequirereset = 0;
		if($oldUser['requireReset'] != $newUser['requireReset']) {
			$changes['change_requirereset'] = array('oldvalue' => $oldUser['requireReset'], 'newvalue' => $newUser['requireReset']);
			$changeRequirereset = 1;
			$somethingChanged = true;
		}
		
		// Check for change_block rule
		$changeBlock = 0;
		if($oldUser['block'] != $newUser['block']) {
			$changes['change_block'] = array('oldvalue' => $oldUser['block'], 'newvalue' => $newUser['block']);
			$changeBlock = 1;
			$somethingChanged = true;
		}
		
		// Check for change_sendemail rule
		$changeSendemail = 0;
		if($oldUser['sendEmail'] != $newUser['sendEmail']) {
			$changes['change_sendemail'] = array('oldvalue' => $oldUser['sendEmail'], 'newvalue' => $newUser['sendEmail']);
			$changeSendemail = 1;
			$somethingChanged = true;
		}
		
		// Check for change_usergroups rule
		$changeUsergroups = 0;
		$groupsChanged = !(array_diff($oldUser['groups'], $newUser['groups']) === array_diff($newUser['groups'], $oldUser['groups']));
		if($groupsChanged) {
			try {
				// Get old usergroups names
				$query = "SELECT " . $db->quoteName('title') .
						 "\n FROM " . $db->quoteName('#__usergroups') .
						 "\n WHERE " .  $db->quoteName('id') . " IN(" . implode(',', $oldUser['groups']) . ")";
				$oldUsergroupsName = $db->setQuery($query)->loadColumn();

				// Get new usergroups names
				$query = "SELECT " . $db->quoteName('title') .
						 "\n FROM " . $db->quoteName('#__usergroups') .
						 "\n WHERE " .  $db->quoteName('id') . " IN(" . implode(',', $newUser['groups']) . ")";
				$newUsergroupsName = $db->setQuery($query)->loadColumn();
			} catch(Exception $e) {
				// No errors during the create log record phase
			}

			$changes['change_usergroups'] = array('oldvalue' => $oldUsergroupsName, 'newvalue' => $newUsergroupsName);
			$changeUsergroups = 1;
			$somethingChanged = true;
		}
		
		// Check for change_requirereset rule change empty activation to something more useful
		$changeActivation = 0;
		if($oldUser['activation'] != $newUser['activation']) {
			$changes['change_activation'] = array('oldvalue' => $oldUser['activation'], 'newvalue' => $newUser['activation']);
			$changeActivation = 1;
			$somethingChanged = true;
		}
		
		// Check if $newUser has the profile array, in such case load old profile values for this user from #__user_profiles and compare them
		if(isset($newUser['profile'])) {
			// Found an extended user profile, go on and load old values to compare
			$query = "SELECT " .
					 $db->quotename('profile_key') . " AS " . $db->quote('key') . ", " .
					 $db->quotename('profile_value') . " AS " . $db->quote('value') .
					 "\n FROM " .  $db->quotename('#__user_profiles') .
					 "\n WHERE " .  $db->quotename('profile_key') . " != " .  $db->quote('gdpr_consent_status') .
					 "\n AND " .  $db->quotename('profile_key') . " != " .  $db->quote('profile.tos') .
					 "\n AND " .  $db->quotename('user_id') . " = " .  (int) $newUser['id'];
			try {
				$oldProfileValues = $db->setQuery($query)->loadAssocList('key');
				if(count($newUser['profile'])) {
					// Decode oldParams
					$oldUserProfileValues = $oldProfileValues;
					$newUserProfileValues = $newUser['profile'];
					$changedOldProfile = array();
					$changedNewProfile = array();
					foreach ($newUserProfileValues as $paramName => $newParamValue) {
						if(!isset($oldUserProfileValues['profile.'.$paramName]) && $newParamValue) {
							$changedOldProfile[$paramName] = null;
							$changedNewProfile[$paramName] = $newParamValue;
						} else {
							if(isset($oldUserProfileValues['profile.'.$paramName])) {
								// The value is json_encoded into the database rows by the user profile plugin
								$oldParamValue = json_decode($oldUserProfileValues['profile.'.$paramName]['value']);
								if($oldParamValue != $newParamValue) {
									$changedOldProfile[$paramName] = $oldParamValue;
									$changedNewProfile[$paramName] = $newParamValue;
								}
							}
						}
					}
						
					if(count($changedNewProfile)) {
						// Already exists a change in standard params?
						if(isset($changes['change_params'])) {
							$changes['change_params']['oldvalue'] = array_merge($changes['change_params']['oldvalue'], $changedOldProfile);
							$changes['change_params']['newvalue'] = array_merge($changes['change_params']['newvalue'], $changedNewProfile);
						} else {
							$changes['change_params'] = array('oldvalue' => $changedOldProfile, 'newvalue' => $changedNewProfile);
						}
						$changeParams = 1;
						$somethingChanged = true;
					}
				}
			} catch(Exception $e) {
				// No errors during the create log record phase
			}
		}
		
		// Add support for custom fields
		if(isset($newUser['com_fields'])) {
			// Found an extended user profile by custom fields, go on and load old values to compare
			$postedCustomFields = array_keys($newUser['com_fields']);
			foreach ($postedCustomFields as &$postedCustomField) {
				$postedCustomField = $db->quote($postedCustomField);
			}
			$postedCustomFields = implode(',', $postedCustomFields);
			$query = "SELECT " .
					 $db->quotename('name') . " AS " . $db->quote('key') . ", " .
					 $db->quotename('value') . " AS " . $db->quote('value') .
					 "\n FROM " .  $db->quotename('#__fields') . " AS " .  $db->quotename('fieldstable') .
					 "\n JOIN " .  $db->quotename('#__fields_values') . " AS " .  $db->quotename('fieldsvalues') .
					 "\n ON fieldstable.id = fieldsvalues.field_id " .
					 "\n WHERE fieldsvalues.item_id = " .  (int) $newUser['id'] .
					 "\n AND fieldstable.state = 1" .
					 "\n AND fieldstable.name IN(" .  $postedCustomFields .")";
					try {
						$oldProfileFieldsValues = $db->setQuery($query)->loadAssocList('key');
						if(count($newUser['com_fields'])) {
							// Decode oldParams
							$oldUserProfileFieldsValues = $oldProfileFieldsValues;
							$newUserProfileFieldsValues = $newUser['com_fields'];
							$changedFieldsOldProfile = array();
							$changedFieldsNewProfile = array();
							foreach ($newUserProfileFieldsValues as $paramName => $newParamValue) {
								if(!isset($oldUserProfileFieldsValues[$paramName]) && $newParamValue) {
									$changedFieldsOldProfile[$paramName] = null;
									$changedFieldsNewProfile[$paramName] = $newParamValue;
								} else {
									if(isset($oldUserProfileFieldsValues[$paramName])) {
										// The value is json_encoded into the database rows by the user profile plugin
										$oldParamValue = $oldUserProfileFieldsValues[$paramName]['value'];
										if($oldParamValue != $newParamValue && $newParamValue !== false) {
											$changedFieldsOldProfile[$paramName] = $oldParamValue;
											$changedFieldsNewProfile[$paramName] = $newParamValue;
										}
									}
								}
							}
		
							if(count($changedFieldsNewProfile)) {
								// Already exists a change in standard params?
								if(isset($changes['change_params'])) {
									$changes['change_params']['oldvalue'] = array_merge($changes['change_params']['oldvalue'], $changedFieldsOldProfile);
									$changes['change_params']['newvalue'] = array_merge($changes['change_params']['newvalue'], $changedFieldsNewProfile);
								} else {
									$changes['change_params'] = array('oldvalue' => $changedFieldsOldProfile, 'newvalue' => $changedFieldsNewProfile);
								}
								$changeParams = 1;
								$somethingChanged = true;
							}
						}
					} catch(Exception $e) {
						// No errors during the create log record phase
					}
		}
		
		// Won't log if no changes and empty savings are not enabled
		if(!$this->cParams->get('log_empty_save', 1) && !$somethingChanged) {
			return;
		}
		
		// Track created users into component db table
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_gdpr/tables');
		$logsTable = JTable::getInstance('Logs', 'Table');
		$logsTable->user_id = $newUser['id'];
		$logsTable->name = $newUser['name'];
		$logsTable->username = $newUser['username'];
		$logsTable->email = $newUser['email'];
		$logsTable->change_name = $changeName;
		$logsTable->change_username = $changeUsername;
		$logsTable->change_password = $changePassword;
		$logsTable->change_email = $changeEmail;
		$logsTable->change_params = $changeParams;
		$logsTable->change_requirereset = $changeRequirereset;
		$logsTable->change_block = $changeBlock;
		$logsTable->change_sendemail = $changeSendemail;
		$logsTable->change_usergroups = $changeUsergroups;
		$logsTable->change_activation = $changeActivation;
		$logsTable->editor_user_id = $editorUser->id;
		$logsTable->editor_name = $editorUser->name;
		$logsTable->editor_username = $editorUser->username;
		$logsTable->change_date = JDate::getInstance()->toSql();
		$logsTable->changes_structure = array('changes'=>$changes);
		$logsTable->created_user = 0;
		$logsTable->deleted_user = 0;
		$logsTable->privacy_policy = $privacyPolicy;
		
		// If log IP address
		if($this->cParams->get('log_user_ipaddress', 0)) {
			$logsTable->ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		
		// Integration with Joomla 3.9+ Privacy tool suite
		if(version_compare(JVERSION, '3.9', '>=')) {
			if($this->app->input->get('option') == 'com_privacy' && 
			   $this->app->input->get('task') == 'remove' &&
			   $this->app->input->get('id')) {
					$logsTable->deleted_user = 1;
					
					// Pseudonymise the GDPR user note and GDPR privacy consent
					try {
						$queryNotes = 	"UPDATE " . $db->quotename('#__user_notes') .
										"\n SET " .  $db->quotename('body') . " = " . $db->quote($logsTable->username) .
										"\n WHERE " .  $db->quotename('user_id') . " = " . $logsTable->user_id .
										"\n AND " .  $db->quotename('catid') . " = " . (int) $this->cParams->get('log_usernote_privacypolicy_category', 0) .
										"\n AND " .  $db->quotename('subject') . " = " . $db->quote(JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'));
						$db->setQuery($queryNotes);
						$db->execute();
						
						$queryConsents = "UPDATE " . $db->quotename('#__privacy_consents') .
										 "\n SET " .  $db->quotename('body') . " = " . $db->quote($logsTable->username) .
										 "\n WHERE " .  $db->quotename('user_id') . " = " . $logsTable->user_id .
										 "\n AND " .  $db->quotename('subject') . " = " . $db->quote('COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT');
						$db->setQuery($queryConsents);
						$db->execute();
					} catch(Exception $e) {
						// No errors during the create log record phase
					}
			}
		}
		
		// Store without any user error visible exception
		try {
			$logsTable->store();
		} catch(Exception $e) {
			// No errors during the create log record phase
		}
	}
	
	/**
	 * Log for new user creation, optionally creates a user note for the agreement to the privacy policy
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was successfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onUserAfterSave($newUser, $isnew, $success, $msg) {
		// Validate execution for this component
		if(!$this->validateExecution('exclude_logs')) {
			return;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_logs')) {
			return;
		}
		
		// Comparisons to find changes applied to this user
		$somethingChanged = false;
		$changes = array();
		$originalUser = JFactory::getUser();
		$editorUser = clone $originalUser;

		// Frontend self new registration, swap editor with himself
		if(!$editorUser->id) {
			$editorUser->id = $newUser['id'];
			$editorUser->name = $newUser['name'];
			$editorUser->username = $newUser['username'];
		}

		// Evaluate an accept of the Joomla 3.9+ Privacy tool suite, act the same as the GDPR one
		$privacyToolAccepted = false;
		if(version_compare(JVERSION, '3.9', '>=') && isset($newUser['privacyconsent'])) {
			if(isset($newUser['privacyconsent']['privacy']) == 1) {
				$privacyToolAccepted = true;
			}
		}

		// Log a user creation
		if($isnew && $this->cParams->get('log_user_create', 1)) {
			// Add a record in the log table about the deletion of this user
			$changes = array();
	
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_gdpr/tables');
			$logsTable = JTable::getInstance('Logs', 'Table');
			$logsTable->user_id = $newUser['id'];
			$logsTable->name = $newUser['name'];
			$logsTable->username = $newUser['username'];
			$logsTable->email = $newUser['email'];
			$logsTable->change_name = 0;
			$logsTable->change_username = 0;
			$logsTable->change_password = 0;
			$logsTable->change_email = 0;
			$logsTable->change_params = 0;
			$logsTable->change_requirereset = 0;
			$logsTable->change_block = 0;
			$logsTable->change_sendemail = 0;
			$logsTable->change_usergroups = 0;
			$logsTable->editor_user_id = $editorUser->id;
			$logsTable->editor_name = $editorUser->name;
			$logsTable->editor_username = $editorUser->username;
			$logsTable->change_date = JDate::getInstance()->toSql();
			$logsTable->changes_structure =  array('changes'=>$changes);
			$logsTable->created_user = 1;
			$logsTable->deleted_user = 0;
			if($this->app->input->getInt('gdpr_privacy_policy_checkbox') != 1 && !$privacyToolAccepted) {
				$logsTable->privacy_policy = 0;
			}
	
			// If log IP address
			if($this->cParams->get('log_user_ipaddress', 0)) {
				$logsTable->ipaddress = $_SERVER['REMOTE_ADDR'];
			}
			
			// Store without any user error visible exception
			try {
				$logsTable->store();
			} catch(Exception $e) {
				// No errors during the create log record phase
			}
		}
		
		// Create a user note for privacy policy checkbox accepted
		if($isnew && ($this->app->input->getInt('gdpr_privacy_policy_checkbox') == 1 || $privacyToolAccepted)) {
			// Load component language
			$this->loadComponentLanguage();
			
			$db = JFactory::getDbo();
			
			if($this->cParams->get('log_usernote_privacypolicy', 1)) {
				$userNote = (object) array(
						'user_id'         => $logsTable->user_id,
						'catid'           => $this->cParams->get('log_usernote_privacypolicy_category', 0),
						'subject'         => JText::_('COM_GDPR_PRIVACY_ACCEPTED_SUBJECT'),
						'body'            => JText::sprintf('COM_GDPR_PRIVACY_ACCEPTED_BODY', $logsTable->name, $logsTable->email, $logsTable->change_date),
						'state'           => 1,
						'created_user_id' => $logsTable->user_id,
						'created_time'    => $logsTable->change_date,
						'modified_user_id' => $logsTable->user_id,
						'modified_time'    => $logsTable->change_date
				);
				
				try {
					$db->insertObject('#__user_notes', $userNote);
				} catch(Exception $e) {
					// No errors during the create log record phase
				}
			}
			
			// Integration with Joomla 3.9+ Privacy tool suite
			if(version_compare(JVERSION, '3.9', '>=') && $this->cParams->get('log_userconsent_privacypolicy', 1)) {
				$privacyConsentBody = JText::sprintf('COM_GDPR_PRIVACY_GDPR_ACCEPTED_BODY', $logsTable->name, $logsTable->email, $logsTable->change_date);
				// If log IP address
				if($this->cParams->get('log_user_ipaddress', 0)) {
					$privacyConsentBody .= JText::sprintf('COM_GDPR_PRIVACY_GDPR_BODY_IP_ADDRESS', $_SERVER['REMOTE_ADDR']);
				}
			
				// Create the user consent
				$userPrivacyConsent = (object) array(
						'user_id' => $logsTable->user_id,
						'subject' => 'COM_GDPR_PRIVACY_GDPR_ACCEPTED_SUBJECT',
						'body'    => $privacyConsentBody,
						'created' => $userNote->created_time,
						'state' => 1
				);
			
				try {
					$db->insertObject('#__privacy_consents', $userPrivacyConsent);
				} catch (Exception $e) {
					// Do nothing if the save fails
				}
			}
			
			// Add a profile key for the consent confirmation status
			$userProfileKey = (object) array(
					'user_id'		=> $logsTable->user_id,
					'profile_key'	=> 'gdpr_consent_status',
					'profile_value'	=> 1
			);
			
			try {
				$db->insertObject('#__user_profiles', $userProfileKey);
			}
			catch (Exception $e) {
				// No errors during the create log record phase
			}
		}
	}
	
	/**
	 * Log user deletion, optionally notifying an admin about it
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array    $user     Holds the user data
	 * @param   boolean  $success  True if user was successfully stored in the database
	 * @param   string   $msg      Message
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onUserAfterDelete($user, $success, $msg) {
		// Validate execution for this component
		if(!$this->validateExecution('exclude_logs')) {
			return;
		}
		
		// Check permissions exclusions
		if($this->checkExclusionPermissions('disallow_logs')) {
			return;
		}
		
		// Log a user deletion
		if($this->cParams->get('log_user_delete', 1)) {
			// Add a record in the log table about the deletion of this user
			$changes = array();
			$editorUser = JFactory::getUser();
			
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_gdpr/tables');
			$logsTable = JTable::getInstance('Logs', 'Table');
			$logsTable->user_id = $user['id'];
			$logsTable->name = $user['name'];
			$logsTable->username = $user['username'];
			$logsTable->email = $user['email'];
			$logsTable->change_name = 0;
			$logsTable->change_username = 0;
			$logsTable->change_password = 0;
			$logsTable->change_email = 0;
			$logsTable->change_params = 0;
			$logsTable->change_requirereset = 0;
			$logsTable->change_block = 0;
			$logsTable->change_sendemail = 0;
			$logsTable->change_usergroups = 0;
			$logsTable->editor_user_id = $editorUser->id;
			$logsTable->editor_name = $editorUser->name;
			$logsTable->editor_username = $editorUser->username;
			$logsTable->change_date = JDate::getInstance()->toSql();
			$logsTable->changes_structure =  array('changes'=>$changes);
			$logsTable->created_user = 0;
			$logsTable->deleted_user = 1;
			$logsTable->privacy_policy = 1;
			
			// If log IP address
			if($this->cParams->get('log_user_ipaddress', 0)) {
				$logsTable->ipaddress = $_SERVER['REMOTE_ADDR'];
			}
			
			// Store without any user error visible exception
			try {
				$logsTable->store();
			} catch(Exception $e) {
				// No errors during the create log record phase
			}
		}
		
		// Optionally notifies an administrator about the user deletion by email
		// Notify a user deletion: notify ONLY own/itself user deletion
		$currentUser = JFactory::getUser ();
		if($this->cParams->get('notify_user_self_delete', 0) && $this->app->isSite() && $currentUser->id == $user['id']) {
			// Load component language
			$this->loadComponentLanguage();
			
			// Joomla global configuration
			$jConfig = JFactory::getConfig();
			
			// Check for notify email addresses
			$validEmailAddresses = array();
			$emailAddresses = $this->cParams->get('logs_emails', '');
			$emailAddresses = explode(',', $emailAddresses);
			if(!empty($emailAddresses)) {
				foreach ($emailAddresses as $validEmail) {
					if(filter_var(trim($validEmail), FILTER_VALIDATE_EMAIL)) {
						$validEmailAddresses[] = trim($validEmail);
					}
				}
			}
		
			if(!empty($validEmailAddresses)) {
				// Build the email subject and message
				$sitename = $jConfig->get('sitename');
				$subject  = JText::sprintf('COM_GDPR_USER_DELETED_OWN_PROFILE_SUBJECT', $sitename);
				$msg      = JText::sprintf('COM_GDPR_USER_DELETED_OWN_PROFILE_MSG', $user['name'], $sitename, $user['name'], $user['username'], $user['email'], $logsTable->change_date);
				
				// Send the email
				$mailer = JFactory::getMailer();
				$mailer->isHtml(true);
				$mailer->addReplyTo($user['email'], $user['name']);
				
				$mailer->setSender(array($this->cParams->get('logs_mailfrom', $jConfig->get('mailfrom')),
										 $this->cParams->get('logs_fromname', $jConfig->get('fromname'))));
				
				$mailer->addRecipient($validEmailAddresses);
				
				$mailer->setSubject($subject);
				$mailer->setBody($msg);
				
				// The Send method will raise an error via JError on a failure, we do not need to check it ourselves here
				$mailer->Send();
			}
		}
		
		return true;
	}
	
	/**
	 * Extends forms by adding custom features:
	 * - Privacy policy
	 * - Delete profile btn
	 * - Export profile btn
	 *
	 * @param   Object $form
	 * @param   array  $data
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	function onContentPrepareForm($form, $data) {
		static $injectedGdprApp = false;
		
		// Avoid operations if plugin is executed in backend
		if ( $this->app->isAdmin ()) {
			// Check if the component language files must be loaded in the com_privacy component
			if($this->app->input->getCmd('option') == 'com_privacy' && $this->app->input->getCmd('view') == 'consents') {
				$this->loadComponentLanguage();
			}
			return;
		}
		
		// Validate execution for this component
		if(!$this->validateExecution('exclude_userprofile')) {
			return;
		}
		
		// Exclusions only for checkbox - $this->cParams->set('privacy_policy_checkbox', 0)
		if(!$this->validateExecution('exclude_privacycheckbox')) {
			$this->cParams->set('privacy_policy_checkbox', 0);
		}
		
		// Only works on JForms
		if (!($form instanceof JForm)) return false;
		$document = JFactory::getDocument();
		if($document->getType() !== 'html') {
			return;
		}
		
		// which belong to the following components
		$components_list = array(
				"com_users.profile", // Delete/Export btns
				"com_users.registration", // Checkbox
				"com_contact.contact" // Checkbox
		);
		
		// Support for custom component forms
		$customComponentsUserprofileButtons = array();
		if(trim($this->cParams->get('custom_components_userprofile_buttons', null))) {
			$customComponentsUserprofileButtons = explode(PHP_EOL, trim($this->cParams->get('custom_components_userprofile_buttons'), null));
			if(!empty($customComponentsUserprofileButtons)) {
				foreach ($customComponentsUserprofileButtons as &$customComponentsUserprofileButton) {
					$customComponentsUserprofileButton = trim($customComponentsUserprofileButton);
				}
				$components_list = array_merge($components_list, $customComponentsUserprofileButtons);
			}
		}
		
		$customComponentsFormCheckbox = array();
		if(trim($this->cParams->get('custom_components_form_checkbox', null))) {
			$customComponentsFormCheckbox = explode(PHP_EOL, trim($this->cParams->get('custom_components_form_checkbox'), null));
			if(!empty($customComponentsFormCheckbox)) {
				foreach ($customComponentsFormCheckbox as &$customComponentFormCheckbox) {
					$customComponentFormCheckbox = trim($customComponentFormCheckbox);
				}
				$components_list = array_merge($components_list, $customComponentsFormCheckbox);
			}
		}
		
		// Grab the form name
		$formName = $form->getName();
		
		// If debug mode is enabled show the form name for configuration purpouse
		if($debugMode = $this->cParams->get('debug', 0)) {
			echo '<label style="font-size:14px;background-color:#005e8d;color:#FFF;border-radius:5px;padding:10px;display:inline-block;margin:2px"><span style="font-size:16px;font-weight:bold">Form name: </span>' . $formName . '</label>'; 
		}
		
		if (in_array($formName, $components_list) && !$injectedGdprApp) {
			$currentUser = JFactory::getUser();
			$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/user.js', 'text/javascript', true);
			if($debugMode) {
				echo '<label style="font-size:14px;border:3px solid #005e8d;color:#000;border-radius:5px;padding:10px;display:inline-block;margin:2px"><span style="font-size:16px;font-weight:bold">USER.JS Script Included</span></label>';
			}
			
			// Load component language
			$this->loadComponentLanguage();
			
			//load the translation
			require_once JPATH_ROOT . '/administrator/components/com_gdpr/framework/helpers/language.php';
			$gdprLanguage = GdprHelpersLanguage::getInstance();
			$translations = array(	'COM_GDPR_DELETE_PROFILE',
									'COM_GDPR_EXPORT_CSV_PROFILE',
									'COM_GDPR_EXPORT_XLS_PROFILE',
									'COM_GDPR_PRIVACY_POLICY_REQUIRED',
									'COM_GDPR_DELETE_PROFILE_CONFIRMATION',
									'COM_GDPR_PRIVACY_POLICY_ACCEPT',
									'COM_GDPR_PRIVACY_POLICY_NOACCEPT'
			);
			$gdprLanguage->injectJsTranslations($translations, $document);
			
			$document->addScriptDeclaration("var gdpr_livesite='" . JUri::base() . "';");
			$document->addScriptDeclaration("var gdprCurrentOption = '" . $this->app->input->getCmd('option') . "';");
			$document->addScriptDeclaration("var gdprCurrentView = '" . $this->app->input->getCmd('view') . "';");
			$document->addScriptDeclaration("var gdprCurrentTask = '" . $this->app->input->getCmd('task') . "';");
			$document->addScriptDeclaration("var gdprCurrentLayout = '" . $this->app->input->getCmd('layout') . "';");
			$document->addScriptDeclaration("var gdprCurrentUserId = " . (int)$currentUser->id  . ";");
			$document->addScriptDeclaration("var gdprDebugMode = " . (int)$debugMode  . ";");
			$document->addScriptDeclaration("var gdprDeleteButton = " . (int)$this->cParams->get('userprofile_buttons_delete', 1)  . ";");
			$document->addScriptDeclaration("var gdprExportButton = " . (int)$this->cParams->get('userprofile_buttons_export', 1)  . ";");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckbox = " . (int)$this->cParams->get('privacy_policy_checkbox', 1)  . ";");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckboxLinkText = '" .JText::_($this->cParams->get('privacy_policy_checkbox_link_text', 'Privacy policy'), true)  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckboxLink = '" . JText::_($this->cParams->get('privacy_policy_checkbox_link', 'javascript:void(0)'), true)  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckboxLinkTitle = '" .JText::_($this->cParams->get('privacy_policy_checkbox_link_title', 'Please agree to our privacy policy, otherwise you will not be able to register.'), true)  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckboxOrder = '" . $this->cParams->get('privacy_policy_checkbox_order', 'right') . "';");
			$document->addScriptDeclaration("var gdprRemoveAttributes = " . (int)$this->cParams->get('remove_attributes', 1)  . ";");
			$document->addScriptDeclaration("var gdprForceSubmitButton = " . (int)$this->cParams->get('force_submit_button', 0)  . ";");
			$document->addScriptDeclaration("var gdprRemoveSubmitButtonEvents = " . (int)$this->cParams->get('remove_submit_button_events', 0)  . ";");
			$document->addScriptDeclaration("var gdprPrivacyPolicyContainerTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_container', "<div class='control-group'>{field}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyLabelTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_label', "<div class='control-label' style='display:inline-block'>{label}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyCheckboxTemplate = '" .  addcslashes(str_replace(array("\r\n", "\n", "\r"), ' ', $this->cParams->get('checkbox_template_controls', "<div class='controls' style='display:inline-block;margin-left:20px'>{checkbox}</div>")), "'")  . "';");
			$document->addScriptDeclaration("var gdprPrivacyPolicyControl = " . (int)$this->cParams->get('use_checkbox', 1)  . ";");
			$document->addScriptDeclaration("var gdprFormSubmissionMethod = '" . $this->cParams->get('checkbox_submission_method', 'form') . "';");
			$document->addScriptDeclaration("var gdprFormActionWorkingmode = '" . $this->cParams->get('userprofile_form_action_workingmode', 'base') . "';");
			$document->addScriptDeclaration("var gdprCustomSubmissionMethodSelector = '" . addcslashes($this->cParams->get('custom_submission_method_selectors', 'input[type=submit],button[type=submit],button[type=button]'), "'") . "';");
			$document->addScriptDeclaration("var gdprConsentLogsFormfields = '" . addcslashes(trim($this->cParams->get('consent_logs_formfields', 'name,email,subject,message'), ','), "'") . "';");
			$document->addScriptDeclaration("var gdprCustomAppendMethod = " . (int)$this->cParams->get('custom_append_method', 0)  . ";");
			$document->addScriptDeclaration("var gdprCustomAppendMethodSelector = '" . addcslashes($this->cParams->get('custom_append_method_selectors', 'input[type=submit],button[type=submit]'), "'") . "';");
			$document->addScriptDeclaration("var gdprCustomAppendMethodTargetElement = '" . $this->cParams->get('custom_append_method_target_element', 'parent') . "';");
			$document->addScriptDeclaration("var gdprCheckboxControlsClass = " . (int)$this->cParams->get('checkbox_controls_class', 0)  . ";");
			$document->addScriptDeclaration("var gdprCheckboxControlsClassList = '" . addcslashes(trim($this->cParams->get('checkbox_controls_class_list', 'required'), ' '), "'") . "';");
			
			if($customComponentsViewFormCheckboxSelector = trim($this->cParams->get('custom_components_view_form_checkbox_selector', null))) {
				$document->addScriptDeclaration("var gdprCustomComponentsViewFormCheckboxSelector = '" . addcslashes($customComponentsViewFormCheckboxSelector, "'")  . "';");
			}
			if($customComponentsViewUserprofileButtonsSelector = trim($this->cParams->get('custom_components_view_userprofile_buttons_selector', null))) {
				$document->addScriptDeclaration("var gdprCustomComponentsViewUserprofileButtonsSelector = '" . addcslashes($customComponentsViewUserprofileButtonsSelector, "'")  . "';");
			}
			
			// Inject permissions exclusions
			$disallowPrivacyPolicy = $this->checkExclusionPermissions('disallow_privacypolicy');
			$document->addScriptDeclaration("var gdprDisallowPrivacyPolicy = " . ($disallowPrivacyPolicy ? 1 : 0) . ";");
			
			$disallowDeleteProfile = $this->checkExclusionPermissions('disallow_deleteprofile');
			$document->addScriptDeclaration("var gdprDisallowDeleteProfile = " . ($disallowDeleteProfile ? 1 : 0) . ";");
			
			$disallowExportProfile = $this->checkExclusionPermissions('disallow_exportprofile');
			$document->addScriptDeclaration("var gdprDisallowExportProfile = " . ($disallowExportProfile ? 1 : 0) . ";");
			
			// Add later/revokable support for Joomla! profile form
			if($this->app->input->get('gdprprivacyrequest', 0)) {
				// Force the revokable mode on
				$this->cParams->set('revokable_privacypolicy', 1);
				$document->addScriptDeclaration("var gdprPropagateGdprPrivacyRequest = 1;");
			}
			if((($formName == 'com_users.profile' && $this->app->input->getCmd('option') == 'com_users') ||
				($formName == 'com_comprofiler.userdetails' && $this->app->input->getCmd('option') == 'com_comprofiler')) && $currentUser->id && $this->cParams->get('revokable_privacypolicy', 0)) {
				$db = JFactory::getDbo();
				$query = "SELECT " . $db->quotename('profile_value') . 
						 "\n FROM " .  $db->quotename('#__user_profiles') .
						 "\n WHERE " .  $db->quotename('profile_key') . " = " .  $db->quote('gdpr_consent_status') .
						 "\n AND " .  $db->quotename('user_id') . " = " .  (int)$currentUser->id;
				try {
					$currentPrivacyConsentStatus = $db->setQuery($query)->loadResult();
					$document->addScriptDeclaration("var gdprCurrentPrivacyConsentStatus = " . (int)$currentPrivacyConsentStatus  . ";");
				} catch(Exception $e) {
					// No errors during the create log record phase
				}
			}
			
			// Support for custom component tasks
			$customFormsUserprofileButtons = array();
			if(trim($this->cParams->get('custom_forms_userprofile_buttons', null))) {
				$customFormsUserprofileButtons = explode(PHP_EOL, trim($this->cParams->get('custom_forms_userprofile_buttons'), null));
				if(!empty($customFormsUserprofileButtons)) {
					foreach ($customFormsUserprofileButtons as &$customFormsUserprofileButton) {
						$customFormsUserprofileButton = trim($customFormsUserprofileButton);
					}
					$document->addScriptDeclaration("var gdprCustomFormsUserprofileButtons = " . json_encode($customFormsUserprofileButtons). ";");
				}
			}
			
			$customFormsTaskCheckbox = array();
			if(trim($this->cParams->get('custom_forms_task_checkbox', null))) {
				$customFormsTaskCheckbox = explode(PHP_EOL, trim($this->cParams->get('custom_forms_task_checkbox'), null));
				if(!empty($customFormsTaskCheckbox)) {
					foreach ($customFormsTaskCheckbox as &$customFormTaskCheckbox) {
						$customFormTaskCheckbox = trim($customFormTaskCheckbox);
					}
					$document->addScriptDeclaration("var gdprCustomFormsTaskCheckbox = " . json_encode($customFormsTaskCheckbox). ";");
				}
			}
			
			// Load popup
			if($this->cParams->get('use_fancybox_checkbox', 0)) {
				$document->addStyleSheet(JUri::root(true) . '/plugins/system/gdpr/assets/css/jquery.fancybox.min.css');
				$document->addScript(JUri::root(true) . '/plugins/system/gdpr/assets/js/jquery.fancybox.min.js', 'text/javascript', true);
				$document->addScriptDeclaration("var gdprUseFancyboxCheckbox=1;");
				$document->addScriptDeclaration("var gdprFancyboxCheckboxWidth=" . (int)$this->cParams->get('fancybox_checkbox_width', 700) . ";");
				$document->addScriptDeclaration("var gdprFancyboxCheckboxHeight=" . (int)$this->cParams->get('fancybox_checkbox_height', 800) . ";");
				$document->addScriptDeclaration("var gdprCheckboxCloseText='" . JText::_('COM_GDPR_CLOSE_POPUP_TEXT', true) . "';");
			
				if($this->cParams->get('use_checkbox_contents', 0)) {
					$formatPopup = $this->cParams->get('popup_format_template', 1) ? 'tmpl=component' : 'format=raw';
					$document->addScriptDeclaration("var gdpr_ajaxendpoint_checkbox_policy='" . JUri::base() . "index.php?option=com_gdpr&task=user.getCheckboxPolicy&$formatPopup';");
				}
			}
			
			$injectedGdprApp = true;
		}
		
		return true;
	}

	/**
	 * Reports the privacy related capabilities for this plugin to site administrators.
	 *
	 * @return  array
	 */
	public function onPrivacyCollectAdminCapabilities() {
		// Manage partial language translations
		$jLang = JFactory::getLanguage();
		$jLang->load('com_gdpr', JPATH_ROOT . '/administrator/components/com_gdpr', 'en-GB', true, true);
		if($jLang->getTag() != 'en-GB') {
			$jLang->load('com_gdpr', JPATH_ROOT . '/administrator', null, true, false);
			$jLang->load('com_gdpr', JPATH_ROOT . '/administrator/components/com_gdpr', null, true, false);
		}
				
		return array(
			JText::_('COM_GDPR_PRIVACY_CAPABILITIES_TITLE') => array(
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_LOGS_USER_PROFILE'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_LOGS_USER_CONSENTS'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_COOKIE_MANAGEMENT'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_LOGS_COOKIE_CONSENTS'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_CONSENTS_REGISTRY'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_DATA_BREACH'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_RECORD_PROCESSING_ACTIVITIES'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_PROFILE_EXPORT_DELETION'),
				JText::_('COM_GDPR_PRIVACY_CAPABILITIES_REVOCABLE_CONSENT')
			)
		);
	}
	
	/**
	 * Processes an export request by the Joomla! Privacy component
	 *
	 * This event will collect data for the following GDPR tables:
	 *
	 * - #__gdpr_consent_registry
	 * - #__gdpr_cookie_consent_registry
	 * - #__gdpr_logs
	 *
	 * @param   PrivacyTableRequest Table object  $request  The request record being processed
	 *
	 * @return  PrivacyExportDomain[]
	 */
	public function onPrivacyExportRequest($request) {
		if (!$request->email) {
			return array();
		}
		
		if(!$this->cParams->get('integrate_comprivacy', 1)) {
			return array();
		}
		
		// Check if the component language files must be loaded in the com_privacy component
		$this->loadComponentLanguage();
		
		$db = JFactory::getDbo();
		$userIdQuery = "SELECT " . $db->quoteName('id') .
					   "\n FROM " . $db->quoteName('#__users') .
					   "\n WHERE " . $db->quoteName('email') . " = " . $db->quote($request->email);
		$request->user_id = $db->setQuery($userIdQuery)->loadResult();
		if (!$request->user_id) {
			return array();
		}
		
		// Ensure to have class loading for the com_privacy helpers classes
		JLoader::register('PrivacyExportDomain', JPATH_ROOT . '/administrator/components/com_privacy/helpers/export/domain.php');
		JLoader::register('PrivacyExportField', JPATH_ROOT . '/administrator/components/com_privacy/helpers/export/field.php');
		JLoader::register('PrivacyExportItem', JPATH_ROOT . '/administrator/components/com_privacy/helpers/export/item.php');
	
		// Init domains tables
		$domains = array();
			
		// Initialize a domains assoc array
		$domainsToLoad = array (
				'gdpr_consent_registry' => array (
						'name' => JText::_('COM_GDPR_EXPORT_CONSENT'),
						'description' => JText::_('COM_GDPR_EXPORT_CONSENT_DESC') 
				),
				'gdpr_cookie_consent_registry' => array (
						'name' => JText::_('COM_GDPR_EXPORT_COOKIE_CONSENT'),
						'description' => JText::_('COM_GDPR_EXPORT_COOKIE_CONSENT_DESC')
				),
				'gdpr_logs' => array (
						'name' => JText::_('COM_GDPR_EXPORT_LOGS'),
						'description' => JText::_('COM_GDPR_EXPORT_LOGS_DESC') 
				) 
		);
		
		// Excluded if not enabled
		$cookieCategoriesArray = array('category1','category2','category3','category4');

		foreach ($domainsToLoad as $domainName => $domainInformations) {
			$domain = new PrivacyExportDomain;
			$domain->name = $domainInformations['name'];
			$domain->description = $domainInformations['description'];
			
			// Load all consents for this user
			$query = "SELECT * FROM " . $db->quoteName('#__' . $domainName) .
				     "\n WHERE " . $db->quoteName('user_id') . " = " . (int) $request->user_id;
			$db->setQuery($query);
			try {
				$dataItems = $db->loadObjectList();
			} catch (Exception $e) {
				// No error handling go on with the process for other domains
			}
			 
			foreach ($dataItems as $dataItem) {
				$item = new PrivacyExportItem;
				$item->id = $dataItem->id;
				foreach ($dataItem as $key => $value) {
					if (is_object($value)) {
						$value = (array) $value;
					}
				
					if (is_array($value)) {
						$value = print_r($value, true);
					}

					if(in_array($key, $cookieCategoriesArray)) {
						if(!$this->cParams->get('cookie_' . $key . '_enable', null)) {
							continue;
						}
					}

					$field = new PrivacyExportField;
					$field->name  = $key;
					$field->value = $value;
				
					$item->addField($field);
				}
				$domain->addItem($item);
			}
			// Assign this domain
			$domains[] = $domain;
		}
		
		return $domains;
	}
	
	/**
	 * Event to specify whether a privacy policy has been published.
	 *
	 * @param   array  &$policy  The privacy policy status data, passed by reference, with keys "published" and "editLink"
	 *
	 * @return  void
	 */
	public function onPrivacyCheckPrivacyPolicyPublished(&$policy) {
		// If another plugin has already indicated a policy is published, we won't change anything here
		if ($policy['published']) {
			return;
		}
	
		$privacyPolicyLink = $this->cParams->get('privacy_policy_link');
		$privacyPolicyCheckboxLink = $this->cParams->get('privacy_policy_checkbox_link');
		$privacyPolicyContents = $this->cParams->get('privacy_policy_contents', '');
		$checkboxContents = $this->cParams->get('checkbox_contents', '');
		if (!$privacyPolicyLink && !$privacyPolicyCheckboxLink && !$privacyPolicyContents && !$checkboxContents) {
			return;
		}
	
		$policy['articlePublished'] = true;
		$policy['published'] = true;
		$policy['editLink']  = JRoute::_('index.php?option=com_gdpr&task=config.display#_cookieconsent');
	}
	
	/** Manage the Joomla updater based on the user license
	 *
	 * @access public
	 * @return void
	 */
	public function onInstallerBeforePackageDownload(&$url, &$headers) {
		$uri 	= JUri::getInstance($url);
		$parts 	= explode('/', $uri->getPath());
		$app = JFactory::getApplication();
		if ($uri->getHost() == 'storejextensions.org' && in_array('com_gdpr.zip', $parts)) {
			// Init as false unless the license is valid
			$validUpdate = false;
	
			// Load component language
			$jLang = JFactory::getLanguage();
			$jLang->load('com_gdpr', JPATH_BASE . '/components/com_gdpr', 'en-GB', true, true);
			if($jLang->getTag() != 'en-GB') {
				$jLang->load('com_gdpr', JPATH_BASE, null, true, false);
				$jLang->load('com_gdpr', JPATH_BASE . '/components/com_gdpr', null, true, false);
			}
	
			// Email license validation API call and &$url building construction override
			$cParams = JComponentHelper::getParams('com_gdpr');
			$registrationEmail = $cParams->get('registration_email', null);
	
			// License
			if($registrationEmail) {
				$prodCode = 'gdpr';
				$cdFuncUsed = 'str_' . 'ro' . 't' . '13';
	
				// Retrieve license informations from the remote REST API
				$apiResponse = null;
				$apiEndpoint = $cdFuncUsed('uggc' . '://' . 'fgberwrkgrafvbaf' . '.bet') . "/option,com_easycommerce/action,licenseCode/email,$registrationEmail/productcode,$prodCode";
				if (function_exists('curl_init')){
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$apiResponse = curl_exec($ch);
					curl_close($ch);
				}
				$objectApiResponse = json_decode($apiResponse);
	
				if(!is_object($objectApiResponse)) {
					// Message user about error retrieving license informations
					$app->enqueueMessage(JText::_('COM_GDPR_ERROR_RETRIEVING_LICENSE_INFO'));
				} else {
					if(!$objectApiResponse->success) {
						switch ($objectApiResponse->reason) {
							// Message user about the reason the license is not valid
							case 'nomatchingcode':
								$app->enqueueMessage(JText::_('COM_GDPR_LICENSE_NOMATCHING'));
								break;
	
							case 'expired':
								// Message user about license expired on $objectApiResponse->expireon
								$app->enqueueMessage(JText::sprintf('COM_GDPR_LICENSE_EXPIRED', $objectApiResponse->expireon));
								break;
						}
							
					}
	
					// Valid license found, builds the URL update link and message user about the license expiration validity
					if($objectApiResponse->success) {
						$url = $cdFuncUsed('uggc' . '://' . 'fgberwrkgrafvbaf' . '.bet' . '/TQCE1306VSPQxtve1433712323njnlv35td1tena3386i.ugzy');
	
						$validUpdate = true;
						$app->enqueueMessage(JText::sprintf('COM_GDPR_EXTENSION_UPDATED_SUCCESS', $objectApiResponse->expireon));
					}
				}
			} else {
				// Message user about missing email license code
				$app->enqueueMessage(JText::sprintf('COM_GDPR_MISSING_REGISTRATION_EMAIL_ADDRESS', JFilterOutput::ampReplace('index.php?option=com_gdpr&task=config.display#_licensepreferences')));
			}
	
			if(!$validUpdate) {
				$app->enqueueMessage(JText::_('COM_GDPR_UPDATER_STANDARD_ADVISE'), 'notice');
			}
		}
	}
	
	/**
	 * Plugin class constructor
	 * 
	 * @param Object $subject
	 */
	public function __construct(&$subject) {
		$this->app = JFactory::getApplication();
		try {
			$this->cParams = JComponentHelper::getParams ( 'com_gdpr' );
		} catch (Exception $e) {
			return false;
		}
		parent::__construct ( $subject );
	}
}