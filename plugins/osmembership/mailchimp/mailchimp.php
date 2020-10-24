<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use DrewM\MailChimp\MailChimp;
use Joomla\Registry\Registry;

if (!class_exists('DrewM\\MailChimp\\MailChimp'))
{
	require_once dirname(__FILE__) . '/api/MailChimp.php';
}

class plgOSMembershipMailchimp extends JPlugin
{
	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Render setting form
	 *
	 * @param OSMembershipTablePlan $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_MAILCHIMP_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database, in this case, use params field of plans table
	 *
	 * @param OSMembershipTablePlan $row
	 * @param Boolean               $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		// $row of table EB_plans
		$params = new Registry($row->params);
		$params->set('mailchimp_list_ids', implode(',', $data['mailchimp_list_ids']));
		$params->set('remove_mailchimp_list_ids', implode(',', $data['remove_mailchimp_list_ids']));
		$row->params = $params->toString();

		$row->store();
	}

	/**
	 * Run when a membership activated
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipActive($row)
	{
		$config = OSMembershipHelper::getConfig();

		// In case subscriber doesn't want to subscribe to newsleter, stop
		if ($config->show_subscribe_newsletter_checkbox && empty($row->subscribe_newsletter))
		{
			return;
		}

		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params  = new Registry($plan->params);
		$listIds = explode(',', $params->get('mailchimp_list_ids', ''));
		$listIds = array_filter($listIds);

		if (empty($listIds))
		{
			return;
		}

		try
		{
			$mailchimp = new MailChimp($this->params->get('api_key'));
		}
		catch (Exception $e)
		{
			return;
		}

		if ($this->params->get('double_optin'))
		{
			$status = 'pending';
		}
		else
		{
			$status = 'subscribed';
		}

		foreach ($listIds as $listId)
		{
			$data = [
				'id'              => $listId,
				'email_address'   => $row->email,
				'merge_fields'    => [],
				'status'          => $status,
				'update_existing' => true,
			];

			if ($row->first_name)
			{
				$data['merge_fields']['FNAME'] = $row->first_name;
			}

			if ($row->last_name)
			{
				$data['merge_fields']['LNAME'] = $row->last_name;
			}

			if ($row->address)
			{
				$data['merge_fields']['ADDRESS'] = $row->address;
			}

			if ($row->phone)
			{
				$data['merge_fields']['PHONE'] = $row->phone;
			}

			$result = $mailchimp->post("lists/$listId/members", $data);

			if ($result === false)
			{
				$this->logError($data, $mailchimp->getLastError());
			}
		}
	}

	/**
	 * Run when a membership expired
	 *
	 * @param OSMembershipTableSubscriber $row
	 */
	public function onMembershipExpire($row)
	{
		$plan = JTable::getInstance('Osmembership', 'Plan');
		$plan->load($row->plan_id);
		$params = new Registry($plan->params);

		$listIds = explode(',', $params->get('remove_mailchimp_list_ids', ''));
		$listIds = array_filter($listIds);

		if (empty($listIds))
		{
			return;
		}

		$activePlans = OSMembershipHelper::getActiveMembershipPlans($row->user_id, array($row->id));
		$query       = $this->db->getQuery(true);
		$query->select('params')
			->from('#__osmembership_plans')
			->where('id IN  (' . implode(',', $activePlans) . ')');
		$this->db->setQuery($query);
		$rowPlans = $this->db->loadObjectList();

		if (count($rowPlans))
		{
			foreach ($rowPlans as $rowPlan)
			{
				$planParams  = new Registry($rowPlan->params);
				$planListIds = explode(',', $planParams->get('mailchimp_list_ids'));
				$listIds     = array_diff($listIds, $planListIds);
			}
		}

		if (empty($listIds))
		{
			return;
		}

		try
		{
			$mailchimp = new MailChimp($this->params->get('api_key'));
		}
		catch (Exception $e)
		{
			return;
		}

		$hash = $mailchimp->subscriberHash($row->email);

		foreach ($listIds as $listId)
		{
			$result = $mailchimp->delete("lists/$listId/members/$hash");

			if ($result === false)
			{
				$this->logError(['listId' => $listId, 'email' => $row->email], $mailchimp->getLastError());
			}
		}
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		try
		{
			$mailchimp = new MailChimp($this->params->get('api_key'));
		}
		catch (Exception $e)
		{
			return;
		}

		$lists = $mailchimp->get('lists', ['count' => 1000]);

		if ($lists === false)
		{
			JFactory::getApplication()->enqueueMessage('No Mailing Lists Found', 'warning');

			return;
		}

		$params        = new Registry($row->params);
		$listIds       = explode(',', $params->get('mailchimp_list_ids', ''));
		$removeListIds = explode(',', $params->get('remove_mailchimp_list_ids', ''));

		$options = array();

		foreach ($lists['lists'] as $list)
		{
			$options[] = JHtml::_('select.option', $list['id'], $list['name']);
		}
		?>

        <div class="row-fluid">
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_ACTIVE'); ?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
								<?php echo OSMembershipHelperHtml::getFieldLabel('mailchimp_list_ids', JText::_('OSM_ASSIGN_TO_MAILING_LISTS'), JText::_('OSM_ASSIGN_TO_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
								<?php echo JHtml::_('select.genericlist', $options, 'mailchimp_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'value', 'text', $listIds) ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="span6 pull-left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('OSM_WHEN_SUBSCRIPTION_EXPIRED'); ?></legend>
                    <table class="admintable adminform" style="width: 90%;">
                        <tr>
                            <td width="220" class="key">
								<?php echo OSMembershipHelperHtml::getFieldLabel('remove_mailchimp_list_ids', JText::_('OSM_REMOVE_FROM_MAILING_LISTS'), JText::_('OSM_REMOVE_FROM_MAILING_LISTS_EXPLAIN')); ?>
                            </td>
                            <td>
								<?php echo JHtml::_('select.genericlist', $options, 'remove_mailchimp_list_ids[]', 'class="inputbox" multiple="multiple" size="10"', 'value', 'text', $removeListIds); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </div>
		<?php
	}

	/**
	 * Log the error from API call
	 *
	 * @param array  $data
	 * @param string $error
	 */
	protected function logError($data, $error)
	{
		$text = '[' . date('m/d/Y g:i A') . '] - ';

		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $keyValue => $valueValue)
				{
					$text .= "$keyValue=$valueValue, ";
				}
			}
			else
			{
				$text .= "$key=$value, ";
			}
		}

		$text .= $error;

		$ipnLogFile = JPATH_ROOT . '/components/com_osmemership/mailchimp_api_errors.txt';
		$fp         = fopen($ipnLogFile, 'a');
		fwrite($fp, $text . "\n\n");
		fclose($fp);
	}
}
