<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die ;

if ($this->canCancelSubscription)
{
	JToolbarHelper::custom('cancel_subscription', 'delete', 'delete', JText::_('OSM_CANCEL_SUBSCRIPTION'), false);
}

if ($this->canRefundSubscription)
{
	JToolbarHelper::custom('refund', 'delete', 'delete', JText::_('OSM_REFUND'), false);
}

$document = JFactory::getDocument();
$document->addScriptDeclaration('
	var siteUrl = "' . JUri::root() . '";			
');
$document->addScript(JUri::root(true) . '/media/com_osmembership/assets/js/membershippro.js');

JHtml::_('formbehavior.chosen', 'select#country');
OSMembershipHelper::loadLanguage();
OSMembershipHelperJquery::validateForm();

$selectedState = '';
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton == 'cancel')
		{
			jQuery("#adminForm").validationEngine('detach');
			Joomla.submitform(pressbutton, form);
		}
		else if(pressbutton == 'cancel_subscription')
        {
            if (confirm("<?php echo JText::_('OSM_CANCEL_SUBSCRIPTION_CONFIRM') ?>"))
            {
                jQuery("#adminForm").validationEngine('detach');
                Joomla.submitform(pressbutton, form);
            }
        }
        else if(pressbutton == 'refund')
        {
            if (confirm("<?php echo JText::_('OSM_REFUND_SUBSCRIPTION_CONFIRM') ?>"))
            {
                jQuery("#adminForm").validationEngine('detach');
                Joomla.submitform(pressbutton, form);
            }
        }
		else
		{
			//Validate the entered data before submitting
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<div class="row-fluid" style="float:left">
<form action="index.php?option=com_osmembership&view=subscription" method="post" name="adminForm" id="adminForm" autocomplete="off" enctype="multipart/form-data" class="form form-horizontal">
	<div class="span6">
        <fieldset class="adminform">
            <legend><?php echo JText::_('OSM_ACCOUNT_INFORMATION'); ?></legend>
	        <?php
	        if (!$this->item->id && $this->config->registration_integration)
	        {
		    ?>
                <div class="control-group" id="username_container">
                    <label class="control-label">
				        <?php echo JText::_('OSM_USERNAME'); ?><span class="required">*</span>
                    </label>
                    <div class="controls">
                        <input type="text" name="username" size="20" class="validate[ajax[ajaxUserCall]]" value="" />
				        <?php echo JText::_('OSM_USERNAME_EXPLAIN'); ?>
                    </div>
                </div>
                <div class="control-group" id="password_container">
                    <label class="control-label">
				        <?php echo JText::_('OSM_PASSWORD'); ?><span class="required">*</span>
                    </label>
                    <div class="controls">
				        <?php
				        $params = JComponentHelper::getParams('com_users');
				        $minimumLength = $params->get('minimum_length', 4);
				        ($minimumLength) ? $minSize = "minSize[$minimumLength]" : $minSize = "";
				        $passwordValidation = ',ajax[ajaxValidatePassword]';
				        ?>
                        <input type="password" name="password" size="20" value="" class="validate[<?php echo $minSize.$passwordValidation;?>]" />
                    </div>
                </div>
		    <?php
	        }
	        ?>
            <div class="control-group">
                <label class="control-label">
			        <?php echo JText::_('OSM_USER'); ?>
                </label>
                <div class="controls">
			        <?php echo OSMembershipHelper::getUserInput($this->item->user_id, (int) $this->item->id) ; ?>
                </div>
            </div>
	        <?php
	        if ($this->config->enable_avatar)
	        {
		        $avatarExists = false;

		        if ($this->item->avatar && file_exists(JPATH_ROOT . '/media/com_osmembership/avatars/' . $this->item->avatar))
		        {
			        $avatarExists = true;
			    ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label><?php echo JText::_('OSM_AVATAR'); ?></label>
                        </div>
                        <div class="controls">
                            <img class="oms-avatar" src="<?php echo JUri::root(true) . '/media/com_osmembership/avatars/' . $this->item->avatar; ?>" />
                            <div id="osm-delete-avatar-container" style="margin-top: 10px;">
                                <label class="checkbox">
                                    <input type="checkbox" name="delete_avatar" value="1" />
			                        <?php echo JText::_('OSM_DELETE_AVATAR'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
			    <?php
		        }
		        ?>
                <div class="control-group">
                    <div class="control-label">
                        <label><?php echo $avatarExists ? JText::_('OSM_NEW_AVATAR') : JText::_('OSM_AVATAR'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="file" name="profile_avatar" accept="image/*">
                    </div>
                </div>
		        <?php
	        }

	        if ($this->config->get('enable_select_show_hide_members_list'))
            {
            ?>
                <div class="control-group">
                    <div class="control-label">
			            <?php echo OSMembershipHelperHtml::getFieldLabel('show_on_members_list', JText::_('OSM_SHOW_ON_MEMBERS_LIST')); ?>
                    </div>
                    <div class="controls">
			            <?php echo OSMembershipHelperHtml::getBooleanInput('show_on_members_list', $this->item->show_on_members_list); ?>
                    </div>
                </div>
            <?php
            }

	        if ($this->config->auto_generate_membership_id)
	        {
	        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo JText::_('OSM_MEMBERSHIP_ID'); ?>
                    </label>
                    <div class="controls">
                        <input type="text" name="membership_id" value="<?php echo $this->item->membership_id > 0 ? $this->item->membership_id : ''; ?>" class="inputbox" size="20" />
                    </div>
                </div>
		    <?php
	        }

	        $fields = $this->form->getFields();
	        $stateType = 0;

	        if (isset($fields['state']))
	        {
		        if ($fields['state']->type == 'State')
		        {
			        $stateType = 1;
		        }
		        else
		        {
			        $stateType = 0;
		        }

		        $selectedState = $fields['state']->value;
	        }

	        if (isset($fields['email']))
	        {
		        $fields['email']->setAttribute('class', 'validate[custom[email]]');
		        $fields['email']->required = false;
	        }

	        foreach ($fields as $field)
	        {
		        echo $field->getControlGroup();
	        }
	        ?>
        </fieldset>
    </div>
    <div class="span6">
        <fieldset class="adminform">
            <legend><?php echo JText::_('OSM_SUBSCRIPTION_INFORMATION'); ?></legend>
            <div class="control-group">
                <label class="control-label">
			        <?php echo JText::_('OSM_PLAN'); ?><span class="required">&nbsp;*</span>
                </label>
                <div class="controls">
			        <?php echo $this->lists['plan_id'] ; ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
			        <?php echo  JText::_('OSM_CREATED_DATE'); ?>
                </label>
                <div class="controls">
			        <?php echo JHtml::_('calendar', $this->item->created_date, 'created_date', 'created_date', $this->datePickerFormat . ' %H:%M:%S'); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
			        <?php echo  JText::_('OSM_SUBSCRIPTION_START_DATE'); ?>
                </label>
                <div class="controls">
			        <?php echo JHtml::_('calendar', $this->item->from_date, 'from_date', 'from_date', $this->datePickerFormat . ' %H:%M:%S'); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
			        <?php echo  JText::_('OSM_SUBSCRIPTION_END_DATE'); ?>
                </label>
                <div class="controls">
			        <?php
			        if ($this->item->lifetime_membership || $this->item->to_date == '2099-12-31 23:59:59')
			        {
				        echo JText::_('OSM_LIFETIME');
			        }
			        else
			        {
				        echo JHtml::_('calendar', $this->item->to_date, 'to_date', 'to_date', $this->datePickerFormat.' %H:%M:%S') ;
			        }
			        ?>
                </div>
            </div>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('OSM_PAYMENT_INFORMATION'); ?></legend>
	        <?php
	        if ($this->item->setup_fee > 0 || !$this->item->id)
	        {
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo  JText::_('OSM_SETUP_FEE'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="setup_fee" value="<?php echo $this->item->setup_fee > 0 ? round($this->item->setup_fee, 2) : ""; ?>" size="7" />
                    </div>
                </div>
		        <?php
	        }
	        ?>
            <div class="control-group">
                <label class="control-label">
			        <?php echo  JText::_('OSM_NET_AMOUNT'); ?>
                </label>
                <div class="controls">
			        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="amount" value="<?php echo $this->item->amount > 0 ? round($this->item->amount, 2) : ""; ?>" size="7" />
                </div>
            </div>
	        <?php
	        if ($this->item->discount_amount > 0 || !$this->item->id)
	        {
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo  JText::_('OSM_DISCOUNT_AMOUNT'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="discount_amount" value="<?php echo $this->item->discount_amount > 0 ? round($this->item->discount_amount, 2) : ""; ?>" size="7" />
                    </div>
                </div>
		        <?php
	        }

	        if ($this->item->tax_amount > 0 || !$this->item->id)
	        {
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo  JText::_('OSM_TAX_AMOUNT'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="tax_amount" value="<?php echo $this->item->tax_amount > 0 ? round($this->item->tax_amount, 2) : ""; ?>" size="7" />
                    </div>
                </div>
		        <?php
	        }

	        if ($this->item->payment_processing_fee > 0 || !$this->item->id)
	        {
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo  JText::_('OSM_PAYMENT_FEE'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="payment_processing_fee" value="<?php echo $this->item->payment_processing_fee > 0 ? round($this->item->payment_processing_fee, 2) : ""; ?>" size="7" />
                    </div>
                </div>
		        <?php
	        }
	        ?>
            <div class="control-group">
                <label class="control-label">
			        <?php echo  JText::_('OSM_GROSS_AMOUNT'); ?>
                </label>
                <div class="controls">
			        <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="gross_amount" value="<?php echo $this->item->gross_amount > 0 ? round($this->item->gross_amount, 2) : ""; ?>" size="7" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
			        <?php echo JText::_('OSM_PAYMENT_METHOD') ?>
                </label>
                <div class="controls">
			        <?php echo $this->lists['payment_method'] ; ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">
			        <?php echo JText::_('OSM_TRANSACTION_ID'); ?>
                </label>
                <div class="controls">
                    <input type="text" class="inputbox" size="50" name="transaction_id" id="transaction_id" value="<?php echo $this->item->transaction_id ; ?>" />
                </div>
            </div>

	        <?php
	        if (!empty($this->item->recurring_subscription))
	        {
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo JText::_('OSM_SUBSCRIPTION_ID'); ?>
                    </label>
                    <div class="controls">
                        <input type="text" class="inputbox" size="50" name="subscription_id" id="subscription_id" value="<?php echo $this->item->subscription_id ; ?>" />
                    </div>
                </div>
		        <?php
	        }
	        ?>

            <div class="control-group">
                <label class="control-label">
			        <?php echo JText::_('OSM_SUBSCRIPTION_STATUS'); ?>
                </label>
                <div class="controls">
			        <?php echo $this->lists['published'] ; ?>
                </div>
            </div>
	        <?php
	        if ($this->item->payment_method == "os_creditcard")
	        {
		        $params = new \Joomla\Registry\Registry($this->item->params);
		        ?>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo JText::_('OSM_FIRST_12_DIGITS_CREDITCARD_NUMBER'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $params->get('card_number'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo JText::_('AUTH_CARD_EXPIRY_DATE'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $params->get('exp_date'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
				        <?php echo JText::_('AUTH_CVV_CODE'); ?>
                    </label>
                    <div class="controls">
				        <?php echo $params->get('cvv'); ?>
                    </div>
                </div>
		        <?php
	        }
	        ?>
        </fieldset>
	    <?php
	    if ($this->item->plan_id > 0)
	    {
		    $plan = OSMembershipHelperDatabase::getPlan($this->item->plan_id);
	    }
	    else
	    {
		    $plan = null;
	    }

	    if ($plan && ($plan->send_first_reminder != 0
                || $plan->send_subscription_end != 0
			    || ($plan->recurring_subscription && strpos($this->item->payment_method, 'os_offline') !== false && JPluginHelper::isEnabled('system', 'mpofflinerecurringinvoice')))
        )
	    {
	        echo $this->loadTemplate('reminder_emails_info', ['plan' => $plan]);
        }
        ?>
	</div>
<div class="clr"></div>
<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>"/>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_( 'form.token' ); ?>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('#adminForm').validationEngine('attach', {
				onValidationComplete: function(form, status){
					if (status == true) {
						form.on('submit', function(e) {
							e.preventDefault();
						});
						return true;
					}
					return false;
				}
			});
			<?php
			if ($stateType)
			{
			?>
				buildStateFields('state', 'country', '<?php echo $selectedState; ?>');
			<?php
			}
			?>
		});
	})(jQuery);
</script>
</form>
</div>