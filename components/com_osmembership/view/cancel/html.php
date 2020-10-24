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
 * HTML View class for the Membership Pro component
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewCancelHtml extends MPFViewHtml
{
	public $hasModel = false;

	public function display()
	{
		$id    = $this->input->getInt('id');
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('published')
			->from('#__osmembership_subscribers')
			->where('id = ' . $id);
		$db->setQuery($query);
		$published = (int) $db->loadResult();

		// Fix PayPal redirect users to cancel page although payment success
		if ($published === 1)
		{
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_osmembership&view=complete&Itemid=' . $this->input->getInt('Itemid'), false));
		}

		$this->setLayout('default');
		$messageObj  = OSMembershipHelper::getMessages();
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();

		if (strlen(strip_tags($messageObj->{'cancel_message' . $fieldSuffix})))
		{
			$message = $messageObj->{'cancel_message' . $fieldSuffix};
		}
		else
		{
			$message = $messageObj->cancel_message;
		}

		$this->message = $message;

		parent::display();
	}
}
