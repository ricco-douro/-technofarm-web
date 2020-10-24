<?php
/**
 * Services - REST Plugin
 * @version     1.3.6
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @package		Joomla.Plugin
 * @subpakage	Annatech.Joomla
 */
defined('_JEXEC') or die( 'Restricted access' );

class plgServicesRest extends \Slim\Middleware
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
     * plgServicesRest constructor.
     * @since 1.0
     */
    public function __construct()
    {
        $lang = JFactory::getLanguage();
        $lang->load('plg_services_rest', __DIR__);
	    new ServicesRestHelpersBasicauth();
        new ServicesRestHelpersToken();
        new ServicesRestHelpersTokenmanage();
    }

    /**
     * @return mixed
     * @since 1.0
     */
    function call(){
        return $this->next->call();
    }
}