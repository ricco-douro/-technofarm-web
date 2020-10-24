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
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('OSM_SUBSCRIPTION_SETTINGS'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('download_id', JText::_('OSM_DOWNLOAD_ID'), JText::_('OSM_DOWNLOAD_ID_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<input type="text" name="download_id" class="input-xlarge" value="<?php echo $config->download_id; ?>" size="60" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('registration_integration', JText::_('OSM_REGISTRATION_INTEGRATION'), JText::_('OSM_REGISTRATION_INTEGRATION_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('registration_integration', $config->registration_integration); ?>
		</div>
	</div>
	<?php
	if (JComponentHelper::isInstalled('com_comprofiler') && JPluginHelper::isEnabled('osmembership', 'cb'))
	{
	?>
		<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('registration_integration' => '1')); ?>'>
			<div class="control-label">
				<?php echo OSMembershipHelperHtml::getFieldLabel('use_cb_api', JText::_('OSM_USE_CB_API'), JText::_('OSM_USE_CB_API_EXPLAIN')); ?>
			</div>
			<div class="controls">
				<?php echo OSMembershipHelperHtml::getBooleanInput('use_cb_api', $config->use_cb_api); ?>
			</div>
		</div>
	<?php
	}
	?>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('registration_integration' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('show_forgot_username_password', JText::_('OSM_SHOW_FORGOT_USERNAME_PASSWORD'), JText::_('OSM_SHOW_FORGOT_USERNAME_PASSWORD_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('show_forgot_username_password', $config->show_forgot_username_password); ?>
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('registration_integration' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('use_email_as_username', JText::_('OSM_USE_EMAIL_AS_USERNAME'), JText::_('OSM_USE_EMAIL_AS_USERNAME_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('use_email_as_username', $config->use_email_as_username); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('enable_avatar', JText::_('OSM_ENABLE_AVATAR'), JText::_('OSM_ENABLE_AVATAR_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('enable_avatar', $config->enable_avatar); ?>
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('enable_avatar' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('avatar_width', JText::_('OSM_AVATAR_WIDTH')); ?>
		</div>
		<div class="controls">
			<input type="text" name="avatar_width" class="input-small" value="<?php echo $this->config->avatar_width ? $this->config->avatar_width : 80; ?>" />
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('enable_avatar' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('avatar_width', JText::_('OSM_AVATAR_HEIGHT')); ?>
		</div>
		<div class="controls">
			<input type="text" name="avatar_height" class="input-small" value="<?php echo $this->config->avatar_height ? $this->config->avatar_height : 80; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('create_account_when_membership_active', JText::_('OSM_CREATE_ACCOUNT_WHEN_MEMBERSHIP_ACTIVE'), JText::_('OSM_CREATE_ACCOUNT_WHEN_MEMBERSHIP_ACTIVE_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('create_account_when_membership_active', $config->create_account_when_membership_active); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('send_activation_email', JText::_('OSM_SEND_ACTIVATION_EMAIL'), JText::_('OSM_SEND_ACTIVATION_EMAIL_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('send_activation_email', $config->send_activation_email); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('auto_login', JText::_('OSM_AUTO_LOGIN'), JText::_('OSM_AUTO_LOGIN_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('auto_login', $config->auto_login); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('auto_reload_user', JText::_('OSM_AUTO_RELOAD_USER'), JText::_('OSM_AUTO_RELOAD_USER_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('auto_reload_user', $config->auto_reload_user); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('synchronize_data', JText::_('OSM_SYNCHRONIZE_DATA'), JText::_('OSM_SYNCHRONIZE_DATA_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('synchronize_data', $config->synchronize_data); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('synchronize_email', JText::_('OSM_SYNCHRONIZE_EMAIL'), JText::_('OSM_SYNCHRONIZE_EMAIL_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('synchronize_email', isset($config->synchronize_email) ? $config->synchronize_email : 0); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('show_login_box_on_subscribe_page', JText::_('OSM_SHOW_LOGIN_BOX'), JText::_('OSM_SHOW_LOGIN_BOX')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('show_login_box_on_subscribe_page', $config->show_login_box_on_subscribe_page); ?>
		</div>
	</div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('show_upgrade_button', JText::_('OSM_SHOW_UPGRADE_BUTTON'), JText::_('OSM_SHOW_UPGRADE_BUTTON_EXPLAIN')); ?>
        </div>
        <div class="controls">
	        <?php echo OSMembershipHelperHtml::getBooleanInput('show_upgrade_button', $config->get('show_upgrade_button', 1)); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('hide_signup_button_if_upgrade_available', JText::_('OSM_HIDE_SIGN_UP_IF_UPGRADE_AVAILABLE'), JText::_('OSM_HIDE_SIGN_UP_IF_UPGRADE_EXPLAIN')); ?>
        </div>
        <div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('hide_signup_button_if_upgrade_available', $config->hide_signup_button_if_upgrade_available); ?>
        </div>
    </div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('number_days_before_renewal', JText::_('OSM_ALLOW_RENEWAL'), JText::_('OSM_ALLOW_RENEWAL_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<input type="text" name="number_days_before_renewal" class="input-mini" value="<?php echo (int)$this->config->number_days_before_renewal; ?>" size="10" />
			<?php echo JText::_('OSM_DAYS_BEFORE_SUBSCRIPTION_EXPIRED'); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('subscription_renew_behavior', JText::_('OSM_SUBSCRIPTION_RENEW_BEHAVIOR'), 'OSM_SUBSCRIPTION_RENEW_BEHAVIOR_EXPLAIN'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['subscription_renew_behavior']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('enable_captcha', JText::_('OSM_ENABLE_CAPTCHA'), ''); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_captcha']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('enable_coupon', JText::_('OSM_ENABLE_COUPON'), ''); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('enable_coupon', $config->enable_coupon); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('auto_generate_membership_id', JText::_('OSM_GENERATE_MEMBERSHIP_ID'), JText::_('OSM_GENERATE_MEMBERSHIP_ID_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('auto_generate_membership_id', $config->auto_generate_membership_id); ?>
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('auto_generate_membership_id' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('membership_id_prefix', JText::_('OSM_MEMBERSHIP_ID_PREFIX'), JText::_('OSM_MEMBERSHIP_ID_PREFIX_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<input type="text" name="membership_id_prefix" class="input-medium" value="<?php echo $this->config->membership_id_prefix; ?>"/>
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('auto_generate_membership_id' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('reset_membership_id', JText::_('OSM_RESET_MEMBERSHIP_ID'), JText::_('OSM_RESET_MEMBERSHIP_ID_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('reset_membership_id', $config->reset_membership_id); ?>
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('auto_generate_membership_id' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('membership_id_start_number', JText::_('OSM_MEMBERSHIP_ID_START_NUMBER'), JText::_('OSM_MEMBERSHIP_ID_START_NUMBER_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<input type="text" name="membership_id_start_number" class="inputbox" value="<?php echo $config->membership_id_start_number ? $config->membership_id_start_number : 1000; ?>" size="10" />
		</div>
	</div>
	<div class="control-group" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(array('auto_generate_membership_id' => '1')); ?>'>
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('membership_id_length', JText::_('OSM_MEMBERSHIP_ID_LENGTH'), JText::_('OSM_MEMBERSHIP_ID_LENGTH_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<input type="text" name="membership_id_length" class="inputbox" value="<?php echo $config->membership_id_length; ?>" size="10" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('enable_select_existing_users', JText::_('OSM_ENABLE_SELECT_EXISTING_USER'), JText::_('OSM_ENABLE_SELECT_EXISTING_USER_EXPLAINS')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('enable_select_existing_users', $config->enable_select_existing_users); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('include_group_members_in_export', JText::_('OSM_INCLUDE_GROUP_MEMBERS_IN_EXPORT')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('include_group_members_in_export', $config->include_group_members_in_export); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('delete_subscriptions_when_account_deleted', JText::_('OSM_DELETE_SUBSCRIPTIONS_WHEN_ACCOUNT_DELETED'), JText::_('OSM_DELETE_SUBSCRIPTIONS_WHEN_ACCOUNT_DELETED_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('delete_subscriptions_when_account_deleted', $config->delete_subscriptions_when_account_deleted); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('force_select_plan', JText::_('OSM_FORCE_SELECT_PLAN'), JText::_('OSM_FORCE_SELECT_PLAN_EXPLAIN')); ?>
		</div>
		<div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('force_select_plan', $config->force_select_plan); ?>
		</div>
	</div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('use_expired_date_as_start_date', JText::_('OSM_ALWAYS_USE_EXPIRED_DATE_AS_START_DATE_FOR_RENEWAL'), JText::_('OSM_ALWAYS_USE_EXPIRED_DATE_AS_START_DATE_FOR_RENEWAL_EXPLAIN')); ?>
        </div>
        <div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('use_expired_date_as_start_date', $config->get('use_expired_date_as_start_date', 0)); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('enable_select_show_hide_members_list', JText::_('OSM_ENABLE_SELECT_SHOW_HIDE_ON_MEMBERS_LIST'), JText::_('OSM_ENABLE_SELECT_SHOW_HIDE_ON_MEMBERS_LIST_EXPLAIN')); ?>
        </div>
        <div class="controls">
			<?php echo OSMembershipHelperHtml::getBooleanInput('enable_select_show_hide_members_list', $config->get('enable_select_show_hide_members_list', 0)); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('grace_period', JText::_('OSM_GRADE_PERIOD')); ?>
        </div>
        <div class="controls">
			<input type="number" min="0" name="grace_period" value="<?php echo $config->get('grace_period', 0); ?>" step="1" class="input-small" /> <?php echo $this->lists['grace_period_unit']; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('grace_period', JText::_('OSM_SUBSCRIPTION_FORM_LAYOUT')); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['subscription_form_layout']; ?>
        </div>
    </div>
</fieldset>
