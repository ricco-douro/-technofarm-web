<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class plgSystemRegistrationRedirect extends JPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 */
	protected $app;
	/**
	 * Database object
	 *
	 * @var JDatabaseDriver
	 */
	protected $db;

	/**
	 * Redirect users to Membership Pro when someone tries to register for other extensions
	 *
	 * @return bool
	 */
	public function onAfterRoute()
	{
		if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php'))
		{
			return;
		}

		// Require library + register autoloader
		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$input  = $this->app->input;
		$option = $input->getCmd('option');
		$task   = $input->getCmd('task');
		$view   = $input->getCmd('view');

		// Registration redirect
		if (($option == 'com_users' && $view == 'registration' && empty($task))
			|| ($option == 'com_comprofiler' && ($task == 'registers' || $view == 'registers'))
			|| ($option == 'com_community' && $view == 'register')
			|| ($option == 'com_users' && $task == 'registration.register')
		)
		{
			$url = $this->params->get('redirect_url', OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register')));

			if (!$url)
			{
				$Itemid = OSMembershipHelper::getItemid();
				$url    = JRoute::_('index.php?option=com_osmembership&view=plans&Itemid=' . $Itemid);
			}

			$this->app->redirect($url);
		}

		// In case users enter email to login, we can convert it to username if needed
		$config = OSMembershipHelper::getConfig();

		if (!empty($config->use_email_as_username) && $option == 'com_users' && $task == 'user.login')
		{
			$method   = $input->getMethod();
			$username = $input->$method->get('username', '', 'USERNAME');

			if (JMailHelper::isEmailAddress($username))
			{
				$query = $this->db->getQuery(true);

				$query->select('*')
					->from('#__users')
					->where('(username = ' . $this->db->quote($username) . ' OR email=' . $this->db->quote($username) . ')');
				$this->db->setQuery($query);
				$user = $this->db->loadObject();

				if ($user && ($user->username != $username))
				{
					$input->$method->set('username', $user->username);
				}
			}
		}

		return true;
	}
}
