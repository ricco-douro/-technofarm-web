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
	<legend><?php echo JText::_('OSM_PLAN_DETAIL');?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_TITLE'); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $this->item->title;?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_ALIAS'); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="alias" id="alias" size="40" maxlength="250" value="<?php echo $this->item->alias;?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_CATEGORY'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['category_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_PRICE'); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="number" name="price" id="price" size="10" maxlength="250" value="<?php echo $this->item->price;?>" step="0.01" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_SUBSCRIPTION_LENGTH'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="number" name="subscription_length" id="subscription_length" size="10" maxlength="250" value="<?php echo $this->item->subscription_length;?>" /><?php echo $this->lists['subscription_length_unit']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_EXPIRED_DATE'); ?>
		</div>
		<div class="controls">
			<?php echo JHtml::_('calendar', $this->item->expired_date, 'expired_date', 'expired_date', $this->datePickerFormat) ; ?>
		</div>
	</div>
	<?php
	if ($this->item->expired_date && $this->item->expired_date != $this->nullDate)
	{
	?>
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('OSM_PRORATED_SIGNUP_COST');?>
			</div>
			<div class="controls">
				<?php echo $this->lists['prorated_signup_cost'];?>
			</div>
		</div>
	<?php
	}
	?>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_LIFETIME_MEMBERSHIP');?>
		</div>
		<div class="controls">
			<?php echo $this->lists['lifetime_membership'];?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_THUMB'); ?>
		</div>
		<div class="controls">
			<input type="file" class="inputbox" name="thumb_image" size="60" />
			<?php
			if ($this->item->thumb)
			{
			?>
				<img src="<?php echo JUri::root().'media/com_osmembership/'.$this->item->thumb; ?>" class="img_preview" />
				<input type="checkbox" name="del_thumb" value="1" /><?php echo JText::_('OSM_DELETE_CURRENT_THUMB'); ?>
			<?php
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('OSM_ENABLE_RENEWAL'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_renewal']; ?>
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
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_SHORT_DESCRIPTION'); ?>
		</div>
		<div class="controls">
			<?php echo $editor->display( 'short_description',  $this->item->short_description , '100%', '250', '75', '10' ) ; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('OSM_DESCRIPTION'); ?>
		</div>
		<div class="controls">
			<?php echo $editor->display( 'description',  $this->item->description , '100%', '250', '75', '10' ) ; ?>
		</div>
	</div>
</fieldset>
