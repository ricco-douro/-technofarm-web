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

/**
 * Class ServicesJoomlaCms
 * @since 1.2.9
 */
class ServicesJoomlaHelpersCms {

    /**
     * cmsServicesJoomla constructor.
     * @since 1.0
     */
    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        /**
         * Main entry
         */
        $app->get('/cms', function () use ($app)
        {

            $user = JFactory::getUser();
            $name = !$user->guest ? $user->name : 'guest';

            $app->render(
                200, array(
                    'msg' => 'Welcome' . ' ' . $name,
                )
            );
        }
        );

        /**
         * @SWG\Get(
         *     path="/cms/version/{option}",
         *     summary="Joomla CMS version information",
         *     description="
Return Joomla CMS version information in different formats
#### Additional Information
<ul>
<li>shortversion</li>
<p>Gets a PHP standardized version string for the current Joomla.</p>
<li>longversion</li>
<p>Gets a version string for the current Joomla with all release information.</p>
<li>useragent</li>
<p>Returns the user agent.</p>
<li>helpversion</li>
<p>Method to get the help file version.</p>
",
         *     operationId="getCmsVersionByOption",
         *     tags={"CMS"},
         *
         *     @SWG\Parameter(
         *     description="Version search type.",
         *     in="path",
         *     name="option",
         *     required=true,
         *     enum={"shortversion","longversion","useragent","helpversion"},
         *     type="string"
         * ),
         *
         *     @SWG\Response(
         *     response="200",
         *     description="Success."
         *   ),
         *   @SWG\Response(
         *     response="403",
         *     description="Guests cannot view version information."
         *   )
         * )
         */
        $app->get('/cms/version/:option', function ($option) use ($app)
        {
            $user = JFactory::getUser();
            if(!$user->guest) {
                $version = new JVersion();

                switch(strtolower($option)) {
                    // Gets a "PHP standardized" version string for the current Joomla.
                    case "shortversion":
                        $response['shortVersion'] = $version->getShortVersion();
                        break;
                    // Gets a version string for the current Joomla with all release information.
                    case "longversion":
                        $response['longVersion'] = $version->getLongVersion();
                        break;
                    // Returns the user agent.
                    case "useragent":
                        $response['userAgent'] = $version->getUserAgent(null,true,true);
                        break;
                    // Method to get the help file version.
                    case "helpversion":
                        $response['helpVersion'] = $version->getHelpVersion();
                        break;
                    default:
                        $response['shortVersion'] = $version->getShortVersion();
                }
                $app->render(
                    200,
                        $response
                );
            }
            $app->render(
                403,array(
                    'msg' => 'Guests cannot view version information.'
                )
            );

        }
        )->name("getCmsVersionByOption");

    }

    function call(){
        return $this->next->call();
    }

}