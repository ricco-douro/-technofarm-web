<?php

/**
 * @version     1.3.6
 * @package     com_services
 * @copyright   Copyright (C) 2015 Annatech LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later
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

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        ServicesHelper::addSubmenu('swaggerui');
        $this->addToolbar();
        // $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/services.php';

        $state = $this->get('State');
        $canDo = ServicesHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_SERVICES_TITLE_SWAGGERUIMISC'), 'swaggerui.png');

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_services');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_services&view=swaggeruimisc');

        $this->extra_sidebar = '';
        $app = JApplicationAdministrator::getInstance();
        $this->loadJavascript($app->isSSLConnection());
    }

    /**
     * Loads required javascript
     */
    protected function loadJavascript($isSSL) {
        $customTag = '';
        $customTag .= <<<EOD
        <link rel="icon" type="image/png" href="components/com_services/assets/swaggerui/images/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="components/com_services/assets/swaggerui/images/favicon-16x16.png" sizes="16x16" />
        
        <link href='components/com_services/assets/swaggerui/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='components/com_services/assets/swaggerui/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='components/com_services/assets/swaggerui/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
        <link href='components/com_services/assets/swaggerui/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
        <link href='components/com_services/assets/swaggerui/css/print.css' media='print' rel='stylesheet' type='text/css'/>
        
        <script src='components/com_services/assets/swaggerui/lib/object-assign-pollyfill.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/jquery.slideto.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/jquery.wiggle.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/handlebars-2.0.0.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/js-yaml.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/lodash.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/backbone-min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/swagger-ui.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/highlight.9.1.0.pack.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/highlight.9.1.0.pack_extended.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/jsoneditor.min.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/marked.js' type='text/javascript'></script>
        <script src='components/com_services/assets/swaggerui/lib/swagger-oauth.js' type='text/javascript'></script>
        
        <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('body').addClass('swagger-section');
                });
        </script>
        
        <script type="text/javascript">
            jQuery(function () {
              var url = window.location.search.match(/url=([^&]+)/);
              if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
              } else {
                url = "/api/v1/slim/swagger";
              }
        
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
