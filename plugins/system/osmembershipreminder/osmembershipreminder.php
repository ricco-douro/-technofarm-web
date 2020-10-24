<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class plgSystemOSMembershipReminder extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var JDatabaseDriver
	 */
	protected $db;


	/**
	 * The sending reminder emails is triggered after the page has fully rendered.
	 *
	 * @return  void
	 *
	 * @since   2.0.0
	 */
	public function onAfterRender()
	{
		if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php'))
		{
			return;
		}

		$bccEmail                = $this->params->get('bcc_email', '');
		$numberEmailSendEachTime = (int) $this->params->get('number_subscribers', 5);
		$lastRun                 = (int) $this->params->get('last_run', 0);
		$now                     = time();
		$cacheTime               = 7200; // The reminder process will be run every 2 hours

		if (!$this->params->get('debug', 0) && ($now - $lastRun) < $cacheTime)
		{
			return;
		}

		//Store last run time
		$query = $this->db->getQuery(true);
		$this->params->set('last_run', $now);
		$params = $this->params->toString();
		$query->clear();
		$query->update('#__extensions')
			->set('params=' . $this->db->quote($params))
			->where('`element`="osmembershipreminder"')
			->where('`folder`="system"');

		try
		{
			// Lock the tables to prevent multiple plugin executions causing a race condition
			$this->db->lockTable('#__extensions');
		}
		catch (Exception $e)
		{
			// If we can't lock the tables it's too risk continuing execution
			return;
		}

		try
		{
			// Update the plugin parameters
			$result = $this->db->setQuery($query)->execute();
			$this->clearCacheGroups(array('com_plugins'), array(0, 1));
		}
		catch (Exception $exc)
		{
			// If we failed to execite
			$this->db->unlockTables();
			$result = false;
		}
		try
		{
			// Unlock the tables after writing
			$this->db->unlockTables();
		}
		catch (Exception $e)
		{
			// If we can't lock the tables assume we have somehow failed
			$result = false;
		}
		// Abort on failure
		if (!$result)
		{
			return;
		}

		// Require library + register autoloader
		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$message = OSMembershipHelper::getMessages();

		try
		{
			$query->clear()
				->select('a.*, b.title AS plan_title, b.recurring_subscription, b.number_payments, c.username')
				->select('IF(b.send_first_reminder > 0, DATEDIFF(to_date, NOW()), DATEDIFF(NOW(), to_date)) AS number_days')
				->from('#__osmembership_subscribers AS a')
				->innerJoin('#__osmembership_plans AS b  ON a.plan_id = b.id')
				->leftJoin('#__users AS c  ON a.user_id = c.id')
				->where('b.send_first_reminder != 0')
				->where('b.lifetime_membership != 1')
				->where('a.published IN (1, 2)')
				->where('a.first_reminder_sent = 0')
				->where('a.group_admin_id = 0')
				->where('b.send_first_reminder != 0')
				->where('IF(b.send_first_reminder > 0, b.send_first_reminder >= DATEDIFF(to_date, NOW()) AND DATEDIFF(to_date, NOW()) >= 0, DATEDIFF(NOW(), to_date) >= ABS(b.send_first_reminder) AND DATEDIFF(NOW(), to_date) <= 60)')
				->order('a.to_date');
			$this->db->setQuery($query, 0, $numberEmailSendEachTime);

			try
			{
				$rows = $this->db->loadObjectList();

				if (!empty($rows))
				{
					OSMembershipHelperMail::sendReminderEmails($rows, $bccEmail, 1);
				}
			}
			catch (Exception $e)
			{

			}

			$query->clear()
				->select('a.*, b.title AS plan_title, b.recurring_subscription, b.number_payments, c.username')
				->select('IF(b.send_second_reminder > 0, DATEDIFF(to_date, NOW()), DATEDIFF(NOW(), to_date)) AS number_days')
				->from('#__osmembership_subscribers AS a')
				->innerJoin('#__osmembership_plans AS b ON a.plan_id = b.id')
				->leftJoin('#__users AS c  ON a.user_id = c.id')
				->where('b.send_second_reminder != 0')
				->where('b.lifetime_membership != 1')
				->where('a.published IN (1, 2)')
				->where('a.second_reminder_sent = 0')
				->where('a.group_admin_id = 0')
				->where('b.send_second_reminder != 0')
				->where('IF(b.send_second_reminder > 0, b.send_second_reminder >= DATEDIFF(to_date, NOW()) AND DATEDIFF(to_date, NOW()) >= 0, DATEDIFF(NOW(), to_date) >= ABS(b.send_second_reminder) AND DATEDIFF(NOW(), to_date) <= 60)')
				->order('a.to_date');
			$this->db->setQuery($query, 0, $numberEmailSendEachTime);

			try
			{
				$rows = $this->db->loadObjectList();

				if (!empty($rows))
				{
					OSMembershipHelperMail::sendReminderEmails($rows, $bccEmail, 2);
				}
			}
			catch (Exception $e)
			{

			}

			$query->clear()
				->select('a.*, b.title AS plan_title, b.recurring_subscription, b.number_payments, c.username')
				->select('IF(b.send_third_reminder > 0, DATEDIFF(to_date, NOW()), DATEDIFF(NOW(), to_date)) AS number_days')
				->from('#__osmembership_subscribers AS a')
				->innerJoin('#__osmembership_plans AS b ON a.plan_id = b.id')
				->leftJoin('#__users AS c  ON a.user_id = c.id')
				->where('b.send_third_reminder != 0')
				->where('b.lifetime_membership != 1')
				->where('a.published IN (1, 2)')
				->where('a.third_reminder_sent = 0')
				->where('a.group_admin_id = 0')
				->where('b.send_third_reminder != 0')
				->where('IF(b.send_third_reminder > 0, b.send_third_reminder >= DATEDIFF(to_date, NOW()) AND DATEDIFF(to_date, NOW()) >= 0, DATEDIFF(NOW(), to_date) >= ABS(b.send_third_reminder) AND DATEDIFF(NOW(), to_date) <= 60 )')
				->order('a.to_date');
			$this->db->setQuery($query, 0, $numberEmailSendEachTime);

			try
			{
				$rows = $this->db->loadObjectList();

				if (!empty($rows))
				{
					OSMembershipHelperMail::sendReminderEmails($rows, $bccEmail, 3);
				}
			}
			catch (Exception $e)
			{

			}

			if (empty($message->subscription_end_email_subject))
			{
				return;
			}

			// Subscription end
			$query->clear()
				->select('a.*, b.title AS plan_title, b.recurring_subscription, b.number_payments, c.username')
				->select('IF(b.send_subscription_end > 0, DATEDIFF(to_date, NOW()), DATEDIFF(NOW(), to_date)) AS number_days')
				->from('#__osmembership_subscribers AS a')
				->innerJoin('#__osmembership_plans AS b ON a.plan_id = b.id')
				->leftJoin('#__users AS c  ON a.user_id = c.id')
				->where('b.send_subscription_end != 0')
				->where('b.recurring_subscription = 1')
				->where('b.number_payments > 0')
				->where('a.published IN (1, 2)')
				->where('a.subscription_end_sent = 0')
				->where('a.group_admin_id = 0')
				->where('a.payment_made = b.number_payments')
				->where('IF(b.send_subscription_end > 0, b.send_subscription_end >= DATEDIFF(to_date, NOW()) AND DATEDIFF(to_date, NOW()) >= 0, DATEDIFF(NOW(), to_date) >= ABS(b.send_subscription_end) AND DATEDIFF(NOW(), to_date) <= 60 )')
				->order('a.to_date');
			$this->db->setQuery($query, 0, $numberEmailSendEachTime);

			try
			{
				$rows = $this->db->loadObjectList();

				if (!empty($rows))
				{
					OSMembershipHelperMail::sendSubscriptionEndEmails($rows, $bccEmail);
				}
			}
			catch (Exception $e)
			{

			}
		}
		catch (Exception $e)
		{
			// Ignore
		}

		return true;
	}

	/**
	 * Clears cache groups. We use it to clear the plugins cache after we update the last run timestamp.
	 *
	 * @param   array $clearGroups  The cache groups to clean
	 * @param   array $cacheClients The cache clients (site, admin) to clean
	 *
	 * @return  void
	 *
	 * @since   2.0.0
	 */
	private function clearCacheGroups(array $clearGroups, array $cacheClients = array(0, 1))
	{
		$conf = JFactory::getConfig();
		foreach ($clearGroups as $group)
		{
			foreach ($cacheClients as $client_id)
			{
				try
				{
					$options = array(
						'defaultgroup' => $group,
						'cachebase'    => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' :
							$conf->get('cache_path', JPATH_SITE . '/cache'),
					);
					$cache   = JCache::getInstance('callback', $options);
					$cache->clean();
				}
				catch (Exception $e)
				{
					// Ignore it
				}
			}
		}
	}
}
