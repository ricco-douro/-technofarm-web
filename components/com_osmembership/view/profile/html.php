<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */


defined('_JEXEC') or die;

class OSMembershipViewProfileHtml extends MPFViewHtml
{
	public function display()
	{
		$this->requestLogin('OSM_LOGIN_TO_EDIT_PROFILE');

		/* @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$user   = JFactory::getUser();
		$config = OSMembershipHelper::getConfig();
		$item   = OSMembershipHelperSubscription::getMembershipProfile($user->id);

		if (!$item)
		{
			if (OSMembershipHelperSubscription::fixProfileId($user->id))
			{
				// Redirect to current page after fixing the data
				$app->redirect(JUri::getInstance()->toString());
			}
			else
			{
				// User don't have any active subscription, redirect to user profile page
				$app->enqueueMessage(JText::_('OSM_DONOT_HAVE_SUBSCRIPTION_RECORD'));
				$app->redirect(JRoute::_('index.php?option=com_users&view=profile', false));
			}
		}

		// Fix wrong data for profile record
		if ($item->id != $item->profile_id)
		{
			$db               = JFactory::getDbo();
			$query            = $db->getQuery(true);
			$item->profile_id = $item->id;
			$query->update('#__osmembership_subscribers')
				->set('profile_id = ' . $item->id)
				->where('id = ' . $item->id);
			$db->setQuery($query);
			$db->execute();
		}

		// Get subscriptions history
		/* @var OSMembershipModelSubscriptions $model */
		$model = JModelLegacy::getInstance('Subscriptions', 'OSMembershipModel');
		$items = $model->getData();

		if (OSMembershipHelper::isUniquePlan($item->user_id))
		{
			$planId = $item->plan_id;
		}
		else
		{
			$planId = 0;
		}

		// Form
		$rowFields = OSMembershipHelper::getProfileFields($planId);
		$data      = OSMembershipHelper::getProfileData($item, $planId, $rowFields);
		$form      = new MPFForm($rowFields);

		if (!isset($data['country']) || !$data['country'])
		{
			$data['country'] = $config->default_country;
		}

		$form->setData($data)->bindData();
		$form->buildFieldsDependency();

		// Trigger third party add-on
		JPluginHelper::importPlugin('osmembership');
		$results = $app->triggerEvent('onProfileDisplay', array($item));

		if ($item->group_admin_id == 0)
		{
			list($planIds, $renewOptions) = OSMembershipHelperSubscription::getRenewOptions($user->id);

			$this->upgradeRules = OSMembershipHelperSubscription::getUpgradeRules($item->user_id);

			$this->planIds      = $planIds;
			$this->renewOptions = $renewOptions;
			$this->plans        = OSMembershipHelperDatabase::getAllPlans('id');
		}

		// Load js file to support state field dropdown
		OSMembershipHelper::addLangLinkForAjax();

		$document = JFactory::getDocument();
		$rootUri  = JUri::root(true);

		if ($config->twitter_bootstrap_version == 'uikit3' && $config->load_bootstrap_compatible_css == '1')
		{
			$document->addStyleSheet($rootUri . '/media/com_osmembership/assets/css/bootstrap.compat.css');
		}

		$document->addScript($rootUri . '/media/com_osmembership/assets/js/paymentmethods.min.js');

		$customJSFile = JPATH_ROOT . '/media/com_osmembership/assets/js/custom.js';

		if (file_exists($customJSFile) && filesize($customJSFile) > 0)
		{
			$document->addScript($rootUri . '/media/com_osmembership/assets/js/custom.js');
		}

		if ($config->get('enable_select_show_hide_members_list'))
		{
			$options   = [];
			$options[] = JHtml::_('select.option', 1, JText::_('JYES'));
			$options[] = JHtml::_('select.option', 0, JText::_('JNO'));

			$this->lists['show_on_members_list'] = JHtml::_('select.genericlist', $options, 'show_on_members_list', '', 'value', 'text', $item->show_on_members_list);
		}

		// Need to get subscriptions information of the user
		$this->item            = $item;
		$this->config          = $config;
		$this->items           = $items;
		$this->form            = $form;
		$this->plugins         = $results;
		$this->subscriptions   = OSMembershipHelper::getSubscriptions($item->profile_id);
		$this->bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$this->params          = $app->getParams();

		$showDownloadMemberCard = false;

		foreach ($this->subscriptions as $subscription)
		{
			if ($subscription->activate_member_card_feature && in_array($subscription->subscription_status, [1, 2]))
			{
				$showDownloadMemberCard = true;

				$subscription->show_download_member_card = $showDownloadMemberCard;
			}
		}

		$this->showDownloadMemberCard = $showDownloadMemberCard;

		parent::display();
	}
}
