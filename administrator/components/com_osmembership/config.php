<?php
/**
 * @package        Joomla
 * @subpackage     Payment Form
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2010 - 2015 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$config = [
	'default_controller_class' => 'OSMembershipController',
	'class_prefix'             => 'OSMembership',
	'language_prefix'          => 'OSM'
];


if (JFactory::getApplication()->isAdmin())
{
	$config['default_view'] = 'dashboard';
}
else
{
	$config['default_view'] = 'plans';
}

return $config;
