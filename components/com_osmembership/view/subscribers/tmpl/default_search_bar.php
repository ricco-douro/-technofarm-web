<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

$pullLeftClass = $this->bootstrapHelper->getClassMapping('pull-left');
?>
<div class="filter-search btn-group <?php echo $pullLeftClass; ?>">
    <label for="filter_search" class="element-invisible sr-only"><?php echo JText::_('OSM_FILTER_SEARCH_SUBSCRIPTIONS_DESC');?></label>
    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->filter_search); ?>" class="hasTooltip input-medium" title="<?php echo JHtml::tooltipText('OSM_SEARCH_SUBSCRIPTIONS_DESC'); ?>" />
</div>
<div class="btn-group <?php echo $pullLeftClass; ?>">
    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
</div>
<div class="btn-group <?php echo $pullLeftClass; ?>  <?php echo $this->bootstrapHelper->getClassMapping('hidden-phone'); ?>">
	<?php
        echo $this->lists['plan_id'];
        echo $this->lists['subscription_type'];
        echo $this->lists['published'];

        foreach($this->filters as $filter)
        {
            echo $filter;
        }
	?>
</div>
