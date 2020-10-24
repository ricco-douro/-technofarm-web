<?php
/**
 * jsonAPI - Slim extension to implement fast JSON API's
 *
 * @version 1.3.6
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @package Services
 * @subpackage Slim Middleware
 * @author Steve Tsiopanos <steve.tsiopanos@annatech.com>
 * @filesource
 * @since 1.0
 *
*/

/**
 * Class plgJsonApiMiddleware
 *
 * @since 1.0
 */
class plgJsonApiMiddleware extends \Slim\Middleware {
    /**
     * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

	/**
	 * Set static API calls
	 * plgJsonApiMiddleware constructor.
	 * @since 1.0
	 */
    public function __construct()
    {
        /**
         * Load Joomla plugin language files.
         */
        $lang = JFactory::getLanguage();
        $lang->load('slimjsonapimiddleware', dirname(__FILE__));

        $app = \Slim\Slim::getInstance();
        $app->config('debug', false);

        // Mirrors the API request
        $app->get('/return', function() use ($app) {

            $app->render(200,array(
                'method'    => $app->request()->getMethod(),
                'name'      => $app->request()->get('name'),
                'headers'   => $app->request()->headers(),
                'params'    => $app->request()->params(),
            ));
        });

        // Generic error handler
        $app->error(function (Exception $e) use ($app) {
            $app->render($e->getCode(),array(
                'error' => true,
                'msg'   => $this->_errorType($e->getCode()) .": ". $e->getMessage(),
            ));
        });

        // Not found handler (invalid routes, invalid method types)
        $app->notFound(function() use ($app) {
            $app->render(404,array(
                'error' => TRUE,
                'msg'   => 'Invalid route',
            ));
        });

        // Handle Empty response body
        $app->hook('slim.after.router', function () use ($app) {
            //Fix sugested by: https://github.com/bdpsoft
            //Will allow download request to flow
            if($app->response()->header('Content-Type')==='application/octet-stream'){
                return;
            }

            if (strlen($app->response()->body()) == 0) {
                $app->render(500,array(
                    'error' => TRUE,
                    'msg'   => 'Empty response',
                ));
            }
        });

    }

	/**
	 * Call next
	 *
	 * @return mixed
	 * @since 1.0
	 */
    function call(){
        return $this->next->call();
    }

	/**
	 * @param int $type
	 *
	 * @return string
	 * @since 1.0
	 */
    public static function _errorType($type=1){
        switch($type)
        {
            default:
            case E_ERROR: // 1 //
                return 'ERROR';
            case E_WARNING: // 2 //
                return 'WARNING';
            case E_PARSE: // 4 //
                return 'PARSE';
            case E_NOTICE: // 8 //
                return 'NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'CORE_WARNING';
            case E_CORE_ERROR: // 64 //
                return 'COMPILE_ERROR';
            case E_CORE_WARNING: // 128 //
                return 'COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'USER_DEPRECATED';
        }
    }
}
