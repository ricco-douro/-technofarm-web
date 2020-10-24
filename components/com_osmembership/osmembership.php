<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

// Require library + register autoloader
require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

// Prepare request data
OSMembershipHelper::prepareRequestData();
$input = new MPFInput();
$task  = $input->getCmd('task', '');

// Handle BC for existing payment plugins
if (in_array($task, ['payment_confirm', 'recurring_payment_confirm']))
{
	$input->set('task', 'register.' . $task);
}

$config = include JPATH_ADMINISTRATOR . '/components/com_osmembership/config.php';

MPFController::getInstance($input->getCmd('option', null), $input, $config)
	->execute()
	->redirect();
