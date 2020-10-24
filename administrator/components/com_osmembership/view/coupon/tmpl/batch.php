<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ; 
JToolBarHelper::title(JText::_('OSM_BATCH_COUPONS_TITLE'));
JToolBarHelper::custom('coupon.batch', 'upload', 'upload', 'Generate Coupons', false);
JToolBarHelper::cancel('coupon.cancel');	
?>
<form action="index.php?option=com_osmembership&view=coupon" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
    <div class="control-group">
        <div class="control-label">
            <?php echo  JText::_('OSM_NUMBER_COUPONS'); ?>
        </div>
        <div class="controls">
            <input class="input-mini" type="text" name="number_coupon" id="number_coupon" size="15" maxlength="250" value="" />
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
            <?php echo  JText::_('OSM_DISCOUNT'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="discount" id="discount" size="10" maxlength="250" value="" />&nbsp;&nbsp;<?php echo $this->lists['coupon_type'] ; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo  JText::_('OSM_CHARACTERS_SET'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="characters_set" id="characters_set" size="15" maxlength="250" value="" />
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo  JText::_('OSM_PREFIX'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="prefix" id="prefix" size="15" maxlength="250" value="" />
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo  JText::_('OSM_COUPON_LENGTH'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="length" id="length" size="15" maxlength="250" value="" />
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('OSM_VALID_FROM_DATE'); ?>
        </div>
        <div class="controls">
            <?php echo JHtml::_('calendar', '', 'valid_from', 'valid_from', $this->datePickerFormat. ' %H:%M:%S') ; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('OSM_VALID_TO_DATE'); ?>
        </div>
        <div class="controls">
            <?php echo JHtml::_('calendar', '', 'valid_to', 'valid_to', $this->datePickerFormat. ' %H:%M:%S') ; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('OSM_TIMES'); ?>
        </div>
        <div class="controls">
            <input class="text_area" type="text" name="times" id="times" size="5" maxlength="250" value="<?php echo $this->item->times;?>" />
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
    <div class="clr"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="used" value="<?php echo $this->item->used;?>" />
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>