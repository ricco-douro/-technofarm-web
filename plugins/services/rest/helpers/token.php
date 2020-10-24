<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.rest
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Class tokenServicesRest
 *
 * REST Service plugin - Token Authentication
 *
 * @since  1.0
 */
class ServicesRestHelpersToken {
    
    public function __construct()
    {
        $this->tokenServicesRestTokenParam();
        $this->tokenServicesRestRequestToken();
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function tokenServicesRestTokenParam()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * Note that header-based tokens will always override parameter-based tokens in the even that both are used in a
         * single request.
         */
        if(!is_null($app->request->params('token')) || !is_null($app->request->headers->get('token')) ) {
            if($app->request->headers->get('token')){
                $this->tokenServicesAuthentication($app->request->headers->get('token'));
            }else{
                $this->tokenServicesAuthentication($app->request->params('token'));
            }
        }
    }

    /**
     *  Token login authentication
     *
     * @string: token
     * @since 1.0
     */
    public function tokenServicesRestRequestToken()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * @SWG\Get(
         *     path="/token/{token}",
         *     summary="Token authentication",
         *     description="Authenticate access to API by token id
#### Additional Information
If connecting via a browser client, the current session will persist, even with subsequent incorrect token requests, until it times out or is logged out manually.",
         *     operationId="getTokenByToken",
         *     tags={"Token"},
         *
         *     @SWG\Parameter(
         *     description="Token",
         *     in="path",
         *     name="token",
         *     required=true,
         *     type="string",
         * ),
         *
         *   @SWG\Response(
         *     response="200",
         *     description="Authenticated",
         *   ),
         *     @SWG\Response(
         *     response="401",
         *     description="Unauthorized",
         *   ),
         * )
         */
        $app->get('/token/:token', function ($token) use ($app) {
            $auth = $this->tokenServicesAuthentication($token);

            if($auth[0]) {
                $app->render(200, array(
                        'msg' => 'Authenticated',
                        'jresponse' => $auth
                    )
                );
            }elseif($auth['sessionid']){
                $app->render(200, array(
                        'msg' => 'Authenticated',
                        'jresponse' => $auth
                    )
                );
            }else{
                $app->render(401, array(
                        'msg' => 'Unauthorized',
                        'jresponse' => $auth
                    )
                );
            }
        }
        )->name('getTokenByToken');
    }

    /**
     * @param $token
     * @return array|bool
     * @since 1.0
     */
    public function tokenServicesAuthentication($token)
    {
        $app = \Slim\Slim::getInstance();

        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName(array('userid')))
            ->from($db->quoteName('#__services_tokens'))
            ->where($db->quoteName('token') . ' = ' . $db->quote($token));

        $db->setQuery($query);
        $userid = $db->loadResult();
        if (!JFactory::getUser($userid)->id) {
            $app->response()->status(403);
            return FALSE;
        } else {
            $user = JFactory::getUser($userid);
            $username = $user->get('username');

            // Define empty response object
            $response = new stdClass();
            $result = array();

            if(JFactory::getUser()->id !== $userid) {
                $application = JFactory::getApplication('site');

                JPluginHelper::importPlugin('user');
                $options = array();
                $options['action'] = 'core.login.site';

                $response->username = $username;
                $result = $application->triggerEvent('onUserLogin', array((array)$response, $options));
            }


            if((isset($result[0])|| JFactory::getSession()->get('user')->id === $userid ) ){
                // Get session
                $result['sessionid'] = JFactory::getSession()->getId();
                $app->response->headers->set('Joomla-Sessionid', JFactory::getSession()->getId());

                // Save to Slim instance settings.
                $app->config('token',$token);
                $app->config('userid',$userid);
                $app->config('username',$username);

            }
            return $result;
        }
    }

    function call(){
        return $this->next->call();
    }

}