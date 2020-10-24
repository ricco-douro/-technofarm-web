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
 * Class ServicesRestHelpersBasicauth
 *
 * REST Service plugin - Basic Authentication
 *
 * @since  1.3.4
 */
class ServicesRestHelpersBasicauth {

	protected $app;

	public function __construct()
	{
		$this->app = \Slim\Slim::getInstance();


		if ( !isset($_SERVER['PHP_AUTH_USER']) && $this->app->request->params('basic_auth') === 'true' && JFactory::getUser()->id < 1) {
			header('WWW-Authenticate: Basic realm="cAPI REST API"');
			header('HTTP/1.0 401 Unauthorized');
			exit;
		}

		$this->servicesRestHelpersBasicauth();
	}

	/**
	 * Basic Authentication
	 *
	 * @return bool
	 * @since 1.3.4
	 */
	public function servicesRestHelpersBasicauth()
	{
		/**
		 * Note that token authentication will always override Basic Authentication in the event that both are used in a
		 * single request.
		 */
		if(is_null($this->app->request->params('token')) && is_null($this->app->request->headers->get('token')) ) {
			if($this->app->request->headers->get('Php-Auth-User') && $this->app->request->headers->get('Php-Auth-Pw')){
				$this->authBasic(
					$this->app->request->headers->get('Php-Auth-User'),
					$this->app->request->headers->get('Php-Auth-Pw')
				);
			}
		}
	}

	/**
	 * @param $username
	 * @param $password
	 *
	 * @return array|bool
	 * @since 1.3.4
	 */
	public function authBasic($username,$password)
	{
		$credentials = array('username' => $username, 'password' => $password);
		$login = JFactory::$application->login($credentials);

		if($login){
			$userid = JUserHelper::getUserId($username);

			$user     = JFactory::getUser($userid);
			$username = $user->get('username');

			$result = array();

			if ((isset($result[0]) || JFactory::getSession()->get('user')->id === $userid))
			{
				// Get session
				$result['sessionid'] = JFactory::getSession()->getId();
				$this->app->response->headers->set('Joomla-Sessionid', JFactory::getSession()->getId());

				// Save to Slim instance settings.
				$this->app->config('userid', $userid);
				$this->app->config('username', $username);

			}
			$this->app->response->setStatus(200);
			return $result;
		}

		if ($this->app->request->params('basic_auth') === 'true')
		{
			header('WWW-Authenticate: Basic realm="cAPI REST API"');
			header('HTTP/1.0 401 Unauthorized');
			exit;
		}
		return false;
	}

	/**
	 * Check if $string is a Base64 encoded string
	 *
	 * @param $string
	 *
	 * @return bool
	 * @since 1.3.4
	 */
	protected function isBase64($string){
		if ( base64_encode(base64_decode($string, true)) !== $string){
			return false;
		}
	}

	function call(){
		return $this->next->call();
	}

}