<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class OSMembershipControllerRegister extends OSMembershipController
{
	/**
	 * Initialize data for renewing membership
	 */
	public function process_renew_membership()
	{
		$renewOptionId = $this->input->getString('renew_option_id', 0);

		if (!$renewOptionId)
		{
			$this->app->enqueueMessage(JText::_('OSM_INVALID_RENEW_MEMBERSHIP_OPTION'));
			$this->app->redirect(JUri::root(), 404);
		}

		if (strpos($renewOptionId, '|') !== false)
		{
			$renewOptionArray = explode('|', $renewOptionId);
			$this->input->set('id', (int) $renewOptionArray[0]);
			$this->input->set('renew_option_id', (int) $renewOptionArray[1]);
		}
		else
		{
			$this->input->set('id', (int) $renewOptionId);
			$this->input->set('renew_option_id', OSM_DEFAULT_RENEW_OPTION_ID);
		}

		$this->input->set('view', 'register');
		$this->input->set('layout', 'default');
		$this->display();
	}

	/**
	 * Initialize data for upgrading membership
	 */
	public function process_upgrade_membership()
	{
		$upgradeOptionId = $this->input->getInt('upgrade_option_id', 0);
		$db              = JFactory::getDbo();
		$query           = $db->getQuery(true);
		$query->select('to_plan_id')
			->from('#__osmembership_upgraderules')
			->where('id=' . $upgradeOptionId);
		$db->setQuery($query);
		$upgradeRule = $db->loadObject();

		if ($upgradeRule)
		{
			//Set Plan ID
			$this->input->set('id', $upgradeRule->to_plan_id);
			$this->input->set('view', 'register');
			$this->input->set('layout', 'default');
			$this->display();
		}
		else
		{
			$this->app->enqueueMessage(JText::_('OSM_INVALID_UPGRADE_MEMBERSHIP_OPTION'));
			$this->app->redirect(JUri::root(), 404);
		}
	}

	/**
	 * Process subscription
	 *
	 * @throws Exception
	 */
	public function process_subscription()
	{
		$this->csrfProtection();
		$config = OSMembershipHelper::getConfig();

		$input = $this->input;

		if (!empty($config->use_email_as_username) && !JFactory::getUser()->get('id'))
		{
			$input->post->set('username', $input->post->getString('email'));
		}

		if (!$input->post->has('first_name') && !$input->post->has('last_name'))
		{
			$input->post->set('first_name', $input->post->get('email'));
		}

		// Validate captcha
		$user = JFactory::getUser();

		if ($config->enable_captcha == 1 || ($config->enable_captcha == 2 && !$user->id))
		{
			$captchaPlugin = $this->app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

			if (!$captchaPlugin)
			{
				// Hardcode to recaptcha, reduce support request
				$captchaPlugin = 'recaptcha';
			}

			$plugin = JPluginHelper::getPlugin('captcha', $captchaPlugin);

			if ($plugin)
			{
				try
				{
					$res = JCaptcha::getInstance($captchaPlugin)->checkAnswer($input->post->get('recaptcha_response_field', '', 'string'));
				}
				catch (Exception $e)
				{
					$res = false;
				}

			}
			else
			{
				$res = true;
			}

			if (!$res)
			{
				$this->app->enqueueMessage(JText::_('OSM_INVALID_CAPTCHA_ENTERED'), 'warning');
				$input->set('view', 'register');
				$input->set('layout', 'default');
				$input->set('id', $input->getInt('plan_id', 0));
				$input->set('validation_error', 1);
				$this->display();

				return;
			}
		}

		// Validate user input

		/**@var OSMembershipModelRegister $model * */
		$model  = $this->getModel();
		$errors = $model->validate($input);

		if (count($errors))
		{
			// Enqueue the error messages
			foreach ($errors as $error)
			{
				$this->app->enqueueMessage($error, 'error');
			}

			$input->set('view', 'register');
			$input->set('layout', 'default');
			$input->set('id', $input->getInt('plan_id', 0));
			$input->set('validation_error', 1);
			$this->display();

			return;
		}

		// OK, data validation success, process the subscription

		try
		{
			$data = $input->post->getData();
			$model->processSubscription($data, $input);
		}
		catch (Exception $e)
		{
			$this->app->enqueueMessage($e->getMessage(), 'error');
			$input->set('view', 'register');
			$input->set('layout', 'default');
			$input->set('id', $input->getInt('plan_id', 0));
			$input->set('validation_error', 1);
			$this->display();

			return;
		}
	}

	/**
	 * Verify the payment and further process. Called by payment gateway when a payment completed
	 */
	public function payment_confirm()
	{
		/**@var OSMembershipModelRegister $model * */

		$model         = $this->getModel();
		$paymentMethod = $this->input->getString('payment_method');
		$model->paymentConfirm($paymentMethod);
	}

	/**
	 * Verify the payment and further process. Called by payment gateway when a recurring payment happened
	 */
	public function recurring_payment_confirm()
	{
		/**@var OSMembershipModelRegister $model * */

		$model         = $this->getModel();
		$paymentMethod = $this->input->getString('payment_method');
		$model->recurringPaymentConfirm($paymentMethod);
	}

	/**
	 * Cancel recurring subscription
	 *
	 * @throws Exception
	 */
	public function process_cancel_subscription()
	{
		$this->csrfProtection();
		$subscriptionId = $this->input->post->get('subscription_id', '', 'none');
		$Itemid         = $this->input->getInt('Itemid', 0);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_subscribers')
			->where('subscription_id = ' . $db->quote($subscriptionId));
		$db->setQuery($query);
		$rowSubscription = $db->loadObject();

		if ($rowSubscription && OSMembershipHelper::canCancelSubscription($rowSubscription))
		{
			/**@var OSMembershipModelRegister $model * */
			$model = $this->getModel('Register');
			$ret   = $model->cancelSubscription($rowSubscription);

			if ($ret)
			{
				JFactory::getSession()->set('mp_subscription_id', $rowSubscription->id);
				$this->app->redirect('index.php?option=com_osmembership&view=subscriptioncancel&Itemid=' . $Itemid);
			}
			else
			{
				// Redirect back to profile page, the payment plugin should enque the reason of failed cancellation so that it could be displayed to end user
				$this->app->redirect('index.php?option=com_osmembership&view=profile&Itemid=' . $Itemid);
			}
		}
		else
		{
			// Redirect back to user profile page
			$this->app->enqueueMessage(JText::_('OSM_INVALID_SUBSCRIPTION'));
			$this->app->redirect('index.php?option=com_osmembership&view=profile&Itemid=' . $Itemid, 404);
		}
	}

	/**
	 * Re-calculate subscription fee when subscribers choose a fee option on subscription form
	 *
	 * Called by ajax request. After calculation, the system will update the fee displayed on end users on subscription sign up form
	 */
	public function calculate_subscription_fee()
	{
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$config = OSMembershipHelper::getConfig();
		$planId = $this->input->getInt('plan_id', 0);
		$query->select('*')
			->from('#__osmembership_plans')
			->where('id=' . $planId);
		$db->setQuery($query);
		$rowPlan   = $db->loadObject();
		$rowFields = OSMembershipHelper::getProfileFields($planId);
		$data      = $this->input->getData();
		$form      = new MPFForm($rowFields);
		$form->setData($data)->bindData(false);

		if (is_callable('OSMembershipHelperOverrideHelper::calculateSubscriptionFee'))
		{
			$fees = OSMembershipHelperOverrideHelper::calculateSubscriptionFee($rowPlan, $form, $data, $config, $this->input->get('payment_method', '', 'none'));
		}
		else
		{
			$fees = OSMembershipHelper::calculateSubscriptionFee($rowPlan, $form, $data, $config, $this->input->get('payment_method', '', 'none'));
		}

		$amountFields = array(
			'setup_fee',
			'amount',
			'discount_amount',
			'tax_amount',
			'payment_processing_fee',
			'gross_amount',
			'trial_amount',
			'trial_discount_amount',
			'trial_tax_amount',
			'trial_payment_processing_fee',
			'trial_gross_amount',
			'regular_amount',
			'regular_discount_amount',
			'regular_tax_amount',
			'regular_payment_processing_fee',
			'regular_gross_amount',
		);

		foreach ($amountFields as $field)
		{
			if (isset($fees[$field]))
			{
				$fees[$field] = OSMembershipHelper::formatAmount($fees[$field], $config);
			}
		}

		echo json_encode($fees);

		$this->app->close();
	}

	/**
	 * Get list of states for the selected country, using in AJAX request
	 */
	public function get_states()
	{
		$config      = OSMembershipHelper::getConfig();
		$countryName = $this->input->get('country_name', '', 'string');
		$fieldName   = $this->input->get('field_name', 'state', 'string');
		$stateName   = $this->input->get('state_name', '', 'string');

		if (!$countryName)
		{
			$countryName = OSMembershipHelper::getConfigValue('default_country');
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('required')
			->from('#__osmembership_fields')
			->where('name=' . $db->quote('state'));
		$db->setQuery($query);
		$required = $db->loadResult();
		($required) ? $class = 'validate[required]' : $class = '';

		$query->clear()
			->select('country_id')
			->from('#__osmembership_countries')
			->where('name=' . $db->quote($countryName));
		$db->setQuery($query);
		$countryId = $db->loadResult();

		//get state
		$query->clear()
			->select('state_2_code AS value, state_name AS text')
			->from('#__osmembership_states')
			->where('country_id=' . (int) $countryId)
			->where('published=1')
			->order('state_name');
		$db->setQuery($query);
		$states  = $db->loadObjectList();
		$options = array();

		if (count($states))
		{
			$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_STATE'));
			$options   = array_merge($options, $states);
		}
		else
		{
			$options[] = JHtml::_('select.option', 'N/A', JText::_('OSM_NA'));
		}

		if ($config->twitter_bootstrap_version == 'uikit3')
		{
			$inputClass = 'uk-select';
		}
		else
		{
			$inputClass = 'input-large';
		}

		echo JHtml::_('select.genericlist', $options, $fieldName, ' class="' . $inputClass . ' ' . $class . '" id="' . $fieldName . '"', 'value', 'text', $stateName);

		$this->app->close();
	}

	/**
	 * Get depend fields status to show/hide custom fields based on selected options
	 */
	public function get_depend_fields_status()
	{
		$input   = $this->input;
		$db      = JFactory::getDbo();
		$fieldId = $this->input->get('field_id', 'int');

		$hiddenFields = array();

		//Get list of depend fields
		$allFieldIds = OSMembershipHelper::getAllDependencyFields($fieldId);

		//Get list of depend fields
		$languageSuffix = OSMembershipHelper::getFieldSuffix();
		$query          = $db->getQuery(true);
		$query->select('*')
			->from('#__osmembership_fields')
			->where('id IN (' . implode(',', $allFieldIds) . ')')
			->where('published=1')
			->order('ordering');

		if ($languageSuffix)
		{
			$query->select('depend_on_options' . $languageSuffix . ' AS depend_on_options');
		}

		$db->setQuery($query);
		$rowFields = $db->loadObjectList();

		$masterFields = array();
		$fieldsAssoc  = array();

		foreach ($rowFields as $rowField)
		{
			if ($rowField->depend_on_field_id)
			{
				$masterFields[] = $rowField->depend_on_field_id;
			}

			$fieldsAssoc[$rowField->id] = $rowField;
		}

		$masterFields = array_unique($masterFields);

		if (count($masterFields))
		{
			foreach ($rowFields as $rowField)
			{
				if ($rowField->depend_on_field_id && isset($fieldsAssoc[$rowField->depend_on_field_id]))
				{
					// If master field is hided, then children field should be hided, too
					if (in_array($rowField->depend_on_field_id, $hiddenFields))
					{
						$hiddenFields[] = $rowField->id;
					}
					else
					{
						$fieldName = $fieldsAssoc[$rowField->depend_on_field_id]->name;

						$masterFieldValues = $input->get($fieldName, '', 'none');

						if (is_array($masterFieldValues))
						{
							$selectedOptions = $masterFieldValues;
						}
						else
						{
							$selectedOptions = array($masterFieldValues);
						}

						$dependOnOptions = explode(',', $rowField->depend_on_options);

						if (!count(array_intersect($selectedOptions, $dependOnOptions)))
						{
							$hiddenFields[] = $rowField->id;
						}
					}
				}
			}
		}


		$showFields = array();
		$hideFields = array();

		foreach ($rowFields as $rowField)
		{
			if (in_array($rowField->id, $hiddenFields))
			{
				$hideFields[] = 'field_' . $rowField->name;
			}
			else
			{
				$showFields[] = 'field_' . $rowField->name;
			}
		}

		echo json_encode(array('show_fields' => implode(',', $showFields), 'hide_fields' => implode(',', $hideFields)));

		$this->app->close();
	}
}
