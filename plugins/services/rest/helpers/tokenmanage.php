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
 * Class tokenServicesRestManage
 * @since 1.2.5
 */
class ServicesRestHelpersTokenmanage
{
    /**
     * @var
     * @since 1.2.5
     */
    private $userObject;

	/**
	 * @var
	 * @since 1.3.4.4
	 */
	private $actions;

    /**
     * @var bool
     * @since 1.2.5
     */
    private $userMatchesRequest;

    /**
     * tokenServicesRestManage constructor.
     * @since 1.2.5
     */
    public function __construct()
    {
        $app = \Slim\Slim::getInstance();
        $this->userObject = JFactory::getUser();

        /**
         * Token Services
         */
        $app->group('/token/manage', function () use ($app) {

            /**
             * @SWG\Get(
             *     path="/token/manage/all",
             *     summary="Get all tokens",
             *     description="Returns list of all tokens. This method is restricted to super administrators with core.admin privileges (either globally or on com_services).",
             *     operationId="getTokenManageAll",
             *     tags={"Token"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of tokens",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Unauthorized",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="No tokens found",
             *   ),
             * )
             */
            $app->get('/all', function () use ($app) {
                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );

	            if($this->userObject->authorise('core.admin','com_services') ||
		            $this->userObject->authorise('core.admin'
		            )
	            ){
		            if ($tokensModel->getItems()) {
			            $app->render(
				            200, $tokensModel->getItems()
			            );
		            }else
		            {
			            $app->render(404, array(
					            'msg' => 'No tokens found',
				            )
			            );
		            }
	            }
	            $app->render(403, array(
			            'msg' => 'Forbidden',
		            )
	            );
            })->name('getTokenManageAll');

            /**
             * @SWG\Get(
             *     path="/token/manage/",
             *     summary="Get tokens by current user session",
             *     description="Returns list of all tokens associated with the current user's session",
             *     operationId="getTokenManage",
             *     tags={"Token"},
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of tokens",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Not Found",
             *   ),
             * )
             */
            $app->get('/', function () use ($app) {
                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );
                $tokensModel->setState('filter.userid', $this->userObject->id);
                if($this->userObject->guest){
                    $app->render(403, array(
                            'msg' => 'Forbidden',
                        )
                    );
                }
                elseif ($tokensModel->getItems()) {
                    $app->render(
                        200, $tokensModel->getItems()
                    );
                }
                $app->render(404, array(
                        'msg' => 'Not Found',
                    )
                );
            })->conditions(array($this->userObject->id > 0))->name('getTokenManage');

            /**
             * @SWG\Get(
             *     path="/token/manage/{type}/{value}",
             *     summary="Get token(s) by userid, username, email or token",
             *     description="Returns list of all tokens associated with a userid, username, email address or token",
             *     operationId="getTokenManageByTypeValue",
             *     tags={"Token"},
             *
             *     @SWG\Parameter(
             *     description="Parameter type",
             *     in="path",
             *     name="type",
             *     required=true,
             *     type="string",
             *     enum={ "id", "username", "email", "token"}
             * ),
             *     @SWG\Parameter(
             *     description="Parameter value",
             *     in="path",
             *     name="value",
             *     required=true,
             *     type="string"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="List of tokens",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="No tokens found",
             *   ),
             * )
             * TODO: Improve parameter definitions for type and value
             */
            $app->get('/:type/:value', function ($type,$value) use ($app) {

                if ($this->userObject->authorise('core.admin','com_services') || $this->getUserMatchesRequest($type,$value)) {
                    $servicesJoomlaHelpersUser = new ServicesJoomlaHelpersUser();
                    switch ($type) {
                        case 'id':
                            if(JUserHelper::getUserGroups($value)) {
                                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );
                                $tokensModel->setState('filter.userid', $value);
                                $app->render(
                                    200, $tokensModel->getItems()
                                );
                            }else{
                                $app->render(
                                    404, array(
                                        'msg' => 'Not Found'
                                    )
                                );
                            }
                            break;
                        case 'username':
                            if(JUserHelper::getUserId($value)) {
                                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );
                                $tokensModel->setState('filter.userid', JUserHelper::getUserId($value));
                                $app->render(
                                    200, $tokensModel->getItems()
                                );
                            }else{
                                $app->render(
                                    404, array(
                                        'msg' => 'Not Found'
                                    )
                                );
                            }
                            break;
                        case 'email':
                            if($servicesJoomlaHelpersUser->getUserIdbyEmail($value)) {
                                // JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/tables');
                                // $tokenTable = JTable::getInstance('Token', 'ServicesTable', array());

                                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );
                                $tokensModel->setState('filter.userid',$servicesJoomlaHelpersUser->getUserIdbyEmail($value));
                                $app->render(
                                    200, $tokensModel->getItems()
                                );
                            }else{
                                $app->render(
                                    404, array(
                                        'msg' => 'Not Found'
                                    )
                                );
                            }
                            break;
                        case 'token':
                            if($servicesJoomlaHelpersUser->getUserIdbyToken($value)) {
                                // JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/tables');
                                // $tokenTable = JTable::getInstance('Token', 'ServicesTable', array());

                                JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/models');
                                $tokensModel = JModelLegacy::getInstance( 'Tokens', 'ServicesModel' , array( 'ignore_request' => true ) );
                                $tokensModel->setState('filter.userid',$servicesJoomlaHelpersUser->getUserIdbyToken($value));
                                $app->render(
                                    200, $tokensModel->getItems()
                                );
                            }else{
                                $app->render(
                                    404, array(
                                        'msg' => 'Not Found'
                                    )
                                );
                            }
                            break;
                        default:
                            $app->render(
                                400, array(
                                    'msg' => 'Bad Request: Illegal request type.'
                                )
                            );
                    }
                }
                $app->render(403, array(
                        'msg' => 'Forbidden',
                    )
                );
            })->name('getTokenManageByTypeValue');

            /**
             * @SWG\Post(
             *     path="/token/manage/",
             *     summary="Create token",
             *     description="Create token by current user session.
### Additional Information:
Token creation by logged in user for any other user ID requires core.create privilege on com_services.
### IMPORTANT:
To allow, for example, Registered users to create their own tokens, the site administrator must configure the com_services component privileges to enable that capability for the desired group. This means that the site administrator can designate a particular group to which a user must belong so they can create their own tokens.",
             *     operationId="postTokenManageByUserid",
             *     operationId="postTokenManageByCurrentsession",
             *     tags={"Token"},
             *
             *     @SWG\Parameter(
             *     description="Mode",
             *     in="query",
             *     name="mode",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Debug",
             *     in="query",
             *     name="debug",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Level",
             *     in="query",
             *     name="log_level",
             *     required=true,
             *     default="EMERGENCY",
             *     enum={"EMERGENCY","ALERT","CRITICAL","ERROR","WARN","NOTICE","INFO","DEBUG","inherit"},
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Enabled",
             *     in="query",
             *     name="log_enabled",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Encrypt",
             *     in="query",
             *     name="cookies_encrypt",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Domain",
             *     in="query",
             *     name="cookies_domain",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secure",
             *     in="query",
             *     name="cookies_secure",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secret Key",
             *     in="query",
             *     name="cookies_secret_key",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="HTTP Version",
             *     in="query",
             *     name="http_version",
             *     required=true,
             *     default="1.10",
             *     type="number",
             *     format="float"
             * ),
             *     @SWG\Parameter(
             *     description="API Rate Limit",
             *     in="query",
             *     name="api_rate_limit",
             *     required=false,
             *     default="0",
             *     type="integer",
             *     format="int32"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Successfully created token",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Bad request",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="User ID not found",
             *   )
             * )
             */

            /**
             * @SWG\Post(
             *     path="/token/manage/userid/{userid}",
             *     summary="Create token by user ID",
             *     description="Create token by user ID.
### Additional Information:
Token creation by logged in user for any other user ID requires core.create privilege on com_services.
### IMPORTANT:
To allow, for example, Registered users to create their own tokens, the site administrator must configure the com_services component privileges to enable that capability for the desired group. This means that the site administrator can designate a particular group to which a user must belong so they can create their own tokens.",
             *     operationId="postTokenManageByUserid",
             *     operationId="postTokenManageByUserid",
             *     tags={"Token"},
             *
             *     @SWG\Parameter(
             *     description="User ID",
             *     in="path",
             *     name="userid",
             *     required=true,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Mode",
             *     in="query",
             *     name="mode",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Debug",
             *     in="query",
             *     name="debug",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Level",
             *     in="query",
             *     name="log_level",
             *     required=true,
             *     default="EMERGENCY",
             *     enum={"EMERGENCY","ALERT","CRITICAL","ERROR","WARN","NOTICE","INFO","DEBUG","inherit"},
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Enabled",
             *     in="query",
             *     name="log_enabled",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Encrypt",
             *     in="query",
             *     name="cookies_encrypt",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Domain",
             *     in="query",
             *     name="cookies_domain",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secure",
             *     in="query",
             *     name="cookies_secure",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secret Key",
             *     in="query",
             *     name="cookies_secret_key",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="HTTP Version",
             *     in="query",
             *     name="http_version",
             *     required=true,
             *     default="1.10",
             *     type="number",
             *     format="float"
             * ),
             *     @SWG\Parameter(
             *     description="API Rate Limit",
             *     in="query",
             *     name="api_rate_limit",
             *     required=false,
             *     default="0",
             *     type="integer",
             *     format="int32"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Successfully created token",
             *   ),
             *     @SWG\Response(
             *     response="401",
             *     description="Bad request",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="User ID not found",
             *   )
             * )
             */
            $app->post('(/(userid/:userid))', function ($userid = null) use ($app) {
                $this->createTokenServicesRestManage($app, $userid);
            })->name('postTokenManageByUserid');

            /**
             * @SWG\Put(
             *     path="/token/manage/tokenid/{tokenid}",
             *     summary="Update token by ID",
             *     description="Update token parameters by token ID.",
             *     operationId="putTokenManageByTokenid",
             *     tags={"Token"},
             *
             *     @SWG\Parameter(
             *     description="Token ID",
             *     in="path",
             *     name="tokenid",
             *     required=true,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Token Reset",
             *     in="query",
             *     name="token",
             *     enum={"","reset"},
             *     default="",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="State",
             *     in="query",
             *     name="state",
             *     required=false,
             *     default="1",
             *     enum={0,1,-2},
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Mode",
             *     in="query",
             *     name="mode",
             *     required=false,
             *     type="integer",
             *     format="double"
             * ),
             *     @SWG\Parameter(
             *     description="Debug",
             *     in="query",
             *     name="debug",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Level",
             *     in="query",
             *     name="log_level",
             *     required=true,
             *     default="EMERGENCY",
             *     enum={"EMERGENCY","ALERT","CRITICAL","ERROR","WARN","NOTICE","INFO","DEBUG","inherit"},
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Log Enabled",
             *     in="query",
             *     name="log_enabled",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Encrypt",
             *     in="query",
             *     name="cookies_encrypt",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Domain",
             *     in="query",
             *     name="cookies_domain",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secure",
             *     in="query",
             *     name="cookies_secure",
             *     default="false",
             *     enum={"true","false"},
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="Cookies Secret Key",
             *     in="query",
             *     name="cookies_secret_key",
             *     required=false,
             *     type="string"
             * ),
             *     @SWG\Parameter(
             *     description="HTTP Version",
             *     in="query",
             *     name="http_version",
             *     required=true,
             *     default="1.10",
             *     type="number",
             *     format="float"
             * ),
             *     @SWG\Parameter(
             *     description="API Rate Limit",
             *     in="query",
             *     name="api_rate_limit",
             *     required=false,
             *     default="0",
             *     type="integer",
             *     format="int32"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Successfully deleted token",
             *   ),
             *     @SWG\Response(
             *     response="400",
             *     description="Bad request",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Token not found",
             *   ),
             *     @SWG\Response(
             *     response="409",
             *     description="Conflict: Cannot change token used to access this service method",
             *   ),
             * )
             */
            $app->put('/tokenid/:tokenid', function ($tokenid) use ($app) {
                $this->updateTokenServicesRestManage($app, $tokenid);
            })->name('putTokenManageByTokenid');

            /**
             * @SWG\Delete(
             *     path="/token/manage/tokenid/{tokenid}",
             *     summary="Delete token by ID",
             *     description="Delete token for user ID or current user by session.",
             *     operationId="deleteTokenManageByTokenid",
             *     tags={"Token"},
             *
             *     @SWG\Parameter(
             *     description="Token ID",
             *     in="path",
             *     name="tokenid",
             *     required=true,
             *     type="integer",
             *     format="double"
             * ),
             *
             *   @SWG\Response(
             *     response="200",
             *     description="Successfully deleted token",
             *   ),
             *     @SWG\Response(
             *     response="400",
             *     description="Bad request",
             *   ),
             *     @SWG\Response(
             *     response="403",
             *     description="Forbidden",
             *   ),
             *     @SWG\Response(
             *     response="404",
             *     description="Token not found",
             *   ),
             *     @SWG\Response(
             *     response="409",
             *     description="Conflict: Cannot change token used to access this service method",
             *   ),
             * )
             */
            $app->delete('/tokenid/:tokenid', function ($tokenid) use ($app) {
                $this->deleteTokenServicesRestManage($app, $tokenid);
            })->name('deleteTokenManageByTokenid');

        });

        $this->getTokenServicesRestManage();
    }

    /**
     * Get token(s) by user ID or current user session.
     *
     * @string: token
     * @since 1.2.5
     */
    private function getTokenServicesRestManage()
    {

    }

    /**
     * Create token for user ID or current user by session.
     *
     * @param null $app
     * @param null $userid
     * @since 1.2.5
     */
    private function createTokenServicesRestManage($app = null, $userid = null)
    {
	    /**
	     * Method requires that request is made by logged-in user
	     */
    	if ($this->userObject->id > 0) {

            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/tables/');
            $table = JTable::getInstance('Token','ServicesTable');

            $token  = new stdClass();
            $token->state = 1;
            $token->id = 0;

            if($userid !== null) {
                if(JFactory::getUser($userid)->username === null){
                    $app->render(404,array(
                        'msg' => 'User ID '.$userid.' not found'
                        )
                    );
                }

                /**
                 * Token creation by logged in user for any other user ID requires core.create privilege on com_services.
                 *
                 * IMPORTANT: To allow, for example, Registered users to create their own tokens, the site administrator
                 * must configure the com_services component privileges to enable that capability for the desired group.
                 *
                 * This means that the site administrator can designate a particular group to which a user must belong
                 * so they can create their own tokens.
                 */

                if($this->userObject->authorise('core.create','com_services'))
                {
	                if ($this->userObject->authorise('core.admin'))
	                {
		                $token->userid = $userid;
	                }
	                elseif ($userid === $this->userObject->id)
	                {
		                $token->userid = $userid;
	                }
	                elseif(JFactory::getUser($userid)->authorise('core.admin')){
		                $app->render(403,array(
				                'msg' => 'Cannot create tokens for users with core.admin privileges unless requesting account has similar privileges.'
			                )
		                );
	                }elseif($this->userObject->authorise('core.manage','com_services')){

	                	if(count(array_diff(JFactory::getUser($userid)->groups, $this->userObject->groups)) < 1){
			                $token->userid = $userid;
		                }else{
			                $app->render(403,array(
					                'msg' => 'Requesting account must be a member of at least all the groups as the user ID for which token creation is requested.'
				                )
			                );
		                }
	                }
	                else{
		                $app->render(403,array(
				                'msg' => 'Requesting account does not have privileges to create token on behalf of other accounts.'
			                )
		                );
	                }
                }else{
	                $app->render(403,array(
			                'msg' => 'Requesting account does not have core.create privilege on com_services.'
		                )
	                );
                }
            }else{
                $token->userid = $this->userObject->id;
            }
            $token->created = JFactory::getDate()->toSQL();
            $token->token = base64_encode(bin2hex(JCrypt::genRandomBytes(15)));
            if($app->request->params('mode')){
                $token->mode = $app->request->params('mode');
            }
            if($app->request->params('debug')){
                $token->debug = $app->request->params('debug');
            }
            if($app->request->params('log_level')){
                $token->log_level = $app->request->params('log_level');
            }else{
                $token->log_level = 'EMERGENCY';
            }
            if($app->request->params('log_enabled')){
                $token->log_enabled = $app->request->params('log_enabled');
            }else{
                $token->log_enabled = 'inherit';
            }
            if($app->request->params('cookies_encrypt')){
                $token->cookies_encrypt = $app->request->params('cookies_encrypt');
            }
            if($app->request->params('cookies_domain')){
                $token->cookies_domain = $app->request->params('cookies_domain');
            }
            if($app->request->params('cookies_secure')){
                $token->cookies_secure = $app->request->params('cookies_secure');
            }else{
                $token->cookies_secure = 'inherit';
            }
            if($app->request->params('cookies_secret_key')){
                $token->cookies_secret_key = $app->request->params('cookies_secret_key');
            }
            if($app->request->params('http_version')){
                $token->http_version = $app->request->params('http_version');
            }else{
                $token->http_version = 1.10;
            }
            if($app->request->params('api_rate_limit')){
                $token->api_rate_limit = $app->request->params('api_rate_limit');
            }
            $data   = (array)$token;

            // Bind data

            if (!$table->bind($data))
            {
                $app->render(401, array(
                        'msg' => 'Error: Incorrect format.'
                    )
                );
            }

            // Check to make sure our data is valid, raise notice if it's not.
            if (!$table->check()) {
                $app->render(401, array(
                        'msg' => 'Error: Incorrect format.'
                    )
                );
            }

            // Now store the token, raise notice if it doesn't get stored.
            if (!$table->save($data)) {
                $app->render(401, array(
                        'msg' => 'Error: Duplicate token.'
                    )
                );
            }

            $app->render(200,
                get_object_vars($table)
            );

    	}
        $app->render(403, array(
                'msg' => 'Forbidden'
            )
        );
    }

	/**
	 * Update token for user ID or current user by session.
	 *
	 * @param null $app
	 * @param $tokenid
	 * @throws Exception
	 * @since 1.2.5
	 */
    private function updateTokenServicesRestManage($app = null, $tokenid)
    {
        if ($this->userObject->authorise('core.admin') ||
        	$this->userObject->authorise('core.edit','com_services') ||
	        $this->userObject->authorise('core.edit.own','com_services.token.'.$tokenid)
        ) {

            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/tables/');
            $table = JTable::getInstance('Token','ServicesTable');

            // Check if token exists
            if(!$table->load($tokenid)){
                $app->render(404, array(
                        'msg' => 'Token not found'
                    )
                );
            }

	        /**
	         * The following check to prevent changes to the same token used to authenticate the request is being removed.
	         * In the future, an option may be provided to the site administrator to allow / disallow such functionality.
	         */
	        /**
            // Prevents changes to the same token used to access this service method.
            if(($table->token === $app->request->headers('token') || $table->token === $app->request->params('token'))
                && $app->request->params('token') === 'reset' ){
                $app->render(409, array(
                        'msg' => 'Conflict: Cannot change token used to access this service method.'
                    )
                );
            }
	        **/

            $token  = new stdClass();
            $token->state = $app->request->params('state');
            $token->id = $tokenid;
            $token->created = JFactory::getDate()->toSQL();
            if($app->request->params('token') === 'reset') {
                $token->token = str_shuffle(base64_encode(bin2hex(JCrypt::genRandomBytes(12)).random_int(100,999)).random_int(100,999));
            }
            if($app->request->params('mode')){
                $token->mode = $app->request->params('mode');
            }
            if($app->request->params('debug')){
                $token->debug = $app->request->params('debug');
            }
            if($app->request->params('log_level')){
                $token->log_level = $app->request->params('log_level');
            }
            if($app->request->params('log_enabled')){
                $token->log_enabled = $app->request->params('log_enabled');
            }
            if($app->request->params('cookies_encrypt')){
                $token->cookies_encrypt = $app->request->params('cookies_encrypt');
            }
            if($app->request->params('cookies_domain')){
                $token->cookies_domain = $app->request->params('cookies_domain');
            }
            if($app->request->params('cookies_secure')){
                $token->cookies_secure = $app->request->params('cookies_secure');
            }
            if($app->request->params('cookies_secret_key')){
                $token->cookies_secret_key = $app->request->params('cookies_secret_key');
            }
            if($app->request->params('http_version')){
                $token->http_version = $app->request->params('http_version');
            }
            if($app->request->params('api_rate_limit')){
                $token->api_rate_limit = $app->request->params('api_rate_limit');
            }
            $data   = (array)$token;

            // Bind data

            if (!$table->bind($data))
            {
                $app->render(401, array(
                        'msg' => 'Error: Incorrect format.'
                    )
                );
            }

            // Check to make sure our data is valid, raise notice if it's not.
            if (!$table->check()) {
                $app->render(401, array(
                        'msg' => 'Error: Incorrect format.'
                    )
                );
            }

            // Now store the token, raise notice if it doesn't get stored.
            if (!$table->store()) {
                $app->render(401, array(
                        'msg' => 'Error: Duplicate token.'
                    )
                );
            }

            $app->render(200,
                get_object_vars($table)
            );
        }
        $app->render(403, array(
                'msg' => 'Forbidden',
            )
        );
    }

    /**
     * Delete token for user ID or current user by session.
     *
     * @param null $app
     * @param $tokenid
     * @since 1.2.5
     */
    private function deleteTokenServicesRestManage($app = null, $tokenid)
    {
        if ($this->userObject->authorise('core.admin') ||
        	$this->userObject->authorise('core.delete','com_services') ||
	        $this->userObject->authorise('core.edit.own','com_services.token.'.$tokenid)
        ) {

            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_services/tables/');
            $table = JTable::getInstance('Token','ServicesTable');

            // Check if token exists
            if(!$table->load($tokenid)){
                $app->render(404, array(
                        'msg' => 'Token not found'
                    )
                );
            }

            $token = $table->token;

	        /**
	         * The following check to prevent changes to the same token used to authenticate the request is being removed.
	         * In the future, an option may be provided to the site administrator to allow / disallow such functionality.
	         */

	        /**
	        // Prevents changes to the same token used to access this service method.
	        if(($table->token === $app->request->headers('token') || $table->token === $app->request->params('token'))){
	            $app->render(409, array(
	                'msg' => 'Conflict: Cannot change token used to access this service method.'
	                )
	            );
	        }
	         */

            if($table->delete($tokenid)){
                $app->render(200, array(
                        'msg' => 'Token deleted',
                        'tokenid' => $tokenid,
                        'token' => $token
                    )
                );
            }else{
                $app->render(400,array(
                        'msg' => 'Bad request'
                    )
                );
            }
        }
        $app->render(403, array(
                'msg' => 'Forbidden',
            )
        );
    }

    /**
     * Check if request userid matches id of account which owns the token(s)
     *
     * @param $type
     * @param $value
     * @return bool
     * @since 1.2.5
     */
    private function getUserMatchesRequest($type,$value){
        $servicesJoomlaHelpersUser = new ServicesJoomlaHelpersUser();
        switch ($type){
            case 'id':
                if($this->userObject->id === $value){
                    $this->userMatchesRequest = true;
                }
                break;
            case 'username':
                if($this->userObject->id === JUserHelper::getUserId($value)){
                    $this->userMatchesRequest = true;
                }
                break;
            case 'email':
                if($this->userObject->id === $servicesJoomlaHelpersUser->getUserIdbyEmail($value)){
                    $this->userMatchesRequest = true;
                }
                break;
            case 'token':
                if($this->userObject->id === $servicesJoomlaHelpersUser->getUserIdbyToken($value)){
                    $this->userMatchesRequest = true;
                }
                break;
            default:
                $this->userMatchesRequest = false;
        }
        return $this->userMatchesRequest;
    }
}