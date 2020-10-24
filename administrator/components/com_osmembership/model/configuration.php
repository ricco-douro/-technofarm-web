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
 * Membership Pro Component Configuration Model
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipModelConfiguration extends MPFModel
{
	/**
	 * Store the configuration data
	 *
	 * @param array $data
	 */
	public function store($data)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$db->truncateTable('#__osmembership_configs');
		$row = $this->getTable('Config');
		foreach ($data as $key => $value)
		{
			$row->id = 0;
			if (is_array($value))
			{
				$value = implode(',', $value);
			}
			$row->config_key   = $key;
			$row->config_value = $value;
			$row->store();
		}
		if ($data['create_account_when_membership_active'])
		{
			//Need to activate the account creation plugin
			$query->update('#__extensions')
				->set('`enabled` = 1')
				->set('`ordering` = -1')
				->where('`element`="account" AND `folder`="osmembership"');
			$db->setQuery($query);
			$db->execute();
		}
		else
		{
			//We should disable this plugin
			$query->update('#__extensions')
				->set('`enabled` = 0')
				->where('`element`="account" AND `folder`="osmembership"');
			$db->setQuery($query);
			$db->execute();
		}

		if (isset($data['custom_css']))
		{
			JFile::write(JPATH_ROOT.'/media/com_osmembership/assets/css/custom.css', trim($data['custom_css']));
		}
	}
}
