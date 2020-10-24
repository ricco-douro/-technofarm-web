<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

?>
<fieldset class="adminform">
	<legend class="adminform"><?php echo JText::_('OSM_REMINDERS_SETTINGS'); ?></legend>
    <div class="control-group">
        <div class="control-label">
			<?php echo  JText::_('OSM_SEND_FIRST_REMINDER'); ?>
        </div>
        <div class="controls">
            <input type="number" class="input-mini" name="send_first_reminder" value="<?php echo $this->item->send_first_reminder; ?>" size="5" /><span><?php echo ' ' . JText::_('OSM_DAYS') . ' ' . $this->lists['send_first_reminder_time']; ?></span><?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo  JText::_('OSM_SEND_SECOND_REMINDER'); ?>
        </div>
        <div class="controls">
            <input type="number" class="input-mini" name="send_second_reminder" value="<?php echo $this->item->send_second_reminder; ?>" size="5" /><span><?php echo ' ' . JText::_('OSM_DAYS') . ' ' . $this->lists['send_second_reminder_time']; ?></span><?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo  JText::_('OSM_SEND_THIRD_REMINDER'); ?>
        </div>
        <div class="controls">
            <input type="number" class="input-mini" name="send_third_reminder" value="<?php echo $this->item->send_third_reminder; ?>" size="5" /><span><?php echo ' ' . JText::_('OSM_DAYS') . ' ' . $this->lists['send_third_reminder_time']; ?></span><?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <?php
        if ($this->item->number_payments > 0)
        {
        ?>
            <div class="control-group">
                <div class="control-label">
			        <?php echo  JText::_('OSM_SEND_SUBSCRIPTION_END'); ?>
                </div>
                <div class="controls">
                    <input type="number" class="input-mini" name="send_subscription_end" value="<?php echo $this->item->send_subscription_end; ?>" size="5" /><span><?php echo ' ' . JText::_('OSM_DAYS') . ' ' . $this->lists['send_subscription_end_time']; ?></span><?php echo JText::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
                </div>
            </div>
        <?php
        }
    ?>
</fieldset>
