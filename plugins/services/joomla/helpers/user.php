<?php
/**
 * @version     1.3.6
 * @package     Annatech.Plugin
 * @subpackage  Services.joomla
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
define( 'DS', DIRECTORY_SEPARATOR );


/**
 * Class ServicesJoomlaUser
 * @since 1.2.9
 */
class ServicesJoomlaHelpersUser  {

    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * @SWG\Get(
         *     path="/user",
         *     summary="Get current logged in user or display if accessing web service as guest.",
         *     operationId="getUser",
         *     tags={"User"},
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user object or welcomes guest"
         *   ),
         * )
         */
        $app->get('/user', function () use ($app)
        {

            $user = JFactory::getUser();
            $name = !$user->guest ? $user->name : 'guest';

            $userResponse = new stdClass;
            $userResponse->username         = $user->username;
            $userResponse->email            = $user->email;
            $userResponse->sendEmail        = $user->sendEmail;
            $userResponse->registerDate     = $user->registerDate;
            $userResponse->lastvisitDate    = $user->lastvisitDate;
            $userResponse->activation       = $user->activation;
            $userResponse->params           = $user->params;
            $userResponse->groups           = $user->groups;
            $userResponse->lastResetTime    = $user->lastResetTime;
            $userResponse->resetCount       = $user->resetCount;
            $userResponse->requireReset     = $user->requireReset;

            if(!$user->guest){
                $app->render(
                    200, get_object_vars($userResponse)
                );
            } else {
                $app->render(
                    200, array(
                        'msg' => 'Welcome' . ' ' . $name,
                    )
                );
            }
        })->name('getUser');

	    /**
	     * @SWG\Get(
	     *     path="/user/list/all",
	     *     summary="Get list of all users.",
	     *     operationId="getUserListAll",
	     *     tags={"User"},
	     *   @SWG\Response(
	     *     response="200",
	     *     description="Returns all user accounts"
	     *   ),
	     *   @SWG\Response(
	     *     response="403",
	     *     description="Forbidden"
	     *   )
	     * )
	     */
	    $app->get('/user/list/all', function () use ($app)
	    {

		    $user = JFactory::getUser();

		    $component  = 'users';
		    $type       = 'Users';
		    $prefix     = 'UsersModel';
		    $jclass     = 'models';

		    /**
		     * Component context path for ADMINISTRATOR
		     **/
		    $comPath = JPATH_SITE.'/administrator/components/'. 'com_'.$component;

		    $config = array(
			    'ignore_request' => true,
		    );

		    JModelLegacy::addIncludePath($comPath . '/'.$jclass);

		    $instance = new stdClass();
		    try {
			    $instance = JModelLegacy::getInstance($type, $prefix, $config);
		    } catch (Exception $e) {
			    $app->render(400, array(
					    'msg' => $e->getMessage()
				    )
			    );
		    }

		    /**
		     * Check access
		     */
		    if ($user->id !== 0) {
			    /**
			     * Recursively json_decode any json encoded fields
			     */
			    foreach($instance as $field => $value){
				    if ($this->json_validator($value))
				    {
					    $instance->$field = json_decode($value);
				    }
			    }

			    /**
			     * Item access is granted under the following conditions:
			     * - core.edit privilege on associated component
			     * - core.edit privilege on associated item asset
			     * - matching access-level for user and each model item
			     */
			    $instance->items = array();

			    /**
			     * Set default Model method
			     */
			    $modelMethod = 'getItems';

			    /**
			     * Create empty $modelMethodArguments array
			     */
			    $modelMethodArguments = array();

			    /**
			     * Get Model item list
			     * ref: https://stackoverflow.com/questions/980708/calling-method-of-object-of-object-with-call-user-func
			     */
			    foreach( call_user_func_array(array($instance,$modelMethod), $modelMethodArguments) as $item)
			    {
				    if ($user->authorise('core.edit', 'com_' . $component . '.component')
					    || $user->authorise('core.edit','com_'.$component.'.'.rtrim($type,"s").'.'.$item->id)
					    || in_array($item->access, JAccess::getAuthorisedViewLevels($user->id))
				    )
				    {
				    	$item = (array)$item;

				    	// Remove password field
				    	unset($item['password']);

				    	// json_decode params
					    $item['params'] = json_decode($item['params']);

					    array_push($instance->items, $item);
				    }
			    }

			    if($instance)
			    {
				    $app->render(200, array(
						    $component => $instance
					    )
				    );
			    }else{
				    $app->render(404, array(
						    'msg' => 'Not Found'
					    )
				    );
			    }
		    }

		    $app->render(
		    	403, array(
		    		'msg' => 'Forbidden'
			    )
		    );
	    })->name('getUserListAll');

        /**
         * @SWG\Get(
         *     path="/user/detail/{type}/{value}",
         *     summary="Get user detail by user ID, username, email or token ID",
         *     description="",
         *     operationId="getUserDetail",
         *     produces={"application/json"},
         *     tags={"User"},
         *     @SWG\Parameter(
         *     description="Can be id, username, email or token",
         *     in="path",
         *     name="type",
         *     required=true,
         *     enum={"id","username","email","token"},
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     description="Value should match the defined type",
         *     in="path",
         *     name="value",
         *     required=true,
         *     type="string"
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="OK"
         *   ),
         *   @SWG\Response(
         *     response="400",
         *     description="Bad Request: Illegal request type."
         *   ),
         *   @SWG\Response(
         *     response="501",
         *     description="Unauthorized"
         *   ),
         *  @SWG\Response(
         *     response="404",
         *     description="Not Found"
         *   ),
         * )
         */
        $app->get('/user/detail/:type/:value', function ($type,$value) use ($app)
        {
        	$user = JFactory::getUser();
	        switch ($type)
	        {
		        case 'id':
		            if($user->authorise('core.admin') || $user->id === $value)
			        {
				        $userResponse = $this->getUserDetailById($value);
				        if ($userResponse->username)
				        {
					        $app->render(
						        200, get_object_vars($userResponse)
					        );
				        }
				        else
				        {
					        $app->render(
						        404, array(
							        'msg' => 'Not Found'
						        )
					        );
				        }
			        }
			        $app->render(401, array(
					        'msg' 		=> 'Unauthorized'
				        )
			        );
			        break;
		        case 'username':
			        if($user->authorise('core.admin') || $user->username === $value)
			        {
				        if ($userId = JUserHelper::getUserId($value))
				        {
					        $userResponse = $this->getUserDetailById($userId);
					        $app->render(
						        200, get_object_vars($userResponse)
					        );
				        }
				        else
				        {
					        $app->render(
						        404, array(
							        'msg' => 'Not Found'
						        )
					        );
				        }
			        }
			        $app->render(401, array(
					        'msg' 		=> 'Unauthorized'
					        )
			        );
			        break;
		        case 'email':
			        if($user->authorise('core.admin') || $user->email === $value)
			        {
				        if ($userId = $this->getUserIdbyEmail($value))
				        {
					        $userResponse = $this->getUserDetailById($userId);
					        $app->render(
						        200, get_object_vars($userResponse)
					        );
				        }
				        else
				        {
					        $app->render(
						        404, array(
							        'msg' => 'Not Found'
						        )
					        );
				        }
			        }
			        $app->render(401, array(
					        'msg' 		=> 'Unauthorized'
				        )
			        );
			        break;
		        case 'token':
			        if($user->authorise('core.admin') || $user->id === $this->getUserIdbyToken($value))
			        {
				        if ($userId = $this->getUserIdbyToken($value))
				        {
					        $userResponse = $this->getUserDetailById($userId);
					        $app->render(
						        200, get_object_vars($userResponse)
					        );
				        }
				        else
				        {
					        $app->render(
						        404, array(
							        'msg' => 'Not Found'
						        )
					        );
				        }
			        }
			        $app->render(401, array(
					        'msg' 		=> 'Unauthorized'
				        )
			        );
			        break;
		        default:
			        $app->render(
				        400, array(
					        'msg' => 'Bad Request: Illegal request type.'
				        )
			        );
	        }
        })->name('getUserDetailByTypeValue');

        /**
         * @SWG\Get(
         *     path="/user/login/{username}/{password}",
         *     summary="Login user",
         *     description="User login authentication via Joomla authentication plugins with username and password. Note that since credentials are passed into the URL, be aware that they can be stored in server logs.
API traffic must traverse a secure (HTTPS) connection.",
         *     operationId="getLoginByUsernamePassword",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Joomla username",
         *     in="path",
         *     name="username",
         *     required=true,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     description="Joomla password",
         *     in="path",
         *     name="password",
         *     required=true,
         *     type="string"
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Welcomes logged in user"
         *   ),
         *  @SWG\Response(
         *     response="401",
         *     description="Unauthorized"
         *   ),
         * )
         */
        $app->get('/user/login/:username/:password', function ($username,$password) use ($app) {
            $credentials = array('username' => $username, 'password' => $password);

            $login = JFactory::$application->login($credentials, array());

            // TODO: Update login method!
            // $applicationSite = new JApplicationSite();
            // $login = $applicationSite->login($credentials, array());
	        
            $query = $app->_db->getQuery(true);
            $query->select('*');
            $query->from($app->_db->quoteName('#__session'));
            $query->where('username="'.$username.'"');
            $app->_db->setQuery($query);
            $results = $app->_db->loadObject();

            if ($login) {
                $app->render(200, array(
                        'msg'		=> 'Authenticated',
                        'jresponse' => $login,
                        'session' 	=> $results->session_id
                    )
                );
            } else {
                $app->render(401, array(
                        'msg' 		=> 'Unauthorized',
                        'jresponse'	=> $login
                    )
                );
            }
        }
        )->name('getUserLoginByUsernamePassword');

        /**
         * @SWG\Get(
         *     path="/user/logout/{username}/{session}",
         *     summary="Logout user",
         *     description="User logout action via Joomla authentication plugins with username and session ID. Note that since the session ID and username are passed into the URL, be aware that they can be stored in server logs.
API traffic must traverse a secure (HTTPS) connection.",
         *     operationId="getLogoutByUsernamePassword",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Joomla username",
         *     in="path",
         *     name="username",
         *     required=true,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Joomla session id",
         *     in="path",
         *     name="session",
         *     required=true,
         *     type="string",
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Confirms successful user logout"
         *   ),
         *  @SWG\Response(
         *     response="403",
         *     description="Cannot logout user"
         *   ),
         * )
         */
        $app->get('/user/logout/:username/:session', function ($username,$session) use ($app) {

            $query = $app->_db->getQuery(true);
            $query->select('*');
            $query->from($app->_db->quoteName('#__session'));
            $query->where('username="' . $username . '"');
            $app->_db->setQuery($query);
            $results = $app->_db->loadObject();


            if (isset($results) && $session === $results->session_id) {
                $logout = JFactory::$application->logout($results->userid);
                if ($logout) {
                    $app->render(200, array(
                            'msg' => 'Logout successful',
                            'jresponse' => $logout
                        )
                    );
                } else {
                    $app->render(403, array(
                            'msg' => 'Logout failed',
                            'jresponse' => $logout
                        )
                    );
                }
            }

            $app->render(403, array(
                    'msg' => 'Username or session does not exist'
                )
            );

        }
        )->name('getUserLogoutByUsernameSession');

        /**
         * @SWG\Post(
         *     path="/user/edit",
         *     summary="Create user",
         *     description="Create new Joomla user account.",
         *     operationId="postUserEdit",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Full name",
         *     in="query",
         *     name="name",
         *     required=true,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Username",
         *     in="query",
         *     name="username",
         *     required=true,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Password",
         *     in="query",
         *     name="password",
         *     required=false,
         *     type="string",
         *     format="password"
         * ),
         *     @SWG\Parameter(
         *     description="User email",
         *     in="query",
         *     name="email",
         *     required=true,
         *     type="string",
         *     format="email"
         * ),
         *     @SWG\Parameter(
         *     description="User groups",
         *     in="query",
         *     name="groups",
         *     required=true,
         *     type="string",
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user id"
         *   ),
         *     @SWG\Response(
         *     response="400",
         *     description="Could not save user"
         *   ),
         *  @SWG\Response(
         *     response="403",
         *     description="Forbidden"
         *   ),
         *     @SWG\Response(
         *     response="409",
         *     description="Could not bind data."
         *   )
         * )
         */
        $app->post('/user/edit', function () use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {

                if(!$app->request->params('groups')){
                    $groups = array ('1' ,'2' );
                }else{
                    $groups = explode(',',$app->request->params('groups'));
                }

                if(!$app->request->params('password')){
                    $password = JUserHelper::genRandomPassword(32);
                }else{
                    $password = $app->request->params('password');
                }

                $data = array(
                    'name'      =>  $app->request->params('name'),
                    'username'  =>  $app->request->params('username'),
                    'password'  =>  $password,
                    'password2' =>  $password,
                    'email'     =>  $app->request->params('email'),
                    'block'     =>   0,
                    'groups'    =>  $groups
                );
                $user = new JUser;
                if(!$user->bind($data)) {
                    $app->render(409, array(
                            'msg' => 'Could not bind data. Error: ' . $user->getError(),
                        )
                    );

                }
                if (!$user->save()) {
                    $app->render(400, array(
                            'msg' => 'Could not save user. Error: ' . $user->getError(),
                        )
                    );
                }
                $app->render(200, array(
                        'id' => $user->id,
                    )
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                    )
            );
        })->name('postUserEdit');

        /**
         * @SWG\Put(
         *     path="/user/edit/{id}",
         *     summary="Update user",
         *     description="Update existing Joomla user account.",
         *     operationId="putUserEdit",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="User ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Full name",
         *     in="query",
         *     name="name",
         *     required=false,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="User name",
         *     in="query",
         *     name="username",
         *     required=false,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="User email",
         *     in="query",
         *     name="email",
         *     required=false,
         *     type="string",
         *     format="email"
         * ),
         *     @SWG\Parameter(
         *     description="User groups",
         *     in="query",
         *     name="groups",
         *     required=false,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Block",
         *     in="query",
         *     name="block",
         *     required=false,
         *     type="integer",
         *     format="int32"
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user id of updated user"
         *   ),
         *     @SWG\Response(
         *     response="400",
         *     description="Could not save user"
         *   ),
         *  @SWG\Response(
         *     response="403",
         *     description="Forbidden"
         *   ),
         *     @SWG\Response(
         *     response="409",
         *     description="Could not bind data."
         *   )
         * )
         */
        $app->put('/user/edit/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                $updateUser = JFactory::getUser($id);
                $data = array();

                $data['id'] = $id;
                if($app->request->params('name')){
                    $data['name'] = $app->request->params('name');
                }
                if($app->request->params('username')){
                    $data['username'] = $app->request->params('username');
                }
                if($app->request->params('password')){
                    $data['password'] = $app->request->params('password');
                    $data['password2'] = $app->request->params('password');
                }
                if($app->request->params('email')){
                    $data['email'] = $app->request->params('email');
                }
                if($app->request->params('block')){
                    $data['block'] = $app->request->params('block');
                }
                if($app->request->params('groups')){
                    $data['groups'] = explode(',',$app->request->params('groups'));
                }
                if(!$updateUser->bind($data)) {
                    $app->render(409, array(
                            'msg' => 'Could not bind data. Error: ' . $updateUser->getError(),
                        )
                    );

                }
                if (!$updateUser->save(true)) {
                    $app->render(400, array(
                            'msg' => 'Could not save user. Error: ' . $updateUser->getError(),
                        )
                    );
                }
                $app->render(200, array(
                        'id' => $updateUser->id,
                    )
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('putUserEditById');

        /**
         * @SWG\Delete(
         *     path="/user/edit/{id}",
         *     summary="Delete user",
         *     description="Delete existing Joomla user account.",
         *     operationId="deleteUserEdit",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="User ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="string",
         * ),
         *     @SWG\Parameter(
         *     description="Must use mode=override parameter to authorize deletion of core.admin id.",
         *     in="query",
         *     name="mode",
         *     required=false,
         *     type="string",
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns successful response ""Deleted user: [id]"""
         *   ),
         *     @SWG\Response(
         *     response="400",
         *     description="Error deleting user: [id]"
         *   ),
         *  @SWG\Response(
         *     response="403",
         *     description="Forbidden"
         *   ),
         *     @SWG\Response(
         *     response="410",
         *     description="User id does not exist: id"
         *   )
         * )
         */
        $app->delete('/user/edit/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                if($user->id === $id){
                    $app->render(403, array(
                            'msg' => 'Cannot delete own account.',
                        )
                    );
                }
                $deleteUser = JFactory::getUser($id);
                if ($deleteUser->id === null || $deleteUser->id === '') {
                    $app->render(410, array(
                            'msg' => 'User id does not exist: '.$id,
                        )
                    );
                }
                if ($deleteUser->authorise('core.admin')) {
                    if($app->request->params('mode') === 'override'){
                        $app->render(200, array(
                                'msg' => 'Deleted core.admin user: '.$id,
                            )
                        );
                    }else{
                        $app->render(403, array(
                                'msg' => 'Requested user is a member of core.admin: ' . $id,
                                'warning' => 'Must use mode=override parameter to authorize deletion of core.admin id.'
                            )
                        );
                    }
                }
                if (!$deleteUser->delete()) {
                    $app->render(400, array(
                            'msg' => 'Error deleting user: '.$id,
                        )
                    );
                }
                $app->render(200, array(
                        'msg' => 'Deleted user: '.$id,
                    )
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('deleteUserEditById');

        /**
         * @SWG\Post(
         *     path="/user/group/edit",
         *     summary="Create new Joomla user group",
         *     description="Method restricted to users with core.admin permission.",
         *     operationId="postUserGroupEdit",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Group parent ID",
         *     in="query",
         *     name="parent_id",
         *     required=false,
         *     type="integer",
         *     format="double"
         * ),
         *     @SWG\Parameter(
         *     description="Group Title",
         *     in="query",
         *     name="title",
         *     required=true,
         *     type="string",
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="OK",
         *      @SWG\Schema(
         *      type="string"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="401",
         *     description="Error",
         *      @SWG\Schema(
         *      type="string",
         *      example="<p>""Error: Incorrect format.""</p><p>""Error: Group exists.""</p><p>""Error: Duplicate title or alias.""</p>"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->post('/user/group/edit', function () use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                $groups = JTableUsergroup::getInstance('usergroup', 'JTable');

                $group    = new stdClass();
                $group->parent_id =  $app->request->params('parent_id');
                if($group->parent_id === null || $group->parent_id < 1){
                    $group->parent_id =   1;
                }
                $group->title     =   $app->request->params('title');

                // Bind data
                if (!$groups->bind($group))
                {
                    $app->render(401, array(
                            'msg' => 'Error: Incorrect format.',
                        )
                    );
                }

                // Check to make sure our data is valid, raise notice if it's not.
                if (!$groups->check()) {
                    $app->render(401, array(
                            'msg' => 'Error: Group exists.',
                        )
                    );
                }

                // Now store the group, raise notice if it doesn't get stored.
                if (!$groups->store(TRUE)) {
                    $app->render(401, array(
                            'msg' => 'Error: Duplicate title or alias.',
                        )
                    );
                }

                $groupCreated = array(
                    'id'        => $groups->id,
                    'parent_id' => $groups->parent_id,
                    'title'     =>  $groups->title
                );

                $app->render(200,
                    $groupCreated
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('postUserGroupEdit');

        /**
         * @SWG\Put(
         *     path="/user/group/edit/{id}",
         *     summary="Update existing Joomla user group",
         *     description="Method restricted to users with core.admin permission.",
         *     operationId="putUserGroupEditById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Group  ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="integer",
         *     format="double"
         * ),
         *
         *     @SWG\Parameter(
         *     description="Group parent ID",
         *     in="query",
         *     name="parent_id",
         *     required=false,
         *     type="integer",
         *     format="double"
         * ),
         *     @SWG\Parameter(
         *     description="Group Title",
         *     in="query",
         *     name="title",
         *     required=false,
         *     type="string",
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="OK",
         *      @SWG\Schema(
         *      type="object",
         *     example="{""id"": ""41"",""parent_id"": 1,""title"": ""Test Group A1"",""error"": false,""status"": 200}"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="401",
         *     description="Error",
         *     @SWG\Header(
         *     header="Access-Control-Allow-Headers",
         *     type="string",
         *     description="Allowed headers"
         *      ),
         *     @SWG\Header(
         *     header="Access-Control-Allow-Methods",
         *     type="string",
         *     description="Allowed methods"
         *      ),
         *      @SWG\Schema(
         *      type="string",
         *      example="<p>""Error: Incorrect format.""</p><p>""Error: Group exists.""</p><p>""Error: Duplicate title or alias.""</p>"
         *      ),
         *     @SWG\Property(
         *     type="string",
         *     enum={
         *     "Error: Incorrect format.",
         *     "Error: Group exists.",
         *     "Error: Duplicate title or alias."}
         *     ),
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->put('/user/group/edit/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {

                // Prevent updates to core Joomla groups
                if($id <= 9){
                    $app->render(403, array(
                            'msg' => 'Cannot modify this group',
                        )
                    );
                }

                $groups = JTableUsergroup::getInstance('usergroup', 'JTable');
                $groups->load($id);
                $group    = new stdClass();

                if($app->request->params('parent_id') && $app->request->params('parent_id') >= 1){
                    $group->parent_id = $app->request->params('parent_id');
                }else{
                    $group->parent_id = 1;
                }
                if($app->request->params('title')){
                    $group->title = $app->request->params('title');
                }

                // Bind data
                if (!$groups->bind($group))
                {
                    $app->render(401, array(
                            'msg' => 'Error: Incorrect format.',
                        )
                    );
                }

                // Check to make sure our data is valid, raise notice if it's not.
                if (!$groups->check()) {
                    $app->render(401, array(
                            'msg' => 'Error: Group exists.',
                        )
                    );
                }

                // Now store the article, raise notice if it doesn't get stored.
                if (!$groups->store()) {
                    $app->render(401, array(
                            'msg' => 'Error: Duplicate title or alias.',
                        )
                    );
                }

                $groupCreated = array(
                    'id'        => $groups->id,
                    'parent_id' => $groups->parent_id,
                    'title'     =>  $groups->title
                );

                $app->render(200,
                    $groupCreated
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('putUserGroupEditById');

        /**
         * @SWG\Delete(
         *     path="/user/group/edit/{id}",
         *     summary="Delete existing Joomla user group",
         *     description="Method restricted to users with core.admin permission.",
         *     operationId="deleteUserGroupEditById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Group  ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="integer",
         *     format="double"
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="OK",
         *      @SWG\Schema(
         *      type="string",
         *     example="Deleted group: id"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="401",
         *     description="Error",
         *     @SWG\Header(
         *     header="Access-Control-Allow-Headers",
         *     type="string",
         *     description="Allowed headers"
         *      ),
         *     @SWG\Header(
         *     header="Access-Control-Allow-Methods",
         *     type="string",
         *     description="Allowed methods"
         *      ),
         *     @SWG\Property(
         *     type="string",
         *     enum={
         *     "Error: Incorrect format.",
         *     "Error: Group exists.",
         *     "Error: Duplicate title or alias."}
         *     ),
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->delete('/user/group/edit/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {

                // Prevent updates to core Joomla groups
                if ($id <= 9) {
                    $app->render(403, array(
                            'msg' => 'Cannot modify this group',
                        )
                    );
                }

                $groups = JTableUsergroup::getInstance('usergroup', 'JTable');
                $groups->load($id);
                if ($groups->id === null || $groups->id === '') {
                    $app->render(401, array(
                            'msg' => 'Group id not found: '.$id,
                        )
                    );
                }

                if (!$groups->delete()) {
                    $app->render(401, array(
                            'msg' => 'Error: Duplicate title or alias.',
                        )
                    );
                }
                $app->render(200, array(
                        'msg'   =>  'Deleted group: '.$id
                    )
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('deleteUserGroupEditById');


        /**
         * @SWG\Get(
         *     path="/user/group/list/all",
         *     summary="List all Joomla user groups",
         *     description="Lists all Joomla user groups",
         *     operationId="getUserGroupListAll",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *   @SWG\Response(
         *     response="200",
         *     description="OK",
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/group/list/all', function () use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {

                $db     = JFactory::getDbo();
                $query  = $db->getQuery(true);
                $query->select('id, parent_id, title');
                $query->from('#__usergroups');
                $db->setQuery($query);
                $rows   = $db->loadObjectList();

                $app->render(200, array(
                        'groups_titles'   =>  $rows,
                    )
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('getUserGroupListAll');

        /**
         * @SWG\Get(
         *     path="/user/group/list/users/{id}",
         *     summary="Get list of users by group id",
         *     description="Get list of Joomla users assigned to a group id.
### mode=0
Method to return a list of user Ids contained in a Group.
### mode=1
Method to return a list of user Ids contained in a Group. Recursively include all child groups.",
         *     operationId="getUserGroupListUsersById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Group  ID",
         *     in="path",
         *     name="id",
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
         *     format="double",
         *     enum={0,1}
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns list of users",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/group/list/users/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                $mode = $app->request->params('mode');
                switch ($mode) {
                    case 0:
                        $users['users'] = JAccess::getUsersByGroup($id, false);
                        break;
                    case 1:
                        $users['users'] = JAccess::getUsersByGroup($id, true);
                        break;
                    default:
                        $users['users'] = JAccess::getUsersByGroup($id, false);
                        break;
                }
                $app->render(200,
                        $users
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('getUserGroupListUsersById');

        /**
         * @SWG\Get(
         *     path="/user/group/list/{id}",
         *     summary="Get list of groups by user id",
         *     description="Get list of Joomla groups assigned to a user id.
### mode=0
Method to return a list of user groups mapped to a user. The returned list can optionally hold only
the groups explicitly mapped to the user or all groups both explicitly mapped and inherited by the user.

### mode=1
Method to return a list of user groups mapped to a user. The returned list can optionally hold only
the groups explicitly mapped to the user or all groups both explicitly mapped and inherited by the user.
Include inherited user groups.

### mode=2
Method to return a list of user groups mapped to a user. Does not include inherited user groups.
Include id, title.

### mode=3
Method to return a list of user groups mapped to a user. Does not include inherited user groups.
Include id, parent_id, title

Null mode or out of bounds value defaults to zero.",
         *     operationId="getUserGroupListById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="Group ID",
         *     in="path",
         *     name="id",
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
         *     format="double",
         *     enum={0,1,2,3}
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns list of users",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/group/list/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                $responseArray = array();
                $mode = $app->request->params('mode');
                switch ($mode) {
                    case 0:
                        $responseArray['groups'] = JAccess::getGroupsByUser($id, false);
                        break;
                    case 1:
                        $responseArray['groups'] = JAccess::getGroupsByUser($id, true);
                        break;
                    case 2:
                        $db     = JFactory::getDbo();
                        $groups = JAccess::getGroupsByUser($id);
                        $groupid_list      = '(' . implode(',', $groups) . ')';
                        $query  = $db->getQuery(true);
                        $query->select('id, title');
                        $query->from('#__usergroups');
                        $query->where('id IN ' .$groupid_list);
                        $db->setQuery($query);
                        $rows   = $db->loadObjectList();
                        $responseArray['groups'] = array_values($rows);
                        break;
                    case 3:
                        $db     = JFactory::getDbo();
                        $groups = JAccess::getGroupsByUser($id);
                        $groupid_list      = '(' . implode(',', $groups) . ')';
                        $query  = $db->getQuery(true);
                        $query->select('id, parent_id, title');
                        $query->from('#__usergroups');
                        $query->where('id IN ' .$groupid_list);
                        $db->setQuery($query);
                        $rows   = $db->loadObjectList();
                        $responseArray['groups'] = array_values($rows);
                        break;
                    default:
                        $responseArray['groups'] = JAccess::getGroupsByUser($id, false);
                        break;
                }
                $app->render(200,
                    $responseArray
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('getUserGroupListById');


        /**
         * @SWG\Get(
         *     path="/user/sessions",
         *     summary="Get user sessions",
         *     description="Get list of Joomla user sessions",
         *     operationId="getUserSessions",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *   @SWG\Response(
         *     response="200",
         *     description="Returns list of users",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Forbidden",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/sessions', function() use ($app) {
            $user = JFactory::getUser();

            if ($user->authorise('core.admin')) {
                $query = $app->_db->getQuery(true);
                $query->select('*');
                $query->from($app->_db->quoteName('#__session'));
                $app->_db->setQuery($query);
                $results = $app->_db->loadObjectList();
                $app->render(200,
                    $results
                );
            }
            $app->render(403, array(
                'msg' => 'Not authorized.',
            ));

        })->name('getUserSessions');

        /**
         * @SWG\Get(
         *     path="/user/profile",
         *     summary="Get profile from current user session",
         *     description="Get list of Joomla user sessions",
         *     operationId="getUserProfile",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user profile",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="404",
         *     description="Profile details not found. May need to save them first.",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/profile', function () use ($app)
        {
            $user = JFactory::getUser();
            if($user->guest){
                $app->render(401, array(
                        'msg' => 'Cannot retrieve profile for Guest. Must log in first.',
                    )
                );
            }
            $userProfile = JUserHelper::getProfile( $user->id );
            if($userProfile->profile) {
                $app->render(200, $userProfile->profile);
            }else {
                $app->render(404, array(
                        'msg' => 'Profile details not found. May need to save them first.',
                    )
                );
            }
        })->name('getUserProfile');

        /**
         * @SWG\Get(
         *     path="/user/profile/{id}",
         *     summary="Get user profile by ID",
         *     description="Returns user profile by ID",
         *     operationId="getUserProfileById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="User ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="integer",
         *     format="double"
         * ),
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user profile",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Not authorized",
         *      @SWG\Schema(
         *      type="string"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="404",
         *     description="Profile details not found. May need to save them first.",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->get('/user/profile/:id', function ($id) use ($app)
        {
            $user = JFactory::getUser();
            if($user->authorise('core.admin')) {
                $userProfile = JUserHelper::getProfile( $id );
                if($userProfile->profile) {
                    $app->render(200, $userProfile->profile);
                }else {
                    $app->render(404, array(
                            'msg' => 'Profile details not found. May need to save them first.',
                        )
                    );
                }
            }
            $app->render(403, array(
                'msg' => 'Not authorized',
            ));
        })->name('getUserProfileById');

        /**
         * @SWG\Put(
         *     path="/user/profile",
         *     summary="Update profile from current user session",
         *     description="Updates and returns user profile by current user session.",
         *     operationId="putUserProfile",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     name="profile_aboutme",
         *     in="query",
         *     description="About Me",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_address1",
         *     in="query",
         *     description="Address 1",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_address2",
         *     in="query",
         *     description="Address 2",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_city",
         *     in="query",
         *     description="City",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_country",
         *     in="query",
         *     description="Country",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_dob",
         *     in="query",
         *     description="Date of Birth",
         *     required=false,
         *     type="string",
         *     format="date-time"
         * ),
         *     @SWG\Parameter(
         *     name="profile_favoritebook",
         *     in="query",
         *     description="Favorite Book",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_phone",
         *     in="query",
         *     description="Phone",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_postal_code",
         *     in="query",
         *     description="Postal Code",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_region",
         *     in="query",
         *     description="Region",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_website",
         *     in="query",
         *     description="Website",
         *     required=false,
         *     type="string"
         * ),
         *
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user profile",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Not authorized",
         *      @SWG\Schema(
         *      type="string"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="404",
         *     description="Profile details not found. May need to save them first.",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->put('/user/profile', function () use ($app)
        {
            $user = JFactory::getUser();
            if($user->id) {

                $userProfile = JUserHelper::getProfile($user->id);

                foreach ($app->request()->get() as $key => $value) {
                    $requestArray[$key] = $value;

                    // Create and populate an object.
                    $profile = new stdClass();
                    $profile->user_id = $user->id;
                    $profile->profile_key = preg_replace('/_/', '.', $key, 1);
                    $profile->profile_value = $value;
                    // $profile->ordering=1;

                    $profileKey = str_replace('profile_', '', $key);

                    if (isset( $userProfile->profile[$profileKey] )) {
                        $result = JFactory::getDbo()->updateObject('#__user_profiles', $profile, array('user_id', 'profile_key'));
                    } else {
                        $result = JFactory::getDbo()->insertObject('#__user_profiles', $profile, array('user_id', 'profile_key'));
                    }
                }
                $app->render(200,
                    JUserHelper::getProfile($user->id)->profile
                );
            }
            $app->render(404, array(
                    'msg' => 'User does not exist.',
                )
            );
        })->name('putUserProfile');

        /**
         * Update User Profile by ID
         * Important: User must exist before profile can be updated!
         */

        /**
         * @SWG\Put(
         *     path="/user/profile/{id}",
         *     summary="Update profile by ID",
         *     description="Updates and returns user profile by current user ID.",
         *     operationId="putUserProfileById",
         *     produces={"application/json"},
         *     tags={"User"},
         *
         *     @SWG\Parameter(
         *     description="User ID",
         *     in="path",
         *     name="id",
         *     required=true,
         *     type="integer",
         *     format="double"
         * ),
         *     @SWG\Parameter(
         *     name="profile_aboutme",
         *     in="query",
         *     description="About Me",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_address1",
         *     in="query",
         *     description="Address 1",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_address2",
         *     in="query",
         *     description="Address 2",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_city",
         *     in="query",
         *     description="City",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_country",
         *     in="query",
         *     description="Country",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_dob",
         *     in="query",
         *     description="Date of Birth",
         *     required=false,
         *     type="string",
         *     format="date-time"
         * ),
         *     @SWG\Parameter(
         *     name="profile_favoritebook",
         *     in="query",
         *     description="Favorite Book",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_phone",
         *     in="query",
         *     description="Phone",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_postal_code",
         *     in="query",
         *     description="Postal Code",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_region",
         *     in="query",
         *     description="Region",
         *     required=false,
         *     type="string"
         * ),
         *     @SWG\Parameter(
         *     name="profile_website",
         *     in="query",
         *     description="Website",
         *     required=false,
         *     type="string"
         * ),
         *
         *   @SWG\Response(
         *     response="200",
         *     description="Returns user profile",
         *      @SWG\Schema(
         *      type="object",
         *     example=""
         *      )
         *   ),
         *     @SWG\Response(
         *     response="403",
         *     description="Not authorized",
         *      @SWG\Schema(
         *      type="string"
         *      )
         *   ),
         *     @SWG\Response(
         *     response="404",
         *     description="Profile details not found. May need to save them first.",
         *      @SWG\Schema(
         *      type="string",
         *      )
         *   )
         * )
         */
        $app->put('/user/profile/:id', function ($id) use ($app)
        {
            if(JFactory::getUser($id)->id === null){
                $app->render(404, array(
                        'msg' => 'Not Found',
                    )
                );
            }

            $user = JFactory::getUser();

            if($user->authorise('core.admin')) {
                $userProfile = JUserHelper::getProfile( $id );

                foreach ($app->request->params() as $key => $value) {
                    $requestArray[$key] = $value;

                    // Create and populate an object.
                    $profile = new stdClass();
                    $profile->user_id = $id;
                    $profile->profile_key = preg_replace('/_/', '.', $key, 1);
                    $profile->profile_value = $value;
                    // $profile->ordering=1;
                    
                    $profileKey = str_replace('profile_', '', $key);
                    
                    if (isset( $userProfile->profile[$profileKey] )) {
                        $result = JFactory::getDbo()->updateObject('#__user_profiles', $profile, array('user_id', 'profile_key'));
                    } else {
                        $result = JFactory::getDbo()->insertObject('#__user_profiles', $profile, array('user_id', 'profile_key'));
                    }
                }
                $app->render(200,
                    JUserHelper::getProfile( $id )->profile
                );
            }
            $app->render(403, array(
                    'msg' => 'Forbidden.',
                )
            );
        })->name('putUserProfileById');
    }

    /**
     * @param $id
     * @return stdClass
     * @since 1.0
     */
    public function getUserDetailById($id){
        $user = JFactory::getUser($id);

        $userResponse = new stdClass;
        $userResponse->id               = $user->id;
        $userResponse->username         = $user->username;
        $userResponse->email            = $user->email;
        $userResponse->sendEmail        = $user->sendEmail;
        $userResponse->registerDate     = $user->registerDate;
        $userResponse->lastvisitDate    = $user->lastvisitDate;
        $userResponse->activation       = $user->activation;
        $userResponse->params           = $user->params;
        $userResponse->groups           = $user->groups;
        $userResponse->lastResetTime    = $user->lastResetTime;
        $userResponse->resetCount       = $user->resetCount;
        $userResponse->requireReset     = $user->requireReset;
        
        return $userResponse;
    }

    /**
     * @param $email
     * @return mixed
     * @since 1.0
     */
    public function getUserIdbyEmail($email){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('id'))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('email') . ' = ' . $db->quote($email));
        $db->setQuery($query, 0, 1);

        if($db->loadResult()){
            return $db->loadResult();
        }else{
            return null;
        }
    }

    /**
     * @param $token
     * @return mixed
     * @since 1.0
     */
    public function getUserIdbyToken($token){
        
        $token = str_replace(' ', '+', $token);
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('userid'))
            ->from($db->quoteName('#__services_tokens'))
            ->where($db->quoteName('token') . ' = ' . $db->quote($token));
        $db->setQuery($query, 0, 1);

        if($db->loadResult()){
            return $db->loadResult();
        }else{
            return null;
        }
    }

	/**
	 * JSON Validator
	 * @param null $data
	 *
	 * @return bool
	 * @since 1.3.4
	 */
	protected function json_validator($data=NULL) {
		if (!empty($data)) {
			@json_decode($data);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	function call(){
		return $this->next->call();
	}

}