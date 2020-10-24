<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
	
JToolbarHelper::title(   JText::_( 'OSM_EMAIL_MESSAGES' ), 'generic.png' );
JToolbarHelper::apply();
JToolbarHelper::save('save');
JToolbarHelper::cancel('cancel');

$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));
$translatable = JLanguageMultilang::isEnabled() && count($this->languages);
JHtml::_('behavior.core');
JHtml::_('behavior.tabstate');
?>
<form action="index.php?option=com_osmembership&view=message" method="post" name="adminForm" id="adminForm">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'message', array('active' => 'general-page'));

	echo JHtml::_('bootstrap.addTab', 'message', 'general-page', JText::_('OSM_GENERAL_MESSAGES', true));
	echo $this->loadTemplate('general', array('editor' => $editor));
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'message', 'renewal-page', JText::_('OSM_RENEWAL_MESSAGES', true));
	echo $this->loadTemplate('renewal', array('editor' => $editor));
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'message', 'upgrade-page', JText::_('OSM_UPGRADE_MESSAGES', true));
	echo $this->loadTemplate('upgrade', array('editor' => $editor));
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'message', 'recurring-page', JText::_('OSM_RECURRING_MESSAGES', true));
	echo $this->loadTemplate('recurring', array('editor' => $editor));
	echo JHtml::_('bootstrap.endTab');

	echo JHtml::_('bootstrap.addTab', 'message', 'reminder-page', JText::_('OSM_REMINDER_MESSAGES', true));
	echo $this->loadTemplate('reminder', array('editor' => $editor));
	echo JHtml::_('bootstrap.endTab');

	// Add support for custom messages layout
	if (file_exists(__DIR__ . '/default_custom_messages.php'))
	{
		echo JHtml::_('bootstrap.addTab', 'message', 'custom-messages-page', JText::_('OSM_CUSTOM_MESSAGES', true));
		echo $this->loadTemplate('custom_messages', array('config' => $config, 'editor' => $editor));
		echo JHtml::_('bootstrap.endTab');
	}

	if ($translatable)
	{
		echo JHtml::_('bootstrap.addTab', 'message', 'translation-page', JText::_('OSM_TRANSLATION', true));
		echo $this->loadTemplate('translation', array('editor' => $editor));
		echo JHtml::_('bootstrap.endTab');
	}

	echo JHtml::_('bootstrap.endTabSet');
?>
	<input type="hidden" name="task" value="" />	
</form>