<?php
/**
 * Services - Slim Plugin
 * @version     1.3.6
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @package		Joomla.Plugin
 * @subpakage	Annatech.Joomla
 */
defined('_JEXEC') or die( 'Restricted access' );

include_once JPATH_PLUGINS . '/services/slim/helpers/routes.php';

class plgServicesSlim extends \Slim\Middleware
{
    /**
     * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Array returned with Slim Micro-Framework configuration parameters. Mainly intended for settings configured
     * during Slim instantiation.
     *
     * @var array
     * @since 1.0
     */
    protected $slim_config = array();

    /**
     * @var null
     * @since 1.0
     */
    protected $token = null;

    /**
     * @var
     * @since 1.0
     */
    protected $user_params;

    public function __construct()
    {

        $lang = JFactory::getLanguage();
        $lang->load('plg_services_slim', __DIR__);
        $this->getSlimConfig();

        new SlimRouteDumper();
        new ServicesSlimHelpersRatelimit();
        new ServicesSlimHelpersSwagger();

        $this->getAllSlimRoutes();
    }

    /**
     * Here is where we define the Slim Micro-Framework configuration settings.
     * While some parameters and defined and fixed, here, others are returned by the function, to be used during
     * instantiation of the Slim Framework.
     *
     * @return array
     * @since 1.0
     */
    protected function getSlimConfig()
    {
        $app = \Slim\Slim::getInstance();
        $tokenServices = new ServicesRestHelpersToken();

        // First assign component parameters to $slim_config array
        $this->slim_config = array(
            'mode' => JComponentHelper::getParams('com_services')->get('mode'),
            'debug' => JComponentHelper::getParams('com_services')->get('debug'),
            'log_level' => JComponentHelper::getParams('com_services')->get('log_level'),
            'log_enabled' => JComponentHelper::getParams('com_services')->get('log_enabled'),
            'cookies_encrypt' => JComponentHelper::getParams('com_services')->get('cookies_encrypt'),
            'cookies_domain' => JComponentHelper::getParams('com_services')->get('cookies_domain'),
            'cookies_secure' => JComponentHelper::getParams('com_services')->get('cookies_secure'),
            'cookies_secret_key' => JComponentHelper::getParams('com_services')->get('cookies_secret_key'),
            'http_version' => JComponentHelper::getParams('com_services')->get('http_version'),
            'api_rate_limit' => JComponentHelper::getParams('com_services')->get('api_rate_limit'),
            'slim_override' => JComponentHelper::getParams('com_services')->get('slim_override')
        );

        if($app->request->headers->get("token")){
            $this->token = $app->request->headers->get("token");
        }elseif($app->request()->get("token")){
            $this->token = $app->request()->get("token");
        }

        // Logout user if token is invalid
        $session = JFactory::getSession();
        if($session->getId() && $this->token !== null) {
            $query = $app->_db->getQuery(true);
            $query->select('userid');
            $query->from($app->_db->quoteName('#__session'));
            $query->where('session_id="' . $session->getId() . '"');
            $app->_db->setQuery($query);
            $results = $app->_db->loadObject();

            if ($tokenServices->tokenServicesAuthentication($this->token) === false) {
                $logout = JFactory::$application->logout($results->userid);
            }
        }

        if($this->token && !isset($logout)) {
            // Get a db connection.
            $db = JFactory::getDbo();

            // Create a new query object.
            $query = $db->getQuery(true);

            $query->select($db->quoteName(array(
                'log_level',
                'log_enabled',
                'cookies_encrypt',
                'cookies_domain',
                'cookies_secure',
                'cookies_secret_key',
                'http_version',
                'api_rate_limit'
            )));
            $query->from($db->quoteName('#__services_tokens'));
            $query->where('token="' . $this->token . '"');

            // Reset the query using our newly populated query object.
            $db->setQuery($query);

            // Load the results as a list of stdClass objects.
            $this->user_params = $db->loadObjectList();
        }

        if($this->user_params[0] && $this->slim_config['slim_override'] !== 'true') {
            $log = $app->getLog();
            // log.level
            if (strtolower($this->user_params[0]->log_level) !== 'inherit') {
                $this->slim_config['log_level'] = $this->user_params[0]->log_level;
            }

            switch ($this->slim_config['log_level']) {
                case "EMERGENCY":
                    $log->setLevel(\Slim\Log::EMERGENCY);
                    break;
                case "ALERT":
                    $log->setLevel(\Slim\Log::ALERT);
                    break;
                case "CRITICAL":
                    $log->setLevel(\Slim\Log::CRITICAL);
                    break;
                case "ERROR":
                    $log->setLevel(\Slim\Log::ERROR);
                    break;
                case "WARN":
                    $log->setLevel(\Slim\Log::WARN);
                    break;
                case "NOTICE":
                    $log->setLevel(\Slim\Log::NOTICE);
                    break;
                case "INFO":
                    $log->setLevel(\Slim\Log::INFO);
                    break;
                case "DEBUG":
                    $log->setLevel(\Slim\Log::DEBUG);
                    break;

            }

            // log.enabled
            if (strtolower($this->user_params[0]->log_enabled) !== 'inherit') {
                $this->slim_config['log_enabled'] = $this->user_params[0]->log_enabled;
            }
            $log->setEnabled($this->slim_config['log_enabled']);

            // cookies domain
            if (strtolower($this->user_params[0]->cookies_domain) !== 'inherit') {
                $this->slim_config['cookies_domain'] = $this->user_params[0]->cookies_domain;
            }
            $app->config('cookies.domain', $this->slim_config['cookies_domain']);

            //cookies secure
            if (strtolower($this->user_params[0]->cookies_secure) !== 'inherit') {
                $this->slim_config['cookies_secure'] = $this->user_params[0]->cookies_secure;
            }
            $app->config('cookies.secure', $this->slim_config['cookies_secure']);

            // cookies.secret_key
            if (strtolower($this->user_params[0]->cookies_secret_key) !== 'inherit') {
                $this->slim_config['cookies_secret_key'] = $this->user_params[0]->cookies_secret_key;
            }
            $app->config('cookies.secret_key', $this->slim_config['cookies_secret_key']);

            // http.version
            if (strtolower($this->user_params[0]->http_version) !== 'inherit') {
                $this->slim_config['http_version'] = $this->user_params[0]->http_version;
            }
            $app->config('http.version', $this->slim_config['http_version']);

            // API Rate Limit
            // While not part of Slim Framework, included here in the interest of efficiency.
            if ($this->user_params[0]->api_rate_limit !== '' && $this->user_params[0]->api_rate_limit !== 0) {
                $this->slim_config['api_rate_limit'] = $this->user_params[0]->api_rate_limit;
            }
            $app->config('rate.limit', $this->slim_config['api_rate_limit']);
        }

        return $this->slim_config;
    }

