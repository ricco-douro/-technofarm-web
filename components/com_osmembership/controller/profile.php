<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

class OSMembershipControllerProfile extends OSMembershipController
{
	/**
	 * Update user profile data
	 */
	public function update()
	{
		$Itemid = $this->input->getInt('Itemid', 0);
		$data   = $this->input->getData();

		/**@var OSMembershipModelProfile $model * */
		$model      = $this->getModel();
		$data['id'] = (int) $data['cid'][0];

		try
		{
			$model->updateProfile($data, $this->input);
			$message = JText::_('OSM_YOUR_PROFILE_UPDATED');
			$type    = 'message';
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			$type    = 'error';
		}

		//Redirect to the profile page
		$this->setRedirect(JRoute::_('index.php?option=com_osmembership&view=profile&Itemid=' . $Itemid, false), $message, $type);
	}

	/**
	 * Update subscription credit card
	 */
	public function update_card()
	{
		$this->csrfProtection();

		$Itemid = $this->input->getInt('Itemid', 0);
		$data   = $this->input->post->getData();

		/**@var OSMembershipModelProfile $model * */
		$model = $this->getModel();

		try
		{
			$model->updateCard($data);
			$message = JText::_('OSM_CREDITCARD_UPDATED');
			$type    = 'message';
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			$type    = 'error';
		}

		//Redirect to the profile page
		$this->setRedirect(JRoute::_('index.php?option=com_osmembership&view=profile&Itemid=' . $Itemid), $message, $type);
	}

	/**
	 * Download member card
	 */
	public function download_member_card()
	{
		$config = OSMembershipHelper::getConfig();
		$user   = JFactory::getUser();

		if (!$config->activate_member_card_feature)
		{
			throw new Exception('This feature is not enabled. If you are administrator and want to use it, go to Membership Pro -> Configuration to enable this feature', 403);
		}

		$item = OSMembershipHelperSubscription::getMembershipProfile($user->id);

		if (!$item)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_osmembesrhip&view=profile'), JText::_('You need to subscribe for at least one subscription plan in our system to download member card'));

			return;
		}

		// Generate member card and save it
		$path = OSMembershipHelperSubscription::generateMemberCard($item, $config);

		while (@ob_end_clean()) ;
		OSMembershipHelper::processDownload($path, $item->username . '.pdf');

		$this->app->close();
	}

	/**
	 * Download member card
	 */
	public function download_member_plan_card()
	{
		$config = OSMembershipHelper::getConfig();
		$user   = JFactory::getUser();

		if (!$config->activate_member_card_feature)
		{
			throw new Exception('This feature is not enabled. If you are administrator and want to use it, go to Membership Pro -> Configuration to enable this feature', 403);
		}

		$item = OSMembershipHelperSubscription::getMembershipProfile($user->id);

		if (!$item)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_osmembership&view=profile'), JText::_('You need to subscribe for at least one subscription plan in our system to download member card'));

			return;
		}

		$planId =  $this->input->getInt('plan_id');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.username')
			->from('#__osmembership_subscribers AS a')
			->innerJoin('#__users AS b ON a.user_id = b.id')
			->where('a.user_id = '. $user->id)
			->where('a.plan_id = '. $planId)
			->where('published IN (1, 2)')
			->order('id');
		$db->setQuery($query);
		$item = $db->loadObject();

		if (!$item)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_osmembership&view=profile'), JText::_('You need to have a valid subscription of this plan download member card'));

			return;
		}

		// Generate member card and save it
		$path = OSMembershipHelperSubscription::generatePlanMemberCard($item, $config);

		while (@ob_end_clean()) ;
		OSMembershipHelper::processDownload($path, $item->username . '_' . $item->plan_id . '.pdf');

		$this->app->close();
	}
}
