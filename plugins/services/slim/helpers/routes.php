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
 * Class SlimRouteDumper
 * @since 1.0
 */
Class SlimRouteDumper extends \Slim\Router {
    /**
     * @return array
     * @since 1.0
     */
    public static function getAllRoutes() {
        $slim = \Slim\Slim::getInstance();
        return $slim->router->routes;
    }

    /**
     * @return mixed
     * @since 1.0
     */
    function call(){
        return $this->next->call();
    }
}