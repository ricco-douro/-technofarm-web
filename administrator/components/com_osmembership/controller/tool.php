<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

class OSMembershipControllerTool extends MPFController
{
	/**
	 * Method to allow sharing language files for Events Booking
	 */
	public function share_translation()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('lang_code')
			->from('#__languages')
			->where('published = 1')
			->where('lang_code != "en-GB"')
			->order('ordering');
		$db->setQuery($query);
		$languages = $db->loadObjectList();

		if (count($languages))
		{
			$mailer   = JFactory::getMailer();
			$jConfig  = JFactory::getConfig();
			$mailFrom = $jConfig->get('mailfrom');
			$fromName = $jConfig->get('fromname');
			$mailer->setSender(array($mailFrom, $fromName));
			$mailer->addRecipient('tuanpn@joomdonation.com');
			$mailer->setSubject('Language Packages for Membership Pro shared by ' . JUri::root());
			$mailer->setBody('Dear Tuan \n. I am happy to share my language packages for Membership Pro.\n Enjoy!');
			foreach ($languages as $language)
			{
				$tag = $language->lang_code;
				if (file_exists(JPATH_ROOT . '/language/' . $tag . '/' . $tag . '.com_osmembership.ini'))
				{
					$mailer->addAttachment(JPATH_ROOT . '/language/' . $tag . '/' . $tag . '.com_osmembership.ini', $tag . '.com_osmembership.ini');
				}

				if (file_exists(JPATH_ADMINISTRATOR . '/language/' . $tag . '/' . $tag . '.com_osmembership.ini'))
				{
					echo JPATH_ADMINISTRATOR . '/language/' . $tag . '/' . $tag . '.com_osmembership.ini';
					$mailer->addAttachment(JPATH_ADMINISTRATOR . '/language/' . $tag . '/' . $tag . '.com_osmembership.ini', 'admin.' . $tag . '.com_osmembership.ini');
				}
			}

			require_once JPATH_COMPONENT . '/libraries/vendor/dbexporter/dumper.php';

			$tables = array($db->replacePrefix('#__eb_fields'), $db->replacePrefix('#__eb_messages'));

			try
			{

				$sqlFile = $tag . '.com_osmembership.sql';
				$options = array(
					'host'           => $jConfig->get('host'),
					'username'       => $jConfig->get('user'),
					'password'       => $jConfig->get('password'),
					'db_name'        => $jConfig->get('db'),
					'include_tables' => $tables,
				);
				$dumper  = Shuttle_Dumper::create($options);
				$dumper->dump(JPATH_ROOT . '/tmp/' . $sqlFile);

				$mailer->addAttachment(JPATH_ROOT . '/tmp/' . $sqlFile, $sqlFile);

			}
			catch (Exception $e)
			{
				//Do nothing
			}

			$mailer->Send();

			$msg = 'Thanks so much for sharing your language files to Membership Pro Community';
		}
		else
		{
			$msg = 'Thanks so willing to share your language files to Membership Pro Community. However, you don"t have any none English language file to share';
		}

