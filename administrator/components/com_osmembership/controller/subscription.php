<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * OSMembership Plugin controller
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipControllerSubscription extends OSMembershipController
{
	/**
	 * Cancel recurring subscription
	 *
	 * @throws Exception
	 */
	public function cancel_subscription()
	{
		$id = $this->input->post->getInt('id', 0);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_subscribers')
			->where('id = ' . $id);
		$db->setQuery($query);
		$rowSubscription = $db->loadObject();

		if ($rowSubscription && OSMembershipHelper::canCancelSubscription($rowSubscription))
		{
			JLoader::register('OSMembershipModelRegister', JPATH_ROOT . '/components/com_osmembership/model/register.php');

			/**@var OSMembershipModelRegister $model * */
			$model = $this->getModel('Register');
			$ret   = $model->cancelSubscription($rowSubscription);

			if ($ret)
			{
				$this->setRedirect('index.php?option=com_osmembership&view=subscription&id=' . $rowSubscription->id, JText::_('OSM_SUBSCRIPTION_CANCELLED'));
			}
			else
			{
				// Redirect back to profile page, the payment plugin should enqueue the reason of failed cancellation so that it could be displayed to end user
				$this->setRedirect('index.php?option=com_osmembership&view=subscription&id=' . $rowSubscription->id);
			}
		}
		else
		{
			throw new InvalidArgumentException(JText::_('OSM_INVALID_RECURRING_SUBSCRIPTION'), 404);
		}
	}

	/**
	 * Resend confirmation email to registrants in case they didn't receive it
	 */
	public function resend_email()
	{
		$cid = $this->input->get('cid', array(), 'array');
		$cid = ArrayHelper::toInteger($cid);

		/* @var OSMembershipModelSubscription $model */
		$model = $this->getModel();

		foreach ($cid as $id)
		{
			$model->resendEmail($id);
		}

		$this->setRedirect('index.php?option=com_osmembership&view=subscriptions', JText::_('OSM_EMAIL_SUCCESSFULLY_RESENT'));
	}

	/**
	 * Send batch mail to subscriptions
	 */
	public function batch_mail()
	{
		if ($this->app->isSite())
		{
			throw new Exception('You are not allowed to perform this action', 403);
		}

		$this->checkAccessPermission('subscriptions');

		/* @var OSMembershipModelSubscription $model */
		$model = $this->getModel();

		try
		{
			$model->batchMail($this->input);
			$this->setMessage(JText::_('OSM_BATCH_MAIL_SUCCESS'));
		}
		catch (Exception $e)
		{
			$this->setMessage($e->getMessage(), 'error');
		}

		$this->setRedirect('index.php?option=com_osmembership&view=subscriptions');
	}

	/**
	 * Renew subscription for given user
	 */
	public function renew()
	{
		if ($this->app->isSite())
		{
			$this->csrfProtection();
		}

		$this->checkAccessPermission('subscriptions');

		$cid = $this->input->get('cid', array(), 'array');
		$cid = ArrayHelper::toInteger($cid);

		/* @var OSMembershipModelSubscription $model */
		$model = $this->getModel('subscription');

		foreach ($cid as $id)
		{
			$model->renew($id);
		}

		$this->setRedirect($this->getViewListUrl(), JText::_('The selected subscription(s) was successfully renewed'));
	}

	/**
	 * Import Subscribers from CSV
	 */
	public function import()
	{
		if ($this->app->isSite())
		{
			throw new Exception('You are not allowed to perform this action', 403);
		}

		$this->checkAccessPermission('subscriptions');

		$inputFile = $this->input->files->get('input_file');
		$fileName  = $inputFile ['name'];
		$fileExt   = strtolower(JFile::getExt($fileName));

		if (!in_array($fileExt, array('csv', 'xls', 'xlsx')))
		{
			$this->setRedirect('index.php?option=com_osmembership&view=import', JText::_('Invalid File Type. Only CSV, XLS and XLS file types are supported'));

			return;
		}

		/* @var OSMembershipModelImport $model */
		$model = $this->getModel('import');

		try
		{
			$numberSubscribers = $model->store($inputFile['tmp_name']);
			$this->setRedirect('index.php?option=com_osmembership&view=subscriptions', JText::sprintf('OSM_NUMBER_SUBSCRIBERS_IMPORTED', $numberSubscribers));
		}
		catch (Exception $e)
		{
			$this->setRedirect('index.php?option=com_osmembership&view=import');
			$this->setMessage($e->getMessage(), 'error');
		}
	}

	/**
	 * Import Subscribers from Joomla cores
	 */
	public function import_from_joomla()
	{
		if ($this->app->isSite())
		{
			throw new Exception('You are not allowed to perform this action', 403);
		}

		$planId = $this->input->getInt('to_plan_id', 0);
		$start  = $this->input->getInt('start', 0);
		$limit  = $this->input->getInt('limit', 0);
		if (empty($planId))
		{
			throw new Exception('Plan not found', 404);
		}

		/* @var OSMembershipModelImport $model */
		$model = $this->getModel('import');

		try
		{
			$numberSubscribers = $model->importFromJoomla($planId, $start, $limit);
			$this->setRedirect('index.php?option=com_osmembership&view=subscriptions', JText::sprintf('OSM_NUMBER_SUBSCRIBERS_IMPORTED', $numberSubscribers));
		}
		catch (Exception $e)
		{
			$this->setRedirect('index.php?option=com_osmembership&view=import');
			$this->setMessage($e->getMessage(), 'error');
		}
	}

	/**
	 * Export registrants into a CSV file
	 */
	public function export()
	{
		if ($this->app->isSite())
		{
			$this->csrfProtection();
		}

		$this->checkAccessPermission('subscriptions');

		set_time_limit(0);

		$config = OSMembershipHelper::getConfig();

		/* @var OSMembershipModelSubscriptions $model */
		$model = $this->getModel('subscriptions');
		$model->set('limitstart', 0)
			->set('limit', 0);

		if ($config->include_group_members_in_export)
		{
			$model->setIncludeGroupMembers(true);
		}

		$rows = $model->getData();

		if (count($rows) == 0)
		{
			$this->setMessage(JText::_('There are no subscription records to export'));
			$this->setRedirect('index.php?option=com_osmembership&view=subscriptions');

			return;
		}

		$planId = (int) $model->get('plan_id');

		$db       = JFactory::getDbo();
		$query    = $db->getQuery(true);
		$nullDate = $db->getNullDate();

		$query->select('id, name, is_core')
			->from('#__osmembership_fields')
			->where('published = 1')
			->where('hide_on_export = 0')
			->order('ordering');

		if ($planId > 0)
		{
			$query->where('(plan_id=0 OR id IN (SELECT field_id FROM #__osmembership_field_plan WHERE plan_id=' . $planId . '))');
		}

		$db->setQuery($query);
		$rowFields = $db->loadObjectList();

		$fieldIds = array();
		foreach ($rowFields as $rowField)
		{
			if ($rowField->is_core)
			{
				continue;
			}

			$fieldIds[] = $rowField->id;
		}

		$fieldValues = $model->getFieldsData($fieldIds);

		$fields = array(
			'id',
			'plan',
			'username',
		);

		$i = 0;

		foreach ($rowFields as $rowField)
		{
			$fields[] = $rowField->name;

			if ($rowField->is_core)
			{
				unset($rowFields[$i]);
			}

			$i++;
		}

		$fields[] = 'created_date';
		$fields[] = 'payment_date';
		$fields[] = 'from_date';
		$fields[] = 'to_date';
		$fields[] = 'published';
		$fields[] = 'amount';
		$fields[] = 'tax_amount';
		$fields[] = 'discount_amount';
		$fields[] = 'gross_amount';
		$fields[] = 'payment_method';
		$fields[] = 'transaction_id';
		$fields[] = 'membership_id';

		if ($config->activate_invoice_feature)
		{
			$fields[] = 'invoice_number';
		}

		if ($config->enable_coupon)
		{
			$fields[] = 'coupon_code';
		}

		$dateFields = array('created_date', 'payment_date', 'from_date', 'to_date');

		foreach ($rows as $row)
		{
			$row->plan = $row->plan_title;

			foreach ($dateFields as $dateField)
			{
				if ($row->{$dateField} != $nullDate && $row->{$dateField})
				{
					$row->{$dateField} = JHtml::_('date', $row->{$dateField}, 'Y-m-d');
				}
				else
				{
					$row->{$dateField} = '';
				}
			}

			foreach ($rowFields as $rowField)
			{
				if (!$rowField->is_core)
				{
					$fieldValue             = isset($fieldValues[$row->id][$rowField->id]) ? $fieldValues[$row->id][$rowField->id] : '';
					$row->{$rowField->name} = $fieldValue;
				}
			}

			if ($config->activate_invoice_feature)
			{
				$row->invoice_number = OSMembershipHelper::formatInvoiceNumber($row, $config);
			}
		}

		if (is_callable('OSMembershipHelperOverrideData::excelExport'))
		{
			OSMembershipHelperOverrideData::excelExport($fields, $rows, 'subscriptions_list');
		}
		else
		{
			OSMembershipHelperData::excelExport($fields, $rows, 'subscriptions_list');
		}
	}

	/**
	 * Method to export expired subscribers in the whole system
	 *
	 * @return void
	 */
	public function export_expired_subscribers()
	{
		$this->checkAccessPermission('subscriptions');

		set_time_limit(0);

		/* @var OSMembershipModelSubscriptions $model */
		$model = $this->getModel('subscriptions');

		$rows = $model->getExpiredSubscribers();

		if (count($rows) == 0)
		{
			$this->setMessage(JText::_('There are no expired subscribers to export'));
			$this->setRedirect('index.php?option=com_osmembership&view=subscriptions');

			return;
		}

		$db       = JFactory::getDbo();
		$query    = $db->getQuery(true);
		$nullDate = $db->getNullDate();

		$query->select('id, name, is_core')
			->from('#__osmembership_fields')
			->where('published = 1')
			->where('plan_id = 0')
			->where('hide_on_export = 0')
			->order('ordering');
		$db->setQuery($query);
		$rowFields = $db->loadObjectList();

		$fieldIds = array();

		foreach ($rowFields as $rowField)
		{
			if ($rowField->is_core)
			{
				continue;
			}

			$fieldIds[] = $rowField->id;
		}

		$userIds = array();

		foreach ($rows as $row)
		{
			$userIds[] = $row->user_id;
		}

		// Get latest expired plan
		$query->clear()
			->select('a.title, b.user_id')
			->from('#__osmembership_plans AS a')
			->innerJoin('#__osmembership_subscribers AS b On a.id = b.plan_id')
			->where('b.user_id IN (' . implode(',', $userIds) . ')')
			->where('b.published = 2')
			->order('b.to_date');
		$db->setQuery($query);
		$userPlans = $db->loadObjectList('user_id');

		$fieldValues = $model->getFieldsData($fieldIds);

		$fields = array(
			'username',
			'plan',
		);

		$i = 0;

		foreach ($rowFields as $rowField)
		{
			$fields[] = $rowField->name;

			if ($rowField->is_core)
			{
				unset($rowFields[$i]);
			}

			$i++;
		}

		$fields[] = 'created_date';
		$fields[] = 'membership_id';

		$dateFields = array('created_date');

		foreach ($rows as $row)
		{
			$row->plan = $userPlans[$row->user_id]->title;

			foreach ($dateFields as $dateField)
			{
				if ($row->{$dateField} != $nullDate && $row->{$dateField})
				{
					$row->{$dateField} = JHtml::_('date', $row->{$dateField}, 'Y-m-d');
				}
				else
				{
					$row->{$dateField} = '';
				}
			}

			foreach ($rowFields as $rowField)
			{
				if (!$rowField->is_core)
				{
					$fieldValue             = isset($fieldValues[$row->id][$rowField->id]) ? $fieldValues[$row->id][$rowField->id] : '';
					$row->{$rowField->name} = $fieldValue;
				}
			}
		}

		if (is_callable('OSMembershipHelperOverrideData::excelExport'))
		{
			OSMembershipHelperOverrideData::excelExport($fields, $rows, 'expired_subscribers_list');
		}
		else
		{
			OSMembershipHelperData::excelExport($fields, $rows, 'expired_subscribers_list');
		}
	}

	/**
	 * Generate CSV Template use to import subscribers into the system
	 */
	public function csv_import_template()
	{
		$this->checkAccessPermission('subscriptions');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('name')
			->from('#__osmembership_fields')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$rowFields = $db->loadObjectList();

		$fields = array(
			'plan',
			'username',
			'password',
		);

		foreach ($rowFields as $rowField)
		{
			$fields[] = $rowField->name;
		}

		$fields[] = 'created_date';
		$fields[] = 'payment_date';
		$fields[] = 'from_date';
		$fields[] = 'to_date';
		$fields[] = 'published';
		$fields[] = 'amount';
		$fields[] = 'tax_amount';
		$fields[] = 'discount_amount';
		$fields[] = 'gross_amount';
		$fields[] = 'payment_method';
		$fields[] = 'transaction_id';
		$fields[] = 'membership_id';

		$row           = new stdClass;
		$row->plan     = '6 Months Membership';
		$row->username = 'tuanpn';
		$row->password = 'tuanpn';

		foreach ($rowFields as $rowField)
		{
			if ($rowField->name == 'first_name')
			{
				$row->{$rowField->name} = 'Tuan';
			}
			elseif ($rowField->name == 'last_name')
			{
				$row->{$rowField->name} = 'Pham Ngoc';
			}
			elseif ($rowField->name == 'email')
			{
				$row->{$rowField->name} = 'tuanpn@joomdonation.com';
			}
			else
			{
				$row->{$rowField->name} = 'sample_data_for_' . $rowField->name;
			}
		}

		$todayDate = JFactory::getDate();

		$row->payment_date = $row->from_date = $row->created_date = $todayDate->format('Y-m-d');

		$todayDate->modify('+6 months');

		$row->to_date         = $todayDate->format('Y-m-d');
		$row->published       = 1;
		$row->amount          = 100;
		$row->tax_amount      = 10;
		$row->discount_amount = 0;
		$row->gross_amount    = 110;
		$row->payment_method  = 'os_paypal';
		$row->transaction_id  = 'TR4756RUI78465';
		$row->membership_id   = 1001;

		OSMembershipHelperData::excelExport($fields, array($row), 'subscriptions_import_template');
	}

	/**
	 * Disable reminders for selected subscription records
	 */
	public function disable_reminders()
	{
		$cid = $this->input->post->get('cid', [], 'array');

		if (count($cid))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->update('#__osmembership_subscribers')
				->set('first_reminder_sent = 1')
				->set('second_reminder_sent = 1')
				->set('third_reminder_sent = 1')
				->where('id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query)
				->execute();
		}

		$this->setRedirect('index.php?option=com_osmembership&view=subscriptions', JText::_('OSM_REMINDER_EMAILS_DISABLED_FOR_SELECTED_SUBSCRIPTIONS'));
	}

	/**
	 * Cancel recurring subscription
	 *
	 * @throws Exception
	 */
	public function refund()
	{
		$id = $this->input->post->getInt('id', 0);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_subscribers')
			->where('id = ' . $id);
		$db->setQuery($query);
		$rowSubscription = $db->loadObject();

		if (OSMembershipHelper::canRefundSubscription($rowSubscription))
		{
			/**@var OSMembershipModelSubscription $model * */
			$model = $this->getModel('Subscription');

			try
			{
				$model->refund($rowSubscription);

				$this->setRedirect('index.php?option=com_osmembership&view=subscription&id=' . $rowSubscription->id, JText::_('OSM_SUBSCRIPTION_REFUNDED'));
			}
			catch (Exception $e)
			{
				$this->app->enqueueMessage($e->getMessage(), 'error');
				$this->setRedirect('index.php?option=com_osmembership&view=subscription&id=' . $rowSubscription->id, $e->getMessage(), 'error');
			}
		}
		else
		{
			throw new InvalidArgumentException(JText::_('OSM_CANNOT_PROCESS__REFUND'));
		}
	}
}
