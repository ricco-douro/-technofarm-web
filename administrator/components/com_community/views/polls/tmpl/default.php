<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" language="javascript">
/**
 * This function needs to be here because, Joomla toolbar calls it
 **/
Joomla.submitbutton = function(action){
	submitbutton(action);
}

function submitbutton(action) {
    submitform(action);
}
</script>
<form action="index.php?option=com_community" method="get" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span24">
			<input type="text" onchange="document.adminForm.submit();" class="no-margin" value="<?php echo ($this->search) ? $this->escape($this->search) : ''; ?>" id="search" name="search" />
			<div class="btn btn-small btn-primary" onclick="document.adminForm.submit();">
				<i class="js-icon-search"></i>
				<?php echo JText::_('COM_COMMUNITY_SEARCH');?>
			</div>
			<div class="pull-right text-right">
				<?php echo $this->categories;?>
				<?php echo $this->_getStatusHTML();?>
			</div>
		</div>
	</div>
	<table class="table table-bordered table-hover">
		<thead>
			<tr class="title">
				<th width="10">#</th>
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					<span class="lbl"></span>
				</th>
				<th width="200">
					<?php echo JHTML::_('grid.sort', JText::_('COM_COMMUNITY_TITLE'), 'a.title', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('COM_COMMUNITY_CATEGORY'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', JText::_('COM_COMMUNITY_NOTIFICATIONGROUP_ADMIN'), 'c.name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="50">
					<?php echo JHTML::_('grid.sort', JText::_('COM_COMMUNITY_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JText::_('COM_COMMUNITY_VIEW');?>
				</th>
			</tr>
		</thead>
		<?php $i = 0; ?>
		<?php if (empty($this->polls)) { ?>
			<tr>
				<td colspan="7" align="center"><?php echo JText::_('COM_COMMUNITY_POLLS_NONE_CREATED');?></td>
			</tr>
		<?php } ?>

		<?php foreach ($this->polls as $row): ?>
			<tr>
				<td align="center">
					<?php echo ($i + 1); ?>
				</td>
				<td>
					<?php echo JHTML::_('grid.id', $i++, $row->id); ?>
					<span class="lbl"></span>
				</td>
				<td>
					<?php echo $row->title; ?>
				</td>
				<td>
					<?php echo $row->category; ?>
				</td>
				<td align="center">
					<?php echo ($row->username ? $row->username : ''); ?>
				</td>
				<td id="published<?php echo $row->id;?>" align="center" class='center'>
					<?php echo $this->getPublish($row, 'published', 'polls,ajaxTogglePublish');?>
				</td>
				<td>
					<a href="<?php echo JRoute::_(JUri::root().'index.php?option=com_community&view=polls'); ?>" target="_blank"><?php echo JText::_('COM_COMMUNITY_VIEW')?></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<div class="pull-left"><?php echo $this->pagination->getListFooter(); ?></div>
	<div class="pull-right"><?php echo $this->pagination->getLimitBox(); ?></div>
	<input type="hidden" name="view" value="polls" />
	<input type="hidden" name="option" value="com_community" />
	<input type="hidden" name="task" value="polls" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>