<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.slim
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Class ServicesSlimHelpersSwagger
 *
 * @since 1.2.7
 */
class ServicesSlimHelpersSwagger  {

    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * @SWG\Get(
         *     path="/slim/swagger",
         *     summary="Returns Swagger definition JSON",
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
        $app->get('/slim/swagger', function () use ($app)
        {
            define('API_HOST', JURI::getInstance()->getHost());
            $protocol = 'http';
            if(JFactory::getURI()->isSSL()){
                $protocol = 'https';
            }
            define('HTTP_PROTOCOL',$protocol);

	        $servicesPlugins = JPluginHelper::getPlugin('services');

	        /**
	         * Create array with complete list of services plugins
	         */
	        $servicesPluginsArray = array();
	        foreach($servicesPlugins as $k => $v){
	        	$servicesPluginsArray = array_merge($servicesPluginsArray,(array)$v->name);
	        }

	        /**
	         * Assign default array element to include basic Swagger doc info
	         */
	        $scanArray = array(JPATH_BASE.'/api/');

	        /**
	         * Get Swagger UI route view ACL configuration from component options
	         */
	        $swaggerUiVisibility = json_decode(JComponentHelper::getParams('com_services')->get('swaggeruivisibility'));

	        $user = JFactory::getUser();

	        /**
	         * Access control logic to determine which API plugins routes should be visible to a given visitor in Swagger UI
	         * Note: This is controlled through the selective generation of API rights in GET /slim/swagger
	         */
	        $c = 0;
	        if(isset($swaggerUiVisibility->capiplugin)){
		        $c = count($swaggerUiVisibility->capiplugin);
	        }
			if($c < 1){
				foreach ($servicesPluginsArray as $pluginame){
					$scanArray = array_merge($scanArray, array(JPATH_BASE . '/plugins/services/' . $pluginame));
				}
			}else
			{
				for ($x = 0; $x < $c; $x++)
				{
					if (
						array_intersect(
							array_values($user->getAuthorisedGroups()),
							array_map('intval',
								(is_array($swaggerUiVisibility->guest_usergroup[$x]) ?
									$swaggerUiVisibility->guest_usergroup[$x]  :
									(array)$swaggerUiVisibility->guest_usergroup[$x]
								)
							)
						) === array()
						&& (array)$swaggerUiVisibility->guest_usergroup[$x] !== array("")
					)
					{
						// $scanArray = array_merge($scanArray, array(JPATH_BASE . '/plugins/services/' . $swaggerUiVisibility->capiplugin[$x]));
						unset($servicesPluginsArray[array_search($swaggerUiVisibility->capiplugin[$x], $servicesPluginsArray, true)]);
					}
				}
				if(count($servicesPluginsArray) > 0){
					foreach ($servicesPluginsArray as $key => $pluginame){
						$scanArray = array_merge($scanArray, array(JPATH_BASE . '/plugins/services/' . $pluginame));
					}
				}
			}

	        $swagger = \Swagger\scan(
	        	$scanArray
	        );

            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
	        $app->response->setBody($swagger);
        }
        );

        /**
         * @SWG\Get(
         *     path="/slim/swagger/remote/{swaggerjson}",
         *     summary="Loads remote Swagger definition JSON through local API",
         *     description="Requires remote URL to be base64 encoded before submitting as swaggerjson path parameter.",
         *     operationId="getSlimSwaggerRemoteByUrl",
         *     tags={"Slim"},
         *
         *     @SWG\Parameter(
         *     description="Base64 Encoded URL",
         *     in="path",
         *     name="swaggerjson",
         *     required=true,
         *     type="string",
         * ),
         *
         *   @SWG\Response(
         *     response=200,
         *     description="OK"
         *   ),
         *   @SWG\Response(
         *     response="404",
         *     description="Error"
         *   )
         * )
         */
        $app->get('/slim/swagger/remote/:swaggerjson', function ($swaggerjson) use ($app)
        {
            $slimHeaders = $app->request()->headers()->all();

            if (!isset($slimHeaders['Cookie'])) {
                $cookieTransfer = JFactory::getSession()->getName() . "=" . JFactory::getSession()->getId() . "; joomla_user_state=logged_in";
            } else {
                $cookieTransfer = preg_replace("/" . JFactory::getSession()->getName() . "[\s\S]+?;/", JFactory::getSession()->getName() . "=" . JFactory::getSession()->getId() . ";", $slimHeaders['Cookie'], 1);
            }

            $headers = array(
                'Content-Type' => 'application/json',
                // 'Authorization' => 'Basic ' . base64_encode($this->pluginObject['username'] . ":" . $this->pluginObject['password']),
                // 'X-Auth-Token' => urlencode($auth_token),
                // 'Content-Length' => urlencode(strlen($app_xml)),
                'User-Agent' => $slimHeaders['User-Agent'],
                'Cookie' => $cookieTransfer,
                // 'Cookie' => $slimHeaders['Cookie'],
            );
            $http = new JHttp();
            $http->setOption(CURLOPT_POST, 0);
            $http->setOption(CURLOPT_RETURNTRANSFER, 1);
            $http->setOption(CURLOPT_COOKIESESSION, 1);
            $http->setOption(CURLOPT_SSL_VERIFYHOST, 1);
            $http->setOption(CURLOPT_SSL_VERIFYPEER, 1);
            // $http->setOption(CURLOPT_SSL_CIPHER_LIST, 'ecdhe_rsa_aes_128_gcm_sha_256');
            $http->setOption(CURLOPT_CAINFO, JPATH_SITE.'/components/com_services/assets/swaggerui/cacert.pem');

            $cipher = new JCryptCipherCrypto();
            $session = JFactory::getSession();

            try {
                // $response = $http->get(base64_decode($swaggerjson), $headers, 30);
                $response = $http->get($cipher->decrypt($this->base64url_decode($swaggerjson),$session->get('cryptoKey')), $headers, 30);
            } catch (Exception $e) {
                $app->render(404, array(
                        'msg' => 'Caught exception: ', $e->getMessage()
                    )
                );
            }
            $app->response->setStatus(200);
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setBody($response->body);
        }
        )->name('getSlimSwaggerRemoteByUrl');
    }

    /**
     * @param $s
     * @return bool|string
     * @since 1.2.8
     */
    protected function base64url_decode($s) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
    }

}