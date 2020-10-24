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
    <legend class="adminform"><?php echo JText::_('OSM_META_DATA'); ?></legend>
    <div class="control-group">
        <label class="control-label">
			<?php echo JText::_('OSM_PAGE_TITLE'); ?>
        </label>
        <div class="controls">
            <input class="input-large" type="text" name="page_title" id="page_title" size="" maxlength="250"
                   value="<?php echo $this->item->page_title; ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
			<?php echo JText::_('OSM_PAGE_HEADING'); ?>
        </label>
        <div class="controls">
            <input class="input-large" type="text" name="page_heading" id="page_heading" size="" maxlength="250"
                   value="<?php echo $this->item->page_heading; ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
			<?php echo JText::_('OSM_META_KEYWORDS'); ?>
        </label>
        <div class="controls">
						<textarea rows="5" cols="30" class="input-lage"
                                  name="meta_keywords"><?php echo $this->item->meta_keywords; ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
			<?php echo JText::_('OSM_META_DESCRIPTION'); ?>
        </label>
        <div class="controls">
						<textarea rows="5" cols="30" class="input-lage"
                                  name="meta_description"><?php echo $this->item->meta_description; ?></textarea>
        </div>
    </div>
</fieldset>
