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
?>
<form action="index.php?option=com_osmembership&view=countries" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('OSM_FILTER_SEARCH_COUNTRIES_DESC');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->filter_search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('OSM_SEARCH_COUNTRIES_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php
					echo $this->lists['filter_state'];
					echo $this->pagination->getLimitBox();
				?>
			</div>
		</div>
		<div class="clearfix"></div>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUNTRY_NAME'), 'tbl.name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>											
					<th class="center title" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUNTRY_CODE_3'), 'tbl.country_3_code', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>			
					<th class="center title" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUNTRY_CODE_2'), 'tbl.country_2_code', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_PUBLISHED'), 'tbl.published', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_ID'), 'tbl.id', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>													
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row       = $this->items[$i];
				$link      = JRoute::_('index.php?option=com_osmembership&view=country&cid[]=' . $row->id);
				$checked   = JHtml::_('grid.id', $i, $row->id);
				$published = JHtml::_('jgrid.published', $row->published, $i, 'country.');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>	
					<td>
						<a href="<?php echo $link; ?>">
							<?php echo $row->name; ?>
						</a>
					</td>									
					<td class="center">
						<?php echo $row->country_3_code; ?>
					</td>	
					<td class="center">
						<?php echo $row->country_2_code; ?>
					</td>											
					<td class="center">
						<?php echo $published; ?>
					</td>
					<td class="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->filter_order_Dir; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>