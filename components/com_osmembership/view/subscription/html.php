<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */


defined('_JEXEC') or die;

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewSubscriptionHtml extends MPFViewHtml
{
	public function display()
	{
		$user  = JFactory::getUser();
		$model = $this->getModel();
		$item  = $model->getData();

		if ($item->user_id != $user->get('id'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('OSM_INVALID_ACTION'));
			$app->redirect(JUri::root(), 403);
		}

		//Form
		$rowFields = OSMembershipHelper::getProfileFields($item->plan_id, true, $item->language);
		$data      = OSMembershipHelper::getProfileData($item, $item->plan_id, $rowFields);
		$form      = new MPFForm($rowFields);
		$form->setData($data)->bindData();
		$form->buildFieldsDependency(false);

		$this->config = OSMembershipHelper::getConfig();
		$this->item   = $item;
		$this->form   = $form;

		parent::display();
	}
}
