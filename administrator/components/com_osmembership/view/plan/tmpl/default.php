<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
JHtml::_('behavior.tooltip');
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));
$translatable = JLanguageMultilang::isEnabled() && count($this->languages);

$bootstrapHelper = OSMembershipHelperHtml::getAdminBootstrapHelper();
$rowFluid        = $bootstrapHelper->getClassMapping('row-fluid');
$span8           = $bootstrapHelper->getClassMapping('span7');
$span4           = $bootstrapHelper->getClassMapping('span5');

JHtml::_('formbehavior.chosen', '#basic-information-page select, .advSelect');

JHtml::_('behavior.tabstate');
JHtml::_('jquery.framework');
JHtml::_('script', 'jui/cms.js', false, true);
?>
<form action="index.php?option=com_osmembership&view=plan" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'plan', array('active' => 'basic-information-page'));
		echo JHtml::_('bootstrap.addTab', 'plan', 'basic-information-page', JText::_('OSM_BASIC_INFORMATION', true));
	?>
		<div class="<?php echo $rowFluid; ?> clearfix">
			<div class="<?php echo $span8; ?> pull-left">
				<?php echo $this->loadTemplate('general', array('editor' => $editor)); ?>
			</div>
			<div class="<?php echo $span4; ?> pull-left" style="display: inline;">
				<?php
                    echo $this->loadTemplate('recurring_settings');
				    echo $this->loadTemplate('reminders_settings');
				    echo $this->loadTemplate('advanced_settings');
				    echo $this->loadTemplate('metadata');
				?>
			</div>
		</div>
	<?php
		echo JHtml::_('bootstrap.endTab');

        echo JHtml::_('bootstrap.addTab', 'plan', 'renew-options-page', JText::_('OSM_RENEW_OPTIONS', true));
        ?>
            <div class="<?php echo $rowFluid; ?> clearfix">
                <?php
                    echo $this->loadTemplate('renew_options');
                    echo $this->loadTemplate('renewal_discounts');
                ?>
            </div>
        <?php
        echo JHtml::_('bootstrap.endTab');

        echo JHtml::_('bootstrap.addTab', 'plan', 'upgrade-options-page', JText::_('OSM_UPGRADE_OPTIONS', true));
        echo $this->loadTemplate('upgrade_options');
        echo JHtml::_('bootstrap.endTab');

        if ($this->config->activate_member_card_feature)
        {
	        echo JHtml::_('bootstrap.addTab', 'plan', 'member-card-page', JText::_('OSM_MEMBER_CARD_SETTINGS', true));
	        echo $this->loadTemplate('member_card', array('editor' => $editor));
	        echo JHtml::_('bootstrap.endTab');
        }

		echo JHtml::_('bootstrap.addTab', 'plan', 'messages-page', JText::_('OSM_MESSAGES', true));
		echo $this->loadTemplate('messages', array('editor' => $editor));
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'plan', 'reminder-messages-page', JText::_('OSM_REMINDER_MESSAGES', true));
		echo $this->loadTemplate('reminder_messages', array('editor' => $editor));
		echo JHtml::_('bootstrap.endTab');

		if ($translatable)
		{
			echo JHtml::_('bootstrap.addTab', 'plan', 'translation-page', JText::_('OSM_TRANSLATION', true));
			echo $this->loadTemplate('translation', array('editor' => $editor));
			echo JHtml::_('bootstrap.endTab');
		}

		if (count($this->plugins))
		{
			$count = 0 ;

			foreach ($this->plugins as $plugin)
			{
				$count++ ;
				echo JHtml::_('bootstrap.addTab', 'plan', 'tab_'.$count, JText::_($plugin['title'], true));
				echo $plugin['form'];
				echo JHtml::_('bootstrap.endTab');
			}
		}

        // Add support for custom settings layout
        if (file_exists(__DIR__ . '/default_custom_settings.php'))
        {
            echo JHtml::_('bootstrap.addTab', 'plan', 'custom-settings-page', JText::_('OSM_CUSTOM_SETTINGS', true));
	        echo $this->loadTemplate('custom_settings', array('editor' => $editor));
            echo JHtml::_('bootstrap.endTab');
        }

	    echo JHtml::_('bootstrap.endTabSet');
	?>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
    <input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>"/>
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="recurring" name="recurring" value="<?php echo (int)$this->item->recurring_subscription;?>" />
	<script type="text/javascript">

		Joomla.submitbutton = function(pressbutton)
		{
			var form = document.adminForm;

			if (pressbutton == 'cancel')
			{
				Joomla.submitform(pressbutton, form);
			}
			else
			{
				//Validate the entered data before submitting
				if (form.title.value == '') {
					alert("<?php echo JText::_('OSM_ENTER_PLAN_TITLE', true); ?>");
					form.title.focus();
					return ;
				}

				var lifetimeMembership = jQuery('input[name=\'lifetime_membership\']:checked').val();

				if (!form.subscription_length.value  && lifetimeMembership == 0) {
					alert("<?php echo JText::_('OSM_ENTER_SUBSCRIPTION_LENGTH', true); ?>");
					form.subscription_length.focus();
					return ;
				}
				var recurringSubscription = jQuery('input[name=\'recurring_subscription\']:checked').val();

				if (recurringSubscription == 1 && form.price.value <= 0) {
					alert("<?php echo JText::_('OSM_PRICE_REQUIRED', true); ?>");
					form.price.focus();
					return ;
				}

				if(jQuery('.article_checkbox').length)
				{
					jQuery('.article_checkbox').attr("checked", false);
				}

				if(jQuery('.k2_item_checkbox').length)
				{
					jQuery('.k2_item_checkbox').attr("checked", false);
				}

				Joomla.submitform(pressbutton, form);
			}
		};

		(function($){
			$(document).ready(function(){
				$('.osm-waring').hide();

				if($('#recurring').val() == 1 && $('#price').val() <= 0)
                {
                    $('.osm-waring').slideDown();
                }
			})
		})(jQuery);
	</script>
</form>