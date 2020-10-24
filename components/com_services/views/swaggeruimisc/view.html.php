<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport( 'joomla.environment.uri' );

/**
 * View class for a list of Services.
 */
class ServicesViewSwaggeruimisc extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 * @return void
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$this->state = $this->get('State');
		$this->params = $app->getParams('com_services');


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_SERVICES_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

        $cipher = new JCryptCipherCrypto();
        $key = $cipher->generateKey();
        $session = JFactory::getSession();
        $session->set('cryptoKey',$key);

        $swaggerjsonurl = $this->base64url_encode($cipher->encrypt($this->params->get('swaggerjson'),$key));

        // $swaggerjsonurl = base64_encode($this->params->get('swaggerjson'));
        $swaggerpath = JUri::base().'api/v1/slim/swagger/remote/'.$swaggerjsonurl;

        $this->loadJavascript($app->isSSLConnection(),$swaggerpath);
    }

    /**
     * @param $s
     * @return mixed
     */
    protected function base64url_encode($s) {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($s));
    }

    /**
     * Loads required javascript
     */
    protected function loadJavascript($isSSL,$swaggerpath) {
        $customTag = '';
        $baseUrl = preg_replace('{/$}','',JUri::base());
        $customTag .= <<<EOD
        <link rel="icon" type="image/png" href="$baseUrl/components/com_services/assets/swaggerui/images/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="$baseUrl/components/com_services/assets/swaggerui/images/favicon-16x16.png" sizes="16x16" />
        
        <link href='$baseUrl/components/com_services/assets/swaggerui/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='$baseUrl/components/com_services/assets/swaggerui/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='$baseUrl/components/com_services/assets/swaggerui/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='$baseUrl/components/com_services/assets/swaggerui/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
        <link href='$baseUrl/components/com_services/assets/swaggerui/css/print.css' media='print' rel='stylesheet' type='text/css'/>
        
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/object-assign-pollyfill.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/jquery.slideto.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/jquery.wiggle.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/handlebars-2.0.0.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/js-yaml.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/lodash.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/backbone-min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/swagger-ui.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/highlight.9.1.0.pack.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/highlight.9.1.0.pack_extended.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/jsoneditor.min.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/marked.js' type='text/javascript'></script>
        <script src='$baseUrl/components/com_services/assets/swaggerui/lib/swagger-oauth.js' type='text/javascript'></script>
        
        <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('body').addClass('swagger-section');
                });
        </script>
        
        <script type="text/javascript">
            jQuery(function () {
                url = "$swaggerpath";
                        
              hljs.configure({
                highlightSizeThreshold: 5000
              });
        
              // Pre load translate...
              if(window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
              }
              window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                  if(typeof initOAuth == "function") {
                    initOAuth({
                      clientId: "your-client-id",
                      clientSecret: "your-client-secret-if-required",
                      realm: "your-realms",
                      appName: "your-app-name",
                      scopeSeparator: ",",
                      additionalQueryStringParams: {}
                    });
                  }
        
                  if(window.SwaggerTranslator) {
                    window.SwaggerTranslator.translate();
                  }
                },
                onFailure: function(data) {
                  log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: false,
                defaultModelRendering: 'schema',
                showRequestHeaders: false
              });
        
              window.swaggerUi.load();
        
              function log() {
                if ('console' in window) {
                  console.log.apply(console, arguments);
                }
              }
          });
          </script>
EOD;

        /**
         * Necessary to ensure Swagger UI service lookup matches HTTP transport method
         */
        if($isSSL){
            $customTag .= <<<EOD
            <script type="text/javascript">
                $(window).bind("load", function() {
                    window.swaggerUi.api.setSchemes(['https']);
                });
            </script>
EOD;
        }

        $document = JFactory::getDocument();
        $document->addCustomTag($customTag);
    }

}