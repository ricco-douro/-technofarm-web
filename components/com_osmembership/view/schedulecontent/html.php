<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */


defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class OSMembershipViewSchedulecontentHtml extends MPFViewHtml
{
	public function display()
	{
		if (!JPluginHelper::isEnabled('system', 'schedulecontent'))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('Schedule Content feature is not enabled. Please contact super administrator'));

			return;
		}

		$this->requestLogin();

		$plugin = JPluginHelper::getPlugin('system', 'schedulecontent');

		$params = new Registry($plugin->params);


		/* @var $model OSmembershipModelSchedulecontent */
		$model                              = $this->getModel();
		$this->items                        = $model->getData();
		$this->config                       = OSMembershipHelper::getConfig();
		$this->pagination                   = $model->getPagination();
		$this->subscriptions                = OSMembershipHelperSubscription::getUserSubscriptionsInfo();
		$this->releaseArticleOlderThanXDays = (int) $params->get('release_article_older_than_x_days', 0);

		parent::display();
	}
}
