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
 * Membership Pro controller
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipControllerConfiguration extends OSMembershipController
{
	/**
	 * Save configuration
	 */
	public function save()
	{
		$post  = $this->input->getData(MPF_INPUT_ALLOWRAW);
		$model = $this->getModel('configuration', array('ignore_request' => true));
		$model->store($post);
		$msg  = JText::_('OSM_CONFIGURATION_SAVED');
		$task = $this->getTask();
		if ($task == 'apply')
		{
			$this->setRedirect('index.php?option=com_osmembership&view=configuration', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_osmembership&view=' . $this->config['default_view'], $msg);
		}
	}

	/**
	 * Redirect back to default view afters users cancel an action
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_osmembership&view=' . $this->config['default_view']);
	}
}