		$this->setRedirect('index.php?option=com_osmembership&view=dashboard', $msg);
	}

	/**
	 * Reset SEF urls
	 */
	public function reset_urls()
	{
		$db = JFactory::getDbo();
		$db->truncateTable('#__osmembership_sefurls');
		$this->setRedirect('index.php?option=com_osmembership&view=dashboard', JText::_('SEF urls has successfully been reset'));
	}

	/**
	 * Trigger expired event to expired subscriptions
	 *
	 * @return void
	 */
	public function trigger_expired_event()
	{
		JPluginHelper::importPlugin('osmembership');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__osmembership_subscribers')
			->where('published = 2')
			->order('id');
		$db->setQuery($query);
		$ids = $db->loadColumn();

		foreach ($ids as $id)
		{
			$row = JTable::getInstance('Subscriber', 'OSMembershipTable');
			$row->load($id);
			$this->app->triggerEvent('onMembershipExpire', array($row));
		}
	}

	/**
	 * Trigger active events to active subscriptions
	 *
	 * @return void
	 */
	public function trigger_active_event()
	{
		JPluginHelper::importPlugin('osmembership');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__osmembership_subscribers')
			->where('published = 1')
			->order('id');
		$db->setQuery($query);
		$ids = $db->loadColumn();

		foreach ($ids as $id)
		{
			$row = JTable::getInstance('Subscriber', 'OSMembershipTable');
			$row->load($id);
			$this->app->triggerEvent('onMembershipActive', array($row));
		}
	}

	/**
	 * Trigger active events to active subscriptions
	 *
	 * @return void
	 */
	public function trigger_active_event_for_joomlagroups()
	{
		JPluginHelper::importPlugin('osmembership', 'joomlagroups');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__osmembership_subscribers')
			->where('published = 1')
			->order('id');
		$db->setQuery($query);
		$ids = $db->loadColumn();

		foreach ($ids as $id)
		{
			$row = JTable::getInstance('Subscriber', 'OSMembershipTable');
			$row->load($id);
			$this->app->triggerEvent('onMembershipActive', array($row));
		}
	}

	/**
	 * Change language code
	 *
	 * @return void
	 */
	public function change_language_code()
	{
		$db = JFactory::getDbo();

		#Process for #__osmembership_categories table
		$varcharFields = array(
			'alias',
			'title',
		);

		$oldLanguageCode = 'ar-AA';
		$newLanguageCode = 'ar';

		foreach ($varcharFields as $varcharField)
		{
			$oldFieldName = $varcharField . '_' . $oldLanguageCode;
			$fieldName    = $varcharField . '_' . $newLanguageCode;
			$sql          = "ALTER TABLE  `#__osmembership_categories` CHANGE  `$oldFieldName` `$fieldName` VARCHAR( 255 );";
			$db->setQuery($sql);
			$db->execute();
		}

		$textFields = array(
			'description',
		);

		foreach ($textFields as $textField)
		{
			$oldFieldName = $textField . '_' . $oldLanguageCode;
			$fieldName    = $textField . '_' . $newLanguageCode;

			$sql = "ALTER TABLE  `#__osmembership_categories` CHANGE `$oldFieldName` `$fieldName` TEXT NULL;";
			$db->setQuery($sql);
			$db->execute();
		}

		#Process for #__osmembership_plans table
		$varcharFields = array(
			'alias',
			'title',
			'user_email_subject',
			'subscription_approved_email_subject',
			'user_renew_email_subject',
		);

		foreach ($varcharFields as $varcharField)
		{
			$oldFieldName = $varcharField . '_' . $oldLanguageCode;
			$fieldName    = $varcharField . '_' . $newLanguageCode;
			$sql          = "ALTER TABLE  `#__osmembership_plans` CHANGE  `$oldFieldName` `$fieldName` VARCHAR( 255 );";
			$db->setQuery($sql);
			$db->execute();
		}


		$textFields = array(
			'short_description',
			'description',
			'subscription_form_message',
			'user_email_body',
			'user_email_body_offline',
			'subscription_approved_email_body',
			'thanks_message',
			'thanks_message_offline',
			'user_renew_email_body'
		);

		foreach ($textFields as $textField)
		{
			$oldFieldName = $textField . '_' . $oldLanguageCode;
			$fieldName    = $textField . '_' . $newLanguageCode;

			$sql = "ALTER TABLE  `#__osmembership_plans` CHANGE `$oldFieldName` `$fieldName` TEXT NULL;";
			$db->setQuery($sql);
			$db->execute();
		}

		#Process for #__osmembership_fields table
		$varcharFields = array(
			'title',
		);

		foreach ($varcharFields as $varcharField)
		{
			$oldFieldName = $varcharField . '_' . $oldLanguageCode;
			$fieldName    = $varcharField . '_' . $newLanguageCode;
			$sql          = "ALTER TABLE  `#__osmembership_fields` CHANGE  `$oldFieldName` `$fieldName` VARCHAR( 255 );";
			$db->setQuery($sql);
			$db->execute();
		}


		$textFields = array(
			'description',
			'values',
			'default_values',
			'fee_values',
			'depend_on_options',
		);

		foreach ($textFields as $textField)
		{
			$oldFieldName = $textField . '_' . $oldLanguageCode;
			$fieldName    = $textField . '_' . $newLanguageCode;

			$sql = "ALTER TABLE  `#__osmembership_fields` CHANGE `$oldFieldName` `$fieldName` TEXT NULL;";
			$db->setQuery($sql);
			$db->execute();
		}

		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_messages');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			if (strpos($row->message_key, $oldLanguageCode) !== false)
			{
				$newKey = str_replace($oldLanguageCode, $newLanguageCode, $row->message_key);
				$query->clear()
					->update('#__osmembership_messages')
					->set('message_key = ' . $db->quote($newKey))
					->where('id = ' . $row->id);
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	/**
	 * Build EU tax rules
	 */
	public function build_eu_tax_rules()
	{
		$db = JFactory::getDbo();
		$db->truncateTable('#__osmembership_taxes');
		$defaultCountry     = OSmembershipHelper::getConfigValue('default_country');
		$defaultCountryCode = OSMembershipHelper::getCountryCode($defaultCountry);
		// Without VAT number, use local tax rate
		foreach (OSMembershipHelperEuvat::$europeanUnionVATInformation as $countryCode => $vatInfo)
		{
			$countryName    = $db->quote($vatInfo[0]);
			$countryTaxRate = OSMembershipHelperEuvat::getEUCountryTaxRate($countryCode);
			$sql            = "INSERT INTO #__osmembership_taxes(plan_id, country, rate, vies, published) VALUES(0, $countryName, $countryTaxRate, 0, 1)";
			$db->setQuery($sql);
			$db->execute();

			if ($countryCode == $defaultCountryCode)
			{
				$localTaxRate = OSMembershipHelperEuvat::getEUCountryTaxRate($defaultCountryCode);
				$sql          = "INSERT INTO #__osmembership_taxes(plan_id, country, rate, vies, published) VALUES(0, $countryName, $localTaxRate, 1, 1)";
				$db->setQuery($sql);
				$db->execute();
			}
		}

		$this->setRedirect('index.php?option=com_osmembership&view=taxes', JText::_('EU Tax Rules were successfully created'));
	}

	/**
	 * Fix "Row size too large" issue
	 */
	public function fix_row_size()
	{
		$db = JFactory::getDbo();
		$db->setQuery('ALTER TABLE `#__osmembership_plans` ENGINE = MYISAM ROW_FORMAT = DYNAMIC');
		$db->execute();
	}

	/**
	 * Method to make a given field search and sortable easier
	 */
	public function make_field_search_sort_able()
	{
		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);
		$fieldId = $this->input->getInt('field_id');

		$query->select('*')
			->from('#__osmembership_fields')
			->where('id = ' . (int) $fieldId);
		$db->setQuery($query);
		$field = $db->loadObject();

		if (!$field)
		{
			throw new Exception('The field does not exist');
		}

		// Add new field to #__eb_registrants
		$fields = array_keys($db->getTableColumns('#__osmembership_subscribers'));

		if (!in_array($field->name, $fields))
		{
			$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `$field->name` VARCHAR( 255 ) NULL;";
			$db->setQuery($sql);
			$db->execute();

			$query->clear()
				->select('*')
				->from('#__osmembership_field_value')
				->where('field_id = ' . $fieldId);
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			$fieldName = $db->quoteName($field->name);

			foreach ($rows as $row)
			{
				$query->clear()
					->update('#__osmembership_subscribers')
					->set($fieldName . ' = ' . $db->quote($row->field_value))
					->where('id = ' . $row->subscriber_id);
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Mark the field as searchable
		$query->clear()
			->update('#__osmembership_fields')
			->set('is_searchable = 1')
			->where('id = ' . (int) $fieldId);
		$db->setQuery($query);
		$db->execute();

		echo 'Done !';
	}

	/**
	 * The second option to fix row size
	 */
	public function fix_row_size2()
	{
		$db        = JFactory::getDbo();
		$languages = OSMembershipHelper::getLanguages();

		if (count($languages))
		{
			$categoryTableFields = array_keys($db->getTableColumns('#__osmembership_categories'));
			$planTableFields     = array_keys($db->getTableColumns('#__osmembership_plans'));
			$fieldTableFields    = array_keys($db->getTableColumns('#__osmembership_fields'));

			foreach ($languages as $language)
			{
				$prefix = $language->sef;

				$fields = array(
					'alias',
					'title',
					'description'
				);

				foreach ($fields as $field)
				{
					$fieldName = $field . '_' . $prefix;

					if (!in_array($fieldName, $categoryTableFields))
					{
						$sql = "ALTER TABLE  `#__osmembership_categories` ADD  `$fieldName` TEXT NULL;";
					}
					else
					{
						$sql = "ALTER TABLE  `#__osmembership_categories` MODIFY  `$fieldName` TEXT NULL;";
					}

					$db->setQuery($sql);

					try
					{
						$db->execute();
					}
					catch (Exception $e)
					{
						$this->app->enqueueMessage(sprintf('Field %s already exist in table %s', $fieldName, '#__osmembership_categories'));
					}
				}

				$fields = array(
					'alias',
					'title',
					'page_title',
					'page_heading',
					'meta_keywords',
					'meta_description',
					'user_email_subject',
					'subscription_approved_email_subject',
					'user_renew_email_subject',
					'short_description',
					'description',
					'subscription_form_message',
					'user_email_body',
					'user_email_body_offline',
					'subscription_approved_email_body',
					'thanks_message',
					'thanks_message_offline',
					'user_renew_email_body',
				);

				foreach ($fields as $field)
				{
					$fieldName = $field . '_' . $prefix;

					if (!in_array($fieldName, $planTableFields))
					{
						$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `$fieldName` TEXT NULL;";
					}
					else
					{
						$sql = "ALTER TABLE  `#__osmembership_plans` MODIFY  `$fieldName` TEXT NULL;";
					}

					$db->setQuery($sql);

					try
					{
						$db->execute();
					}
					catch (Exception $e)
					{
						$this->app->enqueueMessage(sprintf('Field %s already exist in table %s', $fieldName, '#__osmembership_plans'));
					}
				}


				$fields = array(
					'title',
					'place_holder',
					'description',
					'values',
					'default_values',
					'fee_values',
					'depend_on_options',
				);

				foreach ($fields as $field)
				{
					$fieldName = $field . '_' . $prefix;

					if (!in_array($fieldName, $fieldTableFields))
					{
						$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `$fieldName` TEXT NULL;";
					}
					else
					{
						$sql = "ALTER TABLE  `#__osmembership_fields` MODIFY  `$fieldName` TEXT NULL;";
					}

					$db->setQuery($sql);

					try
					{
						$db->execute();
					}
					catch (Exception $e)
					{
						$this->app->enqueueMessage(sprintf('Field %s already exist in table %s', $fieldName, '#__eb_fields'));
					}
				}
			}
		}
	}

	/**
	 * Tool to update subscription_id of subscribers base on exported data from CSV file
	 */
	public function update_stripe_subscription_ids()
	{
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true);
		$file        = JPATH_ADMINISTRATOR . '/components/com_osmembership/subscriptions.csv';
		$subscribers = OSMembershipHelperData::getDataFromFile($file);

		$notFound = [];
		$updated  = 0;

		foreach ($subscribers as $subscriber)
		{
			$subscriptionId = $subscriber['id'];

			// First, check to see whether this subscription exists in the system
			$query->clear()
				->select('COUNT(*)')
				->from('#__osmembership_subscribers')
				->where('subscription_id = ' . $db->quote($subscriptionId));
			$db->setQuery($query);

			if ($db->loadResult())
			{
				// Subscription exists, continue
				continue;
			}

			$email = $subscriber['Customer Email'];
			$plan  = str_replace('membership_plan_', '', $subscriber['Plan']);
			$parts = explode('_', $plan);

			$found = false;

			if (count($parts) > 1)
			{
				$planId = (int) $parts[0];
				$query->clear()
					->select('id')
					->from('#__osmembership_subscribers')
					->where('plan_id = ' . $planId)
					->where('email=' . $db->quote($email))
					->where('LENGTH(subscription_id) = 0');
				$db->setQuery($query);
				$id = (int) $db->loadResult();

				if ($id)
				{
					$query->clear()
						->update('#__osmembership_subscribers')
						->set('subscription_id = ' . $db->quote($subscriptionId))
						->where('id=' . $id);
					$db->setQuery($query);
					$db->execute();
					$updated++;
					$found = true;
				}
			}

			if (!$found)
			{
				$notFound[] = $subscriptionId;
			}
		}

		echo sprintf('%s subscriptions updated', $updated) . '<br />';

		echo 'The following subscriptions could not be found from your system:' . implode("<br />", $notFound);
	}


	public function fix_profile_id()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id, user_id')
			->from('#__osmembership_subscribers')
			->where('profile_id = 0')
			->where('(published >= 1 OR payment_method LIKE "os_offline%")')
			->order('id');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			$isProfile = 1;
			$profileId = $row->id;

			if ($row->user_id > 0)
			{
				$query->clear()
					->select('id')
					->from('#__osmembership_subscribers')
					->where('user_id = ' . $row->user_id)
					->where('(published >= 1 OR payment_method LIKE "os_offline%")')
					->where('is_profile = 1');
				$db->setQuery($query);
				$existingProfileId = $db->loadResult();

				if ($existingProfileId && $existingProfileId != $row->id)
				{
					$isProfile = 0;
					$profileId = $existingProfileId;
				}
			}

			$query->clear()
				->update('#__osmembership_subscribers')
				->set('is_profile = ' . $isProfile)
				->set('profile_id = ' . $profileId)
				->where('id = ' . $row->id);
			$db->setQuery($query)
				->execute();
		}
	}

	/**
	 * Change database schema to support setting up price in more decimal numbers
	 */
	public function support_more_decimal_numbers()
	{
		$db = JFactory::getDbo();

		$sql = "ALTER TABLE  `#__osmembership_plans` CHANGE  `price`	`price` DECIMAL( 15, 8 ) NULL DEFAULT  '0';";
		$db->setQuery($sql)
			->execute();
		$sql = "ALTER TABLE  `#__osmembership_plans` CHANGE  `trial_amount`	`trial_amount` DECIMAL( 15, 8 ) NULL DEFAULT  '0';";
		$db->setQuery($sql)
			->execute();
		$sql = "ALTER TABLE  `#__osmembership_plans` CHANGE  `setup_fee`	`setup_fee` DECIMAL( 15, 8 ) NULL DEFAULT  '0';";
		$db->setQuery($sql)
			->execute();
		$sql = "ALTER TABLE  `#__osmembership_renewrates` CHANGE  `price`	`price` DECIMAL( 15, 8 ) NULL DEFAULT  '0';";
		$db->setQuery($sql)
			->execute();
		$sql = "ALTER TABLE  `#__osmembership_upgraderules` CHANGE  `price`	`price` DECIMAL( 15, 8 ) NULL DEFAULT  '0';";
		$db->setQuery($sql)
			->execute();
	}

	/**
	 * Tool to convert state name to state_2_code
	 */
	public static function convert_to_state_2_code()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id, country, state')
			->from('#__osmembership_subscribers')
			->where('CHAR_LENGTH(state) > 2');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$count = 0;

		foreach ($rows as $row)
		{
			$state = OSmembershipHelper::getStateCode($row->country, $row->state);

			if ($state == $row->state)
			{
				continue;
			}

			$query->clear()
				->update('#__osmembership_subscribers')
				->set('state = ' . $db->quote($state))
				->where('id = ' . $row->id);
			$db->setQuery($query)
				->execute();

			$count++;
		}


		echo sprintf('Succssfully converted %s state to state 2 code', $count);
	}
}