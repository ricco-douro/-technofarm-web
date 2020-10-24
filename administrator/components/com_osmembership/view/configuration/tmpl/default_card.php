<?php
/**
 * @package            Joomla
 * @subpackage         Event Booking
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2018 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die ;
?>
<div class="control-group">
	<div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('activate_member_card_feature', JText::_('OSM_ACTIVATE_MEMBER_CARD_FEATURE'), JText::_('OSM_ACTIVATE_MEMBER_CARD_FEATURE_EXPLAIN')); ?>
	</div>
	<div class="controls">
		<?php echo OSMembershipHelperHtml::getBooleanInput('activate_member_card_feature', $config->activate_member_card_feature); ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_page_orientation', JText::_('OSM_PAGE_ORIENTATION')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['card_page_orientation']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_page_format', JText::_('OSM_PAGE_FORMAT')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['card_page_format']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_bg_image', JText::_('OSM_CARD_BG_IMAGE'), JText::_('OSM_CARD_BG_IMAGE_EXPLAIN')); ?>
	</div>
	<div class="controls">
		<?php echo OSMembershipHelperHtml::getMediaInput($config->get('card_bg_image'), 'card_bg_image'); ?>
	</div>
</div>
<div class="control-group">
    <div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_bg_left', JText::_('OSM_CARD_BG_POSSITION')); ?>
    </div>
    <div class="controls">
		<?php echo JText::_('OSM_LEFT') . '    ';?><input type="text" name="card_bg_left" class="input-mini" value="<?php echo (int) $config->card_bg_left; ?>" />
		<?php echo JText::_('OSM_TOP') . '    ';?><input type="text" name="card_bg_top" class="input-mini" value="<?php echo (int) $config->card_bg_top; ?>" />
    </div>
</div>
<div class="control-group">
    <div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_bg_width', JText::_('OSM_BG_SIZE')); ?>
    </div>
    <div class="controls">
		<?php echo JText::_('OSM_WIDTH') . '    ';?><input type="text" name="card_bg_width" class="input-mini" value="<?php echo (int) $config->get('card_bg_width'); ?>" />
		<?php echo JText::_('OSM_HEIGHT') . '    ';?><input type="text" name="card_bg_height" class="input-mini" value="<?php echo (int) $config->get('card_bg_height'); ?>" />
    </div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo OSMembershipHelperHtml::getFieldLabel('card_layout', JText::_('OSM_CARD_LAYOUT')); ?>
	</div>
	<div class="controls">
		<?php echo $editor->display( 'card_layout',  $config->card_layout , '100%', '550', '75', '8' ) ;?>
	</div>
</div>