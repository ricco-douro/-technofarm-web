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
 * Class OSMembershipViewMembersHtml
 *
 * @property OSMembershipHelperBootstrap $bootstrapHelper
 * @property JRegistry                   $params
 */
class OSMembershipViewMembersHtml extends MPFViewHtml
{
	public function display()
	{
		$user = JFactory::getUser();

		if (!$user->authorise('core.viewmembers', 'com_osmembership'))
		{
			if (!$user->id)
			{
				$this->requestLogin();
			}
			else
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('OSM_NOT_ALLOW_TO_VIEW_MEMBERS'));
				$app->redirect(JUri::root(), 403);
			}
		}

		/* @var OSMembershipModelMembers $model */
		$model  = $this->getModel();
		$state  = $model->getState();
		$fields = OSMembershipHelper::getProfileFields($state->id, true);

		for ($i = 0, $n = count($fields); $i < $n; $i++)
		{
			if (!$fields[$i]->show_on_members_list)
			{
				unset($fields[$i]);
			}
		}

		$fields = array_values($fields);

		$this->fields          = $fields;
		$this->state           = $state;
		$this->items           = $model->getData();
		$this->pagination      = $model->getPagination();
		$this->fieldsData      = $model->getFieldsData();
		$this->config          = OSMembershipHelper::getConfig();
		$this->params          = JFactory::getApplication()->getParams();
		$this->bootstrapHelper = OSMembershipHelperBootstrap::getInstance();

		parent::display();
	}
}
