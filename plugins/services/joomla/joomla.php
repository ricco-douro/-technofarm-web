<?php
/**
 * Services - Joomla Plugin
 * @version     1.3.6
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @package		Joomla.Plugin
 * @subpakage	Annatech.Joomla
 */
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Class plgServicesJoomla
 * @since 1.0
 */
class plgServicesJoomla extends \Slim\Middleware
{
    /**
     * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    public function __construct()
    {
        $lang = JFactory::getLanguage();
        $lang->load('plg_services_joomla', __DIR__);
        new ServicesJoomlaHelpersUser();
        new ServicesJoomlaHelpersCms();
        new ServicesJoomlaHelpersContent();
        new ServicesJoomlaHelpersTag();
        new ServicesJoomlaHelpersMain();
	    new ServicesJoomlaHelpersComponent();
    }

    function call(){
        return $this->next->call();
    }
}