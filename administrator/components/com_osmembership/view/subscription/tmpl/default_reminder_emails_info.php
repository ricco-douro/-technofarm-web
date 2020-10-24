<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
?>
<fieldset>
	<legend><?php echo JText::_('OSM_REMINDER_EMAILS_INFORMATION'); ?></legend>
	<?php
	$nullDate = JFactory::getDbo()->getNullDate();

	if ($plan->send_first_reminder != 0)
	{
	?>
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('OSM_FIRST_REMINDER_EMAIL'); ?>
			</div>
			<div class="controls">
				<?php
				echo OSMembershipHelperHtml::getBooleanInput('first_reminder_sent', $this->item->first_reminder_sent);

				if ($this->item->first_reminder_sent && $this->item->first_reminder_sent_at && $this->item->first_reminder_sent_at != $nullDate)
				{
					echo JText::sprintf('OSM_SENT_AT', JHtml::_('date', $this->item->first_reminder_sent_at, $this->config->date_format.' H:i:s'));
				}
				elseif($this->item->first_reminder_sent)
				{
					echo JText::_('OSM_WILL_NOT_BE_SENT');
				}
				else
				{
					$date = JFactory::getDate($this->item->to_date);

					if ($plan->send_first_reminder > 0)
					{
						$date->modify('-' . $plan->send_first_reminder.' days');
					}
					else
					{
						$date->modify('+' . abs($plan->send_first_reminder) . ' days');
					}

					echo JText::sprintf('OSM_WILL_BE_SENT_AT', JHtml::_('date', $date->toSql(), $this->config->date_format.' H:i:s'));
				}
				?>
			</div>
		</div>
	<?php
	}

	if ($plan->send_second_reminder != 0)
	{
	?>
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('EB_SECOND_REMINDER_EMAIL'); ?>
			</div>
			<div class="controls">
				<?php
				echo OSMembershipHelperHtml::getBooleanInput('second_reminder_sent', $this->item->second_reminder_sent);

				if ($this->item->second_reminder_sent && $this->item->second_reminder_sent_at && $this->item->second_reminder_sent_at != $nullDate)
				{
					echo JText::sprintf('OSM_SENT_AT', JHtml::_('date', $this->item->second_reminder_sent_at, $this->config->date_format.' H:i:s'));
				}
				elseif($this->item->second_reminder_sent)
				{
					echo JText::_('OSM_WILL_NOT_BE_SENT');
				}
				else
				{
					$date = JFactory::getDate($this->item->to_date);

					if ($plan->send_second_reminder > 0)
					{
						$date->modify('-' . $plan->send_second_reminder.' days');
					}
					else
					{
						$date->modify('+' . abs($plan->send_second_reminder) . ' days');
					}

					echo JText::sprintf('OSM_WILL_BE_SENT_AT', JHtml::_('date', $date->toSql(), $this->config->date_format.' H:i:s'));
				}
				?>
			</div>
		</div>
	<?php
	}

	if ($plan->send_third_reminder != 0)
	{
	?>
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('EB_THIRD_REMINDER_EMAIL'); ?>
			</div>
			<div class="controls">
				<?php
				echo OSMembershipHelperHtml::getBooleanInput('third_reminder_sent', $this->item->third_reminder_sent);

				if ($this->item->third_reminder_sent && $this->item->third_reminder_sent_at && $this->item->third_reminder_sent_at != $nullDate)
				{
					echo JText::sprintf('OSM_SENT_AT', JHtml::_('date', $this->item->third_reminder_sent_at, $this->config->date_format.' H:i:s'));
				}
				elseif($this->item->third_reminder_sent)
				{
					echo JText::_('OSM_WILL_NOT_BE_SENT');
				}
				else
				{
					$date = JFactory::getDate($this->item->to_date);

					if ($plan->send_third_reminder > 0)
					{
						$date->modify('-' . $plan->send_third_reminder.' days');
					}
					else
					{
						$date->modify('+' . abs($plan->send_third_reminder) . ' days');
					}

					echo JText::sprintf('OSM_WILL_BE_SENT_AT', JHtml::_('date', $date->toSql(), $this->config->date_format.' H:i:s'));
				}
				?>
			</div>
		</div>
	<?php
	}

	if ($plan->send_subscription_end != 0 && $this->item->subscription_end_sent && $this->item->subscription_end_sent_at && $this->item->subscription_end_sent_at != $nullDate)
	{
	?>
        <div class="control-group">
            <div class="control-label">
				<?php echo JText::_('OSM_SUBSCRIPTION_END_EMAIL'); ?>
            </div>
            <div class="controls">
				<?php
				echo OSMembershipHelperHtml::getBooleanInput('subscription_end_sent', $this->item->subscription_end_sent);

				if ($this->item->subscription_end_sent && $this->item->subscription_end_sent_at && $this->item->subscription_end_sent_at != $nullDate)
				{
					echo JText::sprintf('OSM_SENT_AT', JHtml::_('date', $this->item->subscription_end_sent_at, $this->config->date_format.' H:i:s'));
				}
                elseif($this->item->subscription_end_sent)
				{
					echo JText::_('OSM_WILL_NOT_BE_SENT');
				}
				else
				{
					$date = JFactory::getDate($this->item->to_date);

					if ($plan->send_subscription_end > 0)
					{
						$date->modify('-' . $plan->send_subscription_end.' days');
					}
					else
					{
						$date->modify('+' . abs($plan->send_subscription_end) . ' days');
					}

					echo JText::sprintf('OSM_WILL_BE_SENT_AT', JHtml::_('date', $date->toSql(), $this->config->date_format.' H:i:s'));
				}
				?>
            </div>
        </div>
	<?php
	}

	if ($plan->recurring_subscription && strpos($this->item->payment_method, 'os_offline') !== false && JPluginHelper::isEnabled('system', 'mpofflinerecurringinvoice'))
    {
    ?>
    <div class="control-group">
        <div class="control-label">
			<?php echo JText::_('OSM_OFFLINE_RECURRING_EMAIL'); ?>
        </div>
        <div class="controls">
           <?php echo OSMembershipHelperHtml::getBooleanInput('offline_recurring_email_sent', $this->item->offline_recurring_email_sent); ?>
        </div>
    </div>
    <?php
    }
	?>
</fieldset>
