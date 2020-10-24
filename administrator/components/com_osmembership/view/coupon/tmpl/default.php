<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
JHtml::_('formbehavior.chosen', 'select');

if (!empty($this->subscriptions))
{
	JHtml::_('behavior.tabstate');
}
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform(pressbutton, form);
		} else {
			if (form.code.value == ""){
				alert("<?php echo JText::_("OSM_ENTER_COUPON"); ?>");
				form.code.focus();					
			} else if (form.discount.value == ""){
				alert("<?php echo JText::_("EN_ENTER_DISCOUNT_AMOUNT"); ?>");
				form.discount.focus();
			} else {
				Joomla.submitform(pressbutton, form);	
			}																								
		}								
	}	
		
</script>
<form action="index.php?option=com_osmembership&view=coupon" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<?php
		if (!empty($this->subscriptions))
		{
			echo JHtml::_('bootstrap.startTabSet', 'coupon', array('active' => 'coupon-page'));
			echo JHtml::_('bootstrap.addTab', 'coupon', 'coupon-page', JText::_('OSM_BASIC_INFORMATION', true));
		}
	?>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_CODE'); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="code" id="code" size="15" maxlength="250"
			       value="<?php echo $this->item->code; ?>"/>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_DISCOUNT'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="discount" id="discount" size="10" maxlength="250"
			       value="<?php echo $this->item->discount; ?>"/>&nbsp;&nbsp;<?php echo $this->lists['coupon_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_PLAN'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['plan_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo OSMembershipHelperHtml::getFieldLabel('apply_for', JText::_('OSM_APPLY_FOR'), JText::_('OSM_APPLY_FOR_EXPLAIN')) ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['apply_for']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_TIMES'); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="times" id="times" size="5" maxlength="250"
			       value="<?php echo $this->item->times; ?>"/>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_TIME_USED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->item->used; ?>
		</div>
	</div>
    <div class="control-group">
        <div class="control-label">
			<?php echo JText::_('OSM_MAX_USAGE_PER_USER'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="max_usage_per_user" id="max_usage_per_user" size="5" maxlength="250"
                   value="<?php echo $this->item->max_usage_per_user; ?>"/>
        </div>
    </div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_VALID_FROM_DATE'); ?>
		</div>
		<div class="controls">
			<?php echo JHtml::_('calendar', $this->item->valid_from, 'valid_from', 'valid_from', $this->datePickerFormat. ' %H:%M:%S'); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_VALID_TO_DATE'); ?>
		</div>
		<div class="controls">
			<?php echo JHtml::_('calendar', $this->item->valid_to, 'valid_to', 'valid_to', $this->datePickerFormat. ' %H:%M:%S'); ?>
		</div>
	</div>
    <div class="control-group">
        <label class="control-label">
			<?php echo  JText::_('OSM_USER'); ?>
        </label>
        <div class="controls">
            <?php // Note that 100 parameter is used to prevent on change trigger for the input ?>
	        <?php echo OSMembershipHelper::getUserInput($this->item->user_id, 100) ; ?>
        </div>
    </div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_ACCESS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['access']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>

	<?php
	if (!empty($this->subscriptions))
	{
		echo JHtml::_('bootstrap.endTab');
		echo JHtml::_('bootstrap.addTab', 'coupon', 'subscriptions-page', JText::_('OSM_COUPON_USAGE', true));
		echo $this->loadTemplate('subscriptions');
		echo JHtml::_('bootstrap.endTab');
		echo JHtml::_('bootstrap.endTabSet');
	}
	?>
	<div class="clearfix"></div>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="used" value="<?php echo $this->item->used; ?>"/>
    <input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>"/>
	<input type="hidden" name="task" value=""/>
</form>