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
 * HTML View class for OS Membership component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 * @since 1.5
 */
class OSMembershipViewRules extends OSViewList
{
	public function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published=1 ORDER BY ordering ';
		$db->setQuery($sql);
		$plans = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_FROM_PLAN'), 'id', 'title');
		$options = array_merge($options, $plans);
		$lists['from_plan_id'] = JHtml::_('select.genericlist', $options, 'from_plan_id', ' class="inputbox" onchange="submit();"', 'id', 'title',
			$state->from_plan_id);

		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_TO_PLAN'), 'id', 'title');
		$options = array_merge($options, $plans);
		$lists['to_plan_id'] = JHtml::_('select.genericlist', $options, 'to_plan_id', ' class="inputbox" onchange="submit();"', 'id', 'title',
			$state->to_plan_id);
	}
}
