<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;


class plgSystemMembershipPro extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var JDatabaseDriver
	 */
	protected $db;

	/**
	 * Flag to see whether the plan subscription status for this record has been processed or not
	 *
	 * @var bool
	 */
	private $subscriptionProcessed = false;

	/**
	 * Whether the plugin should be run when events are triggered
	 *
	 * @var bool
	 */
	protected $canRun;

	/**
	 * Constructor
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		$this->canRun = file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php');

		if ($this->canRun)
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';
		}
	}

	/**
	 * This method is run after subscription record is successfully stored in database
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onAfterStoreSubscription($row)
	{
		// Set profile data (is_profile, profile_id) for the subscription
		$this->setSubscriptionProfileData($row);

		// Set plan main record data for the subscription
		$this->setPlanMainRecordData($row);

		if (strpos($row->payment_method, 'os_offline') !== false)
		{
			$config = OSMembershipHelper::getConfig();

			// Generate invoice for offline payment
			if ($config->activate_invoice_feature)
			{
				$this->generateInvoiceNumber($row);
			}

			// Generate Membership ID for offline payment subscription
			if ($config->auto_generate_membership_id)
			{
				$this->generateMembershipId($row);
			}
		}

		// Store the modified data for the subscription back to database
		$row->store();
	}

	/**
	 * This method is run after subscription become active, ie after user complete payment or admin approve the subscription
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @throws Exception
	 */
	public function onMembershipActive($row)
	{
		$config = OSMembershipHelper::getConfig();

		// Create user account (in case the system is configured to generate user account when subscription is active)
		if (!$row->user_id && $row->username && $row->user_password)
		{
			$this->createUserAccount($row);
		}

		// Activate user account when subscription active (in case the system is configured to not send activation email)
		if (!$config->send_activation_email)
		{
			$this->activateUserAccount($row);
		}

		// In case system is configured to only has one subscription record for each plan, update the subscription
		if ($row->act == 'renew' && $config->subscription_renew_behavior == 'update_subscription' && $row->user_id > 0)
		{
			$this->updateSubscriptionOnRenew($row);
		}

		/*
		 * Generate invoice for the subscription if it was not generated before (For example, when admin approve the
		 * offline payment subscription
		 */
		if ($config->activate_invoice_feature && !$row->group_admin_id && !$row->invoice_number)
		{
			$this->generateInvoiceNumber($row);
		}

		/*
		 * Generate Membership ID for the subscription if it was not generated before (For example, when admin approve
		 * the offline payment subscription
		 */
		if ($config->auto_generate_membership_id && !$row->membership_id)
		{
			$this->generateMembershipId($row);
		}

		// Store modified subscription data back to database
		$row->store();

		$this->subscriptionProcessed = true;

		if ($row->group_admin_id == 0)
		{
			$this->updateSubscriptionExpiredDate($row);

			$this->updatePlanSubscriptionStatus($row);

			$this->updateSendingReminderStatus($row);
		}
	}

	/**
	 * Block the user account when membership is expired
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @return bool
	 */
	public function onMembershipExpire($row)
	{
		if ($row->user_id && $this->params->get('block_account_when_expired', 0))
		{
			$user = JFactory::getUser($row->user_id);

			if (!$user->authorise('core.admin'))
			{
				$user->set('block', 1);
				$user->save(true);
			}
		}

		$this->subscriptionProcessed = true;

		if (!$row->group_admin_id)
		{
			$this->updatePlanSubscriptionStatus($row);
		}

		return true;
	}

	/**
	 * Recalculate some important subscription information when a subscription record is being deleted
	 *
	 * @param string                      $context
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onSubscriptionAfterDelete($context, $row)
	{
		if ($row->profile_id > 0 && $row->plan_id > 0)
		{
			$query = $this->db->getQuery(true);

			$query->clear()
				->select('id, profile_id, plan_id, published, from_date, to_date')
				->from('#__osmembership_subscribers')
				->where('plan_id = ' . $row->plan_id)
				->where('profile_id = ' . $row->profile_id)
				->where('(published >= 1 OR payment_method LIKE "os_offline%")')
				->order('id');
			$this->db->setQuery($query);
			$subscriptions = $this->db->loadObjectList();

			if (!empty($subscriptions))
			{
				$isActive         = false;
				$isPending        = false;
				$isExpired        = false;
				$lastActiveDate   = null;
				$lastExpiredDate  = null;
				$planMainRecordId = 0;
				$planFromDate     = $subscriptions[0]->from_date;

				foreach ($subscriptions as $subscription)
				{
					if ($subscription->plan_main_record)
					{
						$planMainRecordId = $subscription->id;
					}

					if ($subscription->published == 1)
					{
						$isActive       = true;
						$lastActiveDate = $subscription->to_date;
					}
					elseif ($subscription->published == 0)
					{
						$isPending = true;
					}
					elseif ($subscription->published == 2)
					{
						$isExpired       = true;
						$lastExpiredDate = $subscription->to_date;
					}
				}

				if ($isActive)
				{
					$published  = 1;
					$planToDate = $lastActiveDate;
				}
				elseif ($isPending)
				{
					$published = 0;
				}
				elseif ($isExpired)
				{
					$published  = 2;
					$planToDate = $lastExpiredDate;
				}
				else
				{
					$published  = 3;
					$planToDate = $subscription->to_date;
				}

				$query->clear()
					->update('#__osmembership_subscribers')
					->set('plan_subscription_status = ' . (int) $published)
					->set('plan_subscription_from_date = ' . $this->db->quote($planFromDate))
					->set('plan_subscription_to_date = ' . $this->db->quote($planToDate))
					->where('plan_id = ' . $row->plan_id)
					->where('profile_id = ' . $row->profile_id);
				$this->db->setQuery($query);
				$this->db->execute();

				if (empty($planMainRecordId))
				{
					$planMainRecordId = $subscriptions[0]->id;
					$query->clear()
						->update('#__osmembership_subscribers')
						->set('plan_main_record = 1')
						->where('id = ' . $planMainRecordId);
					$this->db->setQuery($query);
					$this->db->execute();
				}
			}
		}

		if ($row->is_profile == 1 && $row->user_id > 0)
		{
			// We need to fix the profile record
			OSMembershipHelperSubscription::fixProfileId($row->user_id);
		}
	}

	/**
	 * Update plan subscription status when subscription record updated
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipUpdate($row)
	{
		if (!$this->subscriptionProcessed && JFactory::getApplication()->isAdmin())
		{
			$this->updateSubscriptionExpiredDate($row);

			$this->updatePlanSubscriptionStatus($row);
		}
	}

	/**
	 * Handle Login redirect
	 *
	 * @param $options
	 *
	 * @return void
	 * @throws Exception
	 */
	public function onUserAfterLogin($options)
	{
		if (!$this->canRun)
		{
			return;
		}

		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			return;
		}

		$session                = JFactory::getSession();
		$sessionReturnUrl       = $session->get('osm_return_url');
		$sessionRequiredPlanIds = $session->get('required_plan_ids');

		if (!empty($sessionReturnUrl) && !empty($sessionRequiredPlanIds))
		{
			$activePlans = OSMembershipHelper::getActiveMembershipPlans();

			if (count(array_intersect($activePlans, $sessionRequiredPlanIds)) > 0)
			{
				// Clear the old session data
				$session->clear('osm_return_url');
				$session->clear('required_plan_ids');

				$app->setUserState('users.login.form.return', $sessionReturnUrl);

				return;
			}
		}

		if (!$app->input->post->getInt('login_from_mp_subscription_form') && $loginRedirectUrl = OSMembershipHelper::getLoginRedirectUrl())
		{
			$app->setUserState('users.login.form.return', $loginRedirectUrl);
		}
	}

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * This method creates a subscription record for the saved user
	 *
	 * @param   array   $user    Holds the new user data.
	 * @param   boolean $isnew   True if a new user is stored.
	 * @param   boolean $success True if user was successfully stored in the database.
	 * @param   string  $msg     Message.
	 *
	 * @return  bool
	 *
	 * @since   2.6.0
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if (!$this->canRun)
		{
			return;
		}

		// If the user wasn't stored we don't resync
		if (!$success)
		{
			return false;
		}

		// If the user isn't new we don't sync
		if ($isnew)
		{
			return false;
		}

		// Ensure the user id is really an int
		$userId = (int) $user['id'];

		// If the user id appears invalid then bail out just in case
		if (empty($userId))
		{
			return false;
		}

		$config = OSMembershipHelper::getConfig();

		$option = JFactory::getApplication()->input->getCmd('option');

		if (!empty($config->synchronize_email) && (in_array($option, ['com_users', 'com_comprofiler']) || $config->synchronize_data === '0'))
		{
			$query = $this->db->getQuery(true);
			$query->update('#__osmembership_subscribers')
				->set('email = ' . $this->db->quote($user['email']))
				->where('user_id = ' . $userId);
			$this->db->setQuery($query);
			$this->db->execute();
		}
	}

	/**
	 * Remove all subscriptions for the user if configured
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array   $user    Holds the user data
	 * @param   boolean $success True if user was successfully stored in the database
	 * @param   string  $msg     Message
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$this->canRun)
		{
			return;
		}

		$config = OSMembershipHelper::getConfig();

		if ($config->delete_subscriptions_when_account_deleted)
		{
			/* @var $row OSMembershipTableSubscriber */
			$row = JTable::getInstance('osmembership', 'Subscriber');

			$query = $this->db->getQuery(true);
			$query->select('id')
				->from('#__osmembership_subscribers')
				->where('user_id = ' . (int) $user['id']);
			$this->db->setQuery($query);
			$cid = $this->db->loadColumn();

			if (count($cid))
			{
				$query->clear()
					->delete('#__osmembership_field_value')
					->where('subscriber_id IN (' . implode(',', $cid) . ')');
				$this->db->setQuery($query);
				$this->db->execute();

				JPluginHelper::importPlugin('osmembership');
				$app = JFactory::getApplication();

				foreach ($cid as $id)
				{
					$row->load($id);
					$app->triggerEvent('onMembershipExpire', array($row));
				}

				$query->clear()
					->delete('#__osmembership_subscribers')
					->where('user_id = ' . (int) $user['id']);
				$this->db->setQuery($query);
				$this->db->execute();
			}
		}

		return true;
	}

	/**
	 * Method to set profile data (is_profile, profile_id) for the subscription
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function setSubscriptionProfileData($row)
	{
		$row->is_profile = 1;

		if ($row->user_id > 0)
		{
			$query = $this->db->getQuery(true);
			$query->select('id')
				->from('#__osmembership_subscribers')
				->where('user_id = ' . $row->user_id)
				->where('(published >= 1 OR payment_method LIKE "os_offline%")')
				->where('is_profile = 1');
			$this->db->setQuery($query);
			$profileId = $this->db->loadResult();

			if ($profileId && $profileId != $row->id)
			{
				$row->is_profile = 0;
				$row->profile_id = $profileId;
			}
		}

		if ($row->is_profile == 1)
		{
			$row->profile_id = $row->id;
		}
	}

	/**
	 * Method to set plan main record data (plan_main_record, plan_subscription_from_date) for the subscription.
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function setPlanMainRecordData($row)
	{
		$row->plan_main_record = 1;

		if ($row->user_id > 0)
		{
			$query = $this->db->getQuery(true);
			$query->select('plan_subscription_from_date')
				->from('#__osmembership_subscribers')
				->where('plan_main_record = 1')
				->where('user_id = ' . $row->user_id)
				->where('plan_id = ' . $row->plan_id)
				->where('id != ' . $row->id);
			$this->db->setQuery($query);

			if ($planMainRecord = $this->db->loadObject())
			{
				$row->plan_main_record            = 0;
				$row->plan_subscription_from_date = $planMainRecord->plan_subscription_from_date;
			}
		}

		if ($row->plan_main_record == 1)
		{
			$row->plan_subscription_status    = $row->published;
			$row->plan_subscription_from_date = $row->from_date;
			$row->plan_subscription_to_date   = $row->to_date;
		}
	}

	/**
	 * Method to generate invoice number for the subscription record
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function generateInvoiceNumber($row)
	{
		if (OSMembershipHelper::needToCreateInvoice($row))
		{
			$row->invoice_number = OSMembershipHelper::getInvoiceNumber($row);
		}
	}

	/**
	 * Create user account for subscriber after subscription being active
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @throws Exception
	 */
	protected function createUserAccount($row)
	{
		$data['username']   = $row->username;
		$data['first_name'] = $row->first_name;
		$data['last_name']  = $row->last_name;
		$data['email']      = $row->email;

		//Password
		$privateKey        = md5(JFactory::getConfig()->get('secret'));
		$key               = new JCryptKey('simple', $privateKey, $privateKey);
		$crypt             = new JCrypt(new JCryptCipherSimple, $key);
		$data['password1'] = $crypt->decrypt($row->user_password);

		try
		{
			$row->user_id = (int) OSMembershipHelper::saveRegistration($data);
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
		}
	}

	/**
	 * Active user account automatically after subscription active
	 *
	 * @param $row
	 */
	protected function activateUserAccount($row)
	{
		if (JComponentHelper::getParams('com_users')->get('useractivation') != 2)
		{
			$user = JFactory::getUser($row->user_id);

			if ($user->get('block'))
			{
				$user->set('block', 0);
				$user->set('activation', '');
				$user->save(true);
			}
		}
	}

	/**
	 * Generate Membership ID for a subscription record
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function generateMembershipId($row)
	{
		if ($row->user_id)
		{
			$query = $this->db->getQuery(true);
			$query->select('MAX(membership_id)')
				->from('#__osmembership_subscribers')
				->where('user_id = ' . $row->user_id);
			$this->db->setQuery($query);
			$row->membership_id = (int) $this->db->loadResult();
		}

		if (!$row->membership_id)
		{
			$row->membership_id = OSMembershipHelper::getMembershipId($row);
		}
	}

	/**
	 * Calculate and store subscription expired date of the user for the plan he just processed subscription
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function updateSubscriptionExpiredDate($row)
	{
		$query = $this->db->getQuery(true);
		$query->select('MAX(to_date)')
			->from('#__osmembership_subscribers')
			->where('published = 1')
			->where('profile_id = ' . $row->profile_id)
			->where('plan_id = ' . $row->plan_id);
		$this->db->setQuery($query);
		$subscriptionExpiredDate = $this->db->loadResult();

		if ($subscriptionExpiredDate)
		{
			$query->clear()
				->update('#__osmembership_subscribers')
				->set('plan_subscription_to_date = ' . $this->db->quote($subscriptionExpiredDate))
				->where('profile_id = ' . $row->profile_id)
				->where('plan_id = ' . $row->plan_id);
			$this->db->setQuery($query);
			$this->db->execute();
		}
	}

	/**
	 * Update status of the plan for the user when subscription status change
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function updatePlanSubscriptionStatus($row)
	{
		$subscriptionStatus = OSMembershipHelperSubscription::getPlanSubscriptionStatusForUser($row->profile_id, $row->plan_id);
		$query              = $this->db->getQuery(true);
		$query->update('#__osmembership_subscribers')
			->set('plan_subscription_status = ' . $subscriptionStatus)
			->where('profile_id = ' . $row->profile_id)
			->where('plan_id = ' . $row->plan_id);
		$this->db->setQuery($query);
		$this->db->execute();

		// Store plan_subscription_status for this record to avoid it's changed by other plugin later
		$row->plan_subscription_status = $subscriptionStatus;
	}

	/**
	 * Clear subscription expired reminder status
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function updateSendingReminderStatus($row)
	{
		if ($row->user_id > 0)
		{
			$query = $this->db->getQuery(true);
			$now   = $this->db->quote(JFactory::getDate()->toSql());
			$query->update('#__osmembership_subscribers')
				->set('first_reminder_sent = 1')
				->set('second_reminder_sent = 1')
				->set('third_reminder_sent = 1')
				->set('first_reminder_sent_at = ' . $now)
				->set('second_reminder_sent_at = ' . $now)
				->set('third_reminder_sent_at = ' . $now)
				->where('user_id = ' . $row->user_id)
				->where('plan_id = ' . $row->plan_id)
				->where('id != ' . $row->id);

			$this->db->setQuery($query);
			$this->db->execute();
		}
	}

	/**
	 * Update subscription duration when membership is renewed.
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	protected function updateSubscriptionOnRenew($row)
	{
		$query = $this->db->getQuery(true);

		// Find the first subscription record of the user of this plan
		$query->select('*')
			->from('#__osmembership_subscribers')
			->where('user_id = ' . $row->user_id)
			->where('plan_id = ' . $row->plan_id)
			->where('published IN (1, 2)')
			->order('id');
		$this->db->setQuery($query, 0, 1);
		$rowSubscriber = $this->db->loadObject();

		if ($rowSubscriber)
		{
			// Get subscription_id from the new subscription and set it for new subscription
			if (!$row->subscription_id)
			{
				if ($rowSubscriber->subscription_id)
				{
					$row->subscription_id = $rowSubscriber->subscription_id;
				}
				else
				{
					$query->clear()
						->select('subscription_id')
						->from('#__osmembership_subscribers')
						->where('user_id = ' . $row->user_id)
						->where('plan_id = ' . $row->plan_id)
						->where('published IN (1, 2)')
						->where('LENGTH(subscription_id) > 0');
					$this->db->setQuery($query);
					$row->subscription_id = $this->db->loadResult();
				}
			}

			// Keep payment_made parameter from original subscription
			if ($rowSubscriber->payment_made > 0)
			{
				$row->payment_made = $rowSubscriber->payment_made;
			}

			if ($rowSubscriber->membership_id)
			{
				$row->membership_id = $rowSubscriber->membership_id;
			}

			// Delete all other subscription records to keep the management clean
			$query->clear()
				->delete('#__osmembership_subscribers')
				->where('user_id = ' . $row->user_id)
				->where('plan_id = ' . $row->plan_id)
				->where('id != ' . $row->id);
			$this->db->execute();

			// Set from_date is the date of the first_subscription record
			$row->from_date = $rowSubscriber->from_date;

			// Set profile data for the record
			$this->setSubscriptionProfileData($row);

			$row->plan_main_record            = 1;
			$row->plan_subscription_status    = 1;
			$row->plan_subscription_from_date = $row->from_date;
			$row->plan_subscription_to_date   = $row->to_date;
			$row->store();
		}
	}
}
