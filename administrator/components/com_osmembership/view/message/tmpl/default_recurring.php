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
<table class="admintable adminform" style="width:100%;">
	<tr>
		<td class="key" width="20%">
			<?php echo JText::_('OSM_RECURRING_SUBSCRIPTION_CANCEL_MESSAGE'); ?>
		</td>
		<td width="60%">
			<?php echo $editor->display( 'recurring_subscription_cancel_message',  $this->item->recurring_subscription_cancel_message , '100%', '250', '75', '8' ) ;?>
		</td>
		<td valign="top">
			<?php echo JText::_('OSM_RECURRING_SUBSCRIPTION_CANCEL_MESSAGE_EXPLAIN'); ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo JText::_('OSM_USER_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_SUBJECT'); ?>
		</td>
		<td>
			<input type="text" name="user_recurring_subscription_cancel_subject" class="input-xxlarge" value="<?php echo $this->item->user_recurring_subscription_cancel_subject; ?>" size="50" />
		</td>
		<td valign="top">
			<?php echo JText::_('OSM_USER_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_SUBJECT_EXPLAIN'); ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo JText::_('OSM_USER_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_BODY'); ?>
		</td>
		<td>
			<?php echo $editor->display( 'user_recurring_subscription_cancel_body',  $this->item->user_recurring_subscription_cancel_body , '100%', '250', '75', '8' ) ;?>
		</td>
		<td valign="top">
			<?php echo JText::_('OSM_USER_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_BODY_EXPLAIN'); ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo JText::_('OSM_ADMIN_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_SUBJECT'); ?>
		</td>
		<td>
			<input type="text" name="admin_recurring_subscription_cancel_subject" class="input-xxlarge" value="<?php echo $this->item->admin_recurring_subscription_cancel_subject; ?>" size="50" />
		</td>
		<td valign="top">
			<?php echo JText::_('OSM_ADMIN_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_SUBJECT_EXPLAIN'); ?>
		</td>
	</tr>
	<tr>
		<td class="key">
			<?php echo JText::_('OSM_ADMIN_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_BODY'); ?>
		</td>
		<td>
			<?php echo $editor->display( 'admin_recurring_subscription_cancel_body',  $this->item->admin_recurring_subscription_cancel_body , '100%', '250', '75', '8' ) ;?>
		</td>
		<td valign="top">
			<?php echo JText::_('OSM_ADMIN_RECURRING_SUBSCRIPTION_CANCEL_EMAIL_BODY_EXPLAIN'); ?>
		</td>
	</tr>
    <tr>
        <td class="key">
			<?php echo JText::_('OSM_OFFLINE_RECURRING_RENEWAL_EMAIL_SUBJECT'); ?>
        </td>
        <td>
            <input type="text" name="offline_recurring_email_subject" class="input-xxlarge" value="<?php echo $this->item->offline_recurring_email_subject; ?>" size="50" />
        </td>
        <td valign="top">
            <strong><?php echo JText::_('OSM_AVAILABLE_TAGS'); ?> : [PLAN_TITLE]</strong>
        </td>
    </tr>
    <tr>
        <td class="key">
			<?php echo JText::_('OSM_OFFLINE_RECURRING_RENEWAL_EMAIL_BODY'); ?>
        </td>
        <td>
			<?php echo $editor->display( 'offline_recurring_email_body',  $this->item->offline_recurring_email_body , '100%', '250', '75', '8' ) ;?>
        </td>
        <td valign="top">
            <strong><?php echo JText::_('OSM_AVAILABLE_TAGS'); ?> :[SUBSCRIPTION_DETAIL], [PLAN_TITLE], [FIRST_NAME], [LAST_NAME], [ORGANIZATION], [ADDRESS], [ADDRESS2], [CITY], [STATE], [ZIP], [COUNTRY], [PHONE], [FAX], [EMAIL], [COMMENT], [AMOUNT], [TRANSACTION_ID], [PAYMENT_METHOD]</strong>
        </td>
    </tr>
</table>
