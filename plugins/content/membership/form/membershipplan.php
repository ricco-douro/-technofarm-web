<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class JFormFieldMembershipplan extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $_name = 'plan';

	public function getInput()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title')
			->from('#__osmembership_plans')
			->where('published = 1')
			->order('title');

		$db->setQuery($query);

		return JHtml::_('select.genericlist', $db->loadObjectList(), $this->name . '[]', ' multiple="multiple" ', 'id', 'title', $this->value);
	}
}
