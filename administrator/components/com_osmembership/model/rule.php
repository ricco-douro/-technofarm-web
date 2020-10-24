<?php
/**
 * @version		1.6.2
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

/**
 * EventBooking Component Picture Model
 *
 * @package        Joomla
* @subpackage	Quick Gallery
 * @since 1.5
 */
class OSMembershipModelRule extends OSModel
{
	public function __construct($config)
	{
		$config['table_name'] = '#__osmembership_upgraderules';

		parent::__construct($config);
	}
}
