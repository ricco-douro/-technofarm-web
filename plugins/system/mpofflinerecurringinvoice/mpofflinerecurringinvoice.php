<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

class plgSystemMPOfflineRecurringInvoice extends JPlugin
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

		$numberSubEachTime = (int) $this->params->get('number_subscribers', 10);
		$numberDays        = (int) $this->params->get('number_days', 10);
		$lastRun           = (int) $this->params->get('last_run', 0);
		$now               = time();
		$cacheTime         = (int) $this->params->get('cache_time', 6) * 3600; // The process will be run every 6 hours

		if (!$this->params->get('debug', 0) && ($now - $lastRun) < $cacheTime)
		{
			return;
		}

		//Store last run time
		$this->params->set('last_run', $now);
		$params = $this->params->toString();
		$query  = $this->db->getQuery(true)
			->update('#__extensions')
			->set('params = ' . $this->db->quote($params))
			->where('`element` = "mpofflinerecurringinvoice"')
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

		$query->clear()
			->select('a.*')
			->from('#__osmembership_subscribers AS a')
			->innerJoin('#__osmembership_plans AS b  ON a.plan_id = b.id')
			->where('a.published = 1')
			->where('a.offline_recurring_email_sent = 0')
			->where('b.recurring_subscription = 1')
			->where('a.payment_method LIKE "os_offline%"')
			->where('DATEDIFF(a.to_date, NOW()) >= 0')
			->where('DATEDIFF(a.to_date, NOW()) <= ' . $numberDays);
		$this->db->setQuery($query, 0, $numberSubEachTime);

		try
		{
			$rows = $this->db->loadObjectList();
		}
		catch (Exception $e)
		{
			$rows = [];
		}

		/* @var OSMembershipModelApi $model */
		$model  = MPFModel::getInstance('Api', 'OSmembershipModel');
		$config = OSMembershipHelper::getConfig();

		$query->clear()
			->update('#__osmembership_subscribers')
			->set('offline_recurring_email_sent = 1');

		foreach ($rows AS $row)
		{
			// Check to see whether the user has renewed before
			$query->clear()
				->select('COUNT(*)')
				->from('#__osmembership_subscribers')
				->where('plan_id = ' . $row->plan_id)
				->where('published = 1')
				->where('id > ' . $row->id)
				->where('((user_id > 0 AND user_id = ' . (int) $row->user_id . ') OR email="' . $row->email . '")');
			$this->db->setQuery($query);
			$total = (int) $this->db->loadResult();

			if (!$total)
			{
				$renewedSubscription = $model->renew($row->id, ['published' => 0], false);
				OSMembershipHelperMail::sendOfflineRecurringEmail($renewedSubscription, $config);
			}

			$query->clear()
				->update('#__osmembership_subscribers')
				->set('offline_recurring_email_sent = 1')
				->where('id = ' . $row->id);
			$this->db->setQuery($query)->execute();
		}
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
