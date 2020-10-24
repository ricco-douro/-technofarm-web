<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OsMembershipViewMessageHtml extends MPFViewHtml
{
	public function display()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_plugins')
			->where('name LIKE "os_offline_%"');
		$db->setQuery($query);

		$this->extraOfflinePlugins = $db->loadObjectList();
		$this->item                = OSMembershipHelper::getMessages();
		$this->languages           = OSMembershipHelper::getLanguages();

		parent::display();
	}
}
