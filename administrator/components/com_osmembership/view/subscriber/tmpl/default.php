<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die ;

JToolbarHelper::title(   JText::_( 'OSM_EDIT_SUBSCRIBER' ), 'generic.png' );
JToolbarHelper::save('save');
JToolbarHelper::cancel('cancel');

if (JFactory::getUser()->authorise('core.admin', 'com_osmembership'))
{
	JToolbarHelper::preferences('com_osmembership');
}
?>
<form action="index.php?option=com_osmembership&view=subscriber" method="post" name="adminForm" id="adminForm" autocomplete="off" enctype="multipart/form-data" class="form form-horizontal">
	<?php
		echo JHtml::_('bootstrap.startTabSet', 'osm-profile', array('active' => 'profile-page'));
		echo JHtml::_('bootstrap.addTab', 'osm-profile', 'profile-page', JText::_('OSM_PROFILE_INFORMATION', true));
		echo $this->loadTemplate('profile');
		echo JHtml::_('bootstrap.endTab');
		echo JHtml::_('bootstrap.addTab', 'osm-profile', 'subscription-history-page', JText::_('OSM_SUBSCRIPTION_HISTORY', true));
		echo $this->loadTemplate('subscriptions_history');
		echo JHtml::_('bootstrap.endTab');

		if (count($this->plugins))
		{
			$count = 0 ;

			foreach ($this->plugins as $plugin)
			{
				$count++ ;

				if (empty($plugin['form']))
				{
					continue;
				}

				echo JHtml::_('bootstrap.addTab', 'osm-profile', 'tab_'.$count, JText::_($plugin['title'], true));
				echo $plugin['form'];
				echo JHtml::_('bootstrap.endTab');
			}
		}

		echo JHtml::_('bootstrap.endTabSet');
	?>
    <input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>"/>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>