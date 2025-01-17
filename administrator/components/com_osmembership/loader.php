<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

define('OSM_DEFAULT_RENEW_OPTION_ID', 999);

/**
 * Re-register prefix and classes for auto-loading
 */
JLoader::registerPrefix('MPF', JPATH_ADMINISTRATOR . '/components/com_osmembership/libraries/mpf');
JLoader::registerPrefix('OSMembership', JPATH_BASE . '/components/com_osmembership');
JLoader::register('os_payments', JPATH_ROOT . '/components/com_osmembership/plugins/os_payments.php');
JLoader::register('os_payment', JPATH_ROOT . '/components/com_osmembership/plugins/os_payment.php');
JLoader::register('JFolder', JPATH_LIBRARIES . '/joomla/filesystem/folder.php');
JLoader::register('JFile', JPATH_LIBRARIES . '/joomla/filesystem/file.php');

JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_osmembership/table');

if (JFactory::getApplication()->isAdmin())
{
	JLoader::register('OSMembershipModelSubscriptiontrait', JPATH_ROOT . '/components/com_osmembership/model/subscriptiontrait.php');
	JLoader::register('OSMembershipModelValidationtrait', JPATH_ROOT . '/components/com_osmembership/model/validationtrait.php');
	JLoader::register('OSMembershipModelApi', JPATH_ROOT . '/components/com_osmembership/model/api.php');
	JLoader::register('OSMembershipModelOverrideApi', JPATH_ROOT . '/components/com_osmembership/model/override/api.php');

	JLoader::register('OSMembershipHelper', JPATH_ROOT . '/components/com_osmembership/helper/helper.php');
	JLoader::register('OSMembershipHelperDatabase', JPATH_ROOT . '/components/com_osmembership/helper/database.php');
	JLoader::register('OSMembershipHelperHtml', JPATH_ROOT . '/components/com_osmembership/helper/html.php');
	JLoader::register('OSMembershipHelperEuvat', JPATH_ROOT . '/components/com_osmembership/helper/euvat.php');
	JLoader::register('OSMembershipHelperJquery', JPATH_ROOT . '/components/com_osmembership/helper/jquery.php');
	JLoader::register('OSMembershipHelperMail', JPATH_ROOT . '/components/com_osmembership/helper/mail.php');
	JLoader::register('OSMembershipHelperSubscription', JPATH_ROOT . '/components/com_osmembership/helper/subscription.php');
	JLoader::register('OSMembershipHelperData', JPATH_ROOT . '/components/com_osmembership/helper/data.php');
	JLoader::register('OSMembershipHelperBootstrap', JPATH_ROOT . '/components/com_osmembership/helper/bootstrap.php');
	JLoader::register('OSMembershipHelperRoute', JPATH_ROOT . '/components/com_osmembership/helper/route.php');


	// Register override classes
	$possibleOverrides = array(
		'OSMembershipHelperOverrideHelper' => 'helper.php',
		'OSMembershipHelperOverrideMail'   => 'mail.php',
		'OSMembershipHelperOverrideJquery' => 'jquery.php',
		'OSMembershipHelperOverrideData'   => 'data.php',
	);

	foreach ($possibleOverrides as $className => $filename)
	{
		JLoader::register($className, JPATH_ROOT . '/components/com_osmembership/helper/override/' . $filename);
	}
}

// Validator
/** @var \Composer\Autoload\ClassLoader $autoLoader */
$autoLoader = include JPATH_LIBRARIES . '/vendor/autoload.php';
$autoLoader->setPsr4('Valitron\\', JPATH_ADMINISTRATOR . '/components/com_osmembership/libraries/vendor/valitron/src/Valitron');

// Disable STRICT_TRANS_TABLES mode required in Joomla 4
if (version_compare(JVERSION, '4.0.0-dev', 'ge'))
{
	$db = JFactory::getDbo();
	$db->setQuery("SET sql_mode=(SELECT REPLACE(@@sql_mode,'STRICT_TRANS_TABLES',''));");
	$db->execute();
}

$config = OSMembershipHelper::getConfig();

if (empty($config->debug))
{
	error_reporting(0);	
}
else
{
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
}