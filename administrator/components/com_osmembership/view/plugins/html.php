<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

/**
 * HTML View class for Quick Gallery component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewPluginsHtml extends MPFViewList
{
	/**
	 * Method to add toolbar buttons
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('OSM_PLUGINS_MANAGEMENT'));
		JToolBarHelper::deleteList(JText::_('OSM_PLUGIN_UNINSTALL_CONFIRM'), 'uninstall', 'Uninstall');
		JToolbarHelper::publish('publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}
