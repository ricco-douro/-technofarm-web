<?php
/**
 * cAPI "Constant API" Joomla extensions developed by Steve Tsiopanos - Annatech LLC https://www.annatech.com
 * Powered by the Slim Micro Framework http://www.slimframework.com
 *
 * Note: The API service endpoint is configured as a physical directory to ensure the highest URL routing performance
 * and relieve requirements for configuring SEF URLs either within core Joomla or 3rd party extensions like sh404sef.
 *
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

/**
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */
define('JOOMLA_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, JOOMLA_MINIMUM_PHP, '<'))
{
    die('Your host needs to use PHP ' . JOOMLA_MINIMUM_PHP . ' or higher to run this version of Joomla!');
}

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);
define('_API', 1);

if (!defined('_JDEFINES'))
{
    define('JPATH_BASE', dirname(dirname(__DIR__)));
    require_once JPATH_BASE . '/includes/defines.php';
}

if (file_exists(dirname(dirname(__DIR__)). '/includes/defines.php'))
{
    include_once dirname(dirname(__DIR__)). '/includes/defines.php';
}

// Include the Joomla framework
require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . '/libraries/services/autoload.php';

// Set profiler start time and memory usage and mark afterLoad in the profiler.
JDEBUG ? JProfiler::getInstance('Application')->setStart($startTime, $startMem)->mark('afterLoad') : null;


$application = JFactory::getApplication('site');
JLoader::registerPrefix('Services', JPATH_PLUGINS . '/services');

/**
 * @SWG\Info(
 *   title="cAPI REST API",
 *   description="Code named ""Constant API"", cAPI meshes the Slim micro-framework with the Joomla Framework / CMS. By leveraging Joomla's advanced ""pluggable"" architecture and robust ACL, cAPI can transform your website into a true middleware service for anything ranging from SQL servers, MongoDB servers, to Microsoft Active Directory and more!.",
 *   version="1.3.6",
 *   @SWG\Contact(
 *     email="steve.tsiopanos@annatech.com",
 *     name="Steve Tsiopanos",
 *     url="https://www.annatech.com"
 *   ),
 *   @SWG\License(
 *     name="GNU General Public License version 2 or later",
 *     url="http://www.gnu.org/licenses/gpl-2.0.html"
 *   ),
 *   termsOfService="https://www.annatech.com"
 * )
 * @SWG\Swagger(
 *   host=API_HOST,
 *   basePath="/api/v1",
 *   schemes={HTTP_PROTOCOL},
 *   produces={"application/json"},
 *   @SWG\ExternalDocumentation(
 *     description="Find out more about cAPI",
 *     url="https://learn.getcapi.org"
 *   )
 * )
 * @SWG\SecurityScheme(
 *  securityDefinition="token",
 *  type="apiKey",
 *  in="header",
 *  name="token"
 * ),
 * @SWG\SecurityScheme(
 *  securityDefinition="Basic Authentication",
 *  type="basic"
 * ),
 */
$app = new \Slim\Slim(array(
		'mode' => JComponentHelper::getParams('com_services')->get('mode'),
		'slim.debug' => JComponentHelper::getParams('com_services')->get('slim_debug'),
		'cookies.encrypt' => JComponentHelper::getParams('com_services')->get('cookies_encrypt'),
		'rate.limit' => JComponentHelper::getParams('com_services')->get('api_rate_limit')
));

/**
 * CORS handling will be updated in a future release to allow for per-domain whitelisting
 */
header("Access-Control-Allow-Origin: *");

/**
if(isset($_SERVER['HTTP_REFERER'])) {

	header('Access-Control-Allow-Origin: ' . parse_url($_SERVER['HTTP_REFERER'])['scheme'] . '://' . parse_url($_SERVER['HTTP_REFERER'])['host']);
	$app->response->headers->set('Access-Control-Allow-Origin', parse_url($app->request->getReferrer())['scheme'] . '://' . parse_url($app->request->getReferrer())['host']);
}
**/

/**
 * Respond to preflights
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	// return only the headers and not the content
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		$app->response->headers->set('Access-Control-Allow-Origin', '*');
		// $app->response->headers->set('Access-Control-Allow-Origin', parse_url($app->request->getReferrer())['scheme'] . '://' . parse_url($app->request->getReferrer())['host']);
		$app->response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With');
	}
	exit;
}

$app->_db    = JFactory::getDbo();
$app->_input = JFactory::getApplication()->input;

JPluginHelper::importPlugin('services');

$app->view(new plgJsonApiView());
$app->add(new plgJsonApiMiddleware());

$app->run();