    /**
     * getAllSlimRoutes
     *
     * Slim route to return list of all routes. Restrict to access by Super Admins, only.
     *
     * @since 1.0
     */
    protected function getAllSlimRoutes(){

        $app = \Slim\Slim::getInstance();

        /**
         * @SWG\Get(
         *     path="/slim/routes",
         *     summary="Returns list of all configured Slim service methods.",
         *     description="
## Access Control
Authorization requires that user has core.create privileges over cAPI tokens.",
         *     operationId="getSlimRoutes",
         *     produces={"application/json"},
         *     tags={"Slim"},
         *   @SWG\Response(
         *     response=200,
         *     description="OK"
         *   ),
         *   @SWG\Response(
         *     response="403",
         *     description="Forbidden"
         *   )
         * )
         */
        $app->get('/slim/routes', function () use ($app)
        {
            $user = JFactory::getUser();
            $results[] = array();
            if($user->authorise('core.create','com_services.token')) {
                $routes = SlimRouteDumper::getAllRoutes();

                $i = 0;
                foreach($routes as $route){
                    $results[$i] = "{$route->getName()} : {$route->getPattern()}";
                    $i = $i+1;
                }

                $app->render(200,
                    $results
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden',
                )
            );
        }
        )->name('getSlimRoutes');
    }

    function call(){
        return $this->next->call();
    }
}