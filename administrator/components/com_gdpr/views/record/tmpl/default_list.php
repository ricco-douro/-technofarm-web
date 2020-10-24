<?php 
/** 
 * @package GDPR::RECORD::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage links
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Ordering drag'n'drop management
if ($this->orders['order'] == 's.ordering') {
	$saveOrderingUrl = 'index.php?option=com_gdpr&task=record.saveOrder&format=json&ajax=1';
	JHtml::_('sortablelist.sortable', 'adminList', 'adminForm', strtolower($this->orders['order_Dir']), $saveOrderingUrl);
	$this->document->addScript ( JUri::root ( true ) . '/administrator/components/com_gdpr/js/sortablelist.js', 'text/javascript', true );
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="full headerlist">
		<tr>
			<td class="left">
			</td>
			<td class="right">
				<div class="input-prepend active hidden-phone">
					<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_STATE' ); ?></span>
					<?php
						echo $this->lists['state'];
						echo $this->pagination->getLimitBox();
					?>
				</div>
			</td>
		</tr>
	</table>

	<table id="adminList" class="adminlist table table-striped table-hover">
	<thead>
		<tr>
			<th width="1%">
				<?php echo JText::_('COM_GDPR_NUM' ); ?>
			</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value=""  onclick="Joomla.checkAll(this)" />
			</th>
			
			<th>
				<?php echo JText::_('COM_GDPR_RECORD_STRUCTURE' ); ?>
			</th>
			<th>
				<?php echo JText::_('COM_GDPR_RECORD_TREATMENT_NAME' ); ?>
			</th>
			<th>
				<?php echo JText::_('COM_GDPR_RECORD_TREATMENT_REASON' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JText::_('COM_GDPR_RECORD_TARGET_USERS' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_CATEGORY' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_TYPE' ); ?>
			</th>
			<th class="order hidden-tablet hidden-phone">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_ORDER', 's.ordering', @$this->orders['order_Dir'], @$this->orders['order'], 'record.display'); ?>
				<?php 
					if(isset($this->orders['order']) && $this->orders['order'] == 's.ordering'):
						echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'record.saveOrder'); 
					endif;
				 ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_PUBLISHED', 's.published', @$this->orders['order_Dir'], @$this->orders['order'], 'record.display' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$canCheckin = $this->user->authorise('core.manage', 'com_checkin');
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
		$row = $this->items[$i];
		$link =  'index.php?option=com_gdpr&task=record.editEntity&cid[]='. $row->id ;
		$fields = json_decode($row->fields);
		$taskPublishing	= !isset($row->published) || !$row->published ? 'record.publish' : 'record.unpublish';
		$altPublishing 	= !isset($row->published) || !$row->published ? JText::_( 'COM_GDPR_PUBLISH' ) : JText::_( 'COM_GDPR_UNPUBLISH' );
		
		$checked = null;
		// Access check.
		if($this->user->authorise('core.edit', 'com_gdpr')) {
			$checked = $row->checked_out && $row->checked_out != $this->user->id ?
			JHtml::_('jgrid.checkedout', $i, JFactory::getUser($row->checked_out)->name, $row->checked_out_time, 'record.', $canCheckin) . '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>' :
			JHtml::_('grid.id', $i, $row->id);
		} else {
			$checked = '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>';
		}
		
		if($this->user->authorise('core.edit.state', 'com_gdpr')) {
			$published = '<a href="index.php?option=com_gdpr&task=' . $taskPublishing . '&cid[]=' . $row->id . '">';
			$published .= !isset($row->published) || $row->published == 0 ? '<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0"/>' :
																			'<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0"/>';
			$published .= '</a>';
		} else {
			$published = '<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0"/>';
		}
		
		?>
		<tr>
			<td align="center">
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
		
			<td align="center">
				<?php
				if ( ($row->checked_out && ( $row->checked_out != $this->user->get ('id'))) || !$this->user->authorise('core.edit', 'com_gdpr') ) {
					echo $fields->structure;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_GDPR_EDIT_RECORD' ); ?>">
						<span class="icon-pencil"></span>
						<?php echo $fields->structure; ?>
					</a>
					<?php
				}
				?>
			</td>
			<td align="center">
				<span class="label label-info"><?php echo $fields->treatment_name; ?></span>
			</td>
			<td align="center">
				<span class="label label-warning"><?php echo $fields->treatment_reason; ?></span>
			</td>
			<td align="center" class="hidden-phone">
				<span class="label label-primary"><?php echo $fields->target_users; ?></span>
			</td>
			<td align="center" class="hidden-phone">
				<span class="label label-primary"><?php echo $fields->personal_data_category; ?></span>
			</td>
			<td align="center" class="hidden-phone">
				<span class="label label-primary"><?php echo $fields->personal_data_type; ?></span>
			</td>
			
			<td class="order hidden-tablet hidden-phone">
				<?php 
				$ordering = $this->orders['order'] == 's.ordering'; 
				$disabled = $ordering ?  '' : 'disabled="disabled"'; 
				
				$iconClass = '';
				if (!$this->user->authorise('core.edit', 'com_gdpr')) {
					$iconClass = ' inactive';
				}
				elseif (!$ordering) {
					$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
				}
				?>
				<div style="display:inline-block" class="sortable-handler<?php echo $iconClass ?>">
					<span class="icon-menu"></span>
				</div>
				
				<span class="moveup"><?php echo $this->pagination->orderUpIcon( $i, true, 'record.moveorder_up', 'COM_GDPR_MOVE_UP', $ordering); ?></span>
				<span class="movedown"><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'record.moveorder_down', 'COM_GDPR_MOVE_DOWN', $ordering); ?></span>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>"  <?php echo $disabled; ?>  class="ordering_input" style="text-align: center" />
			</td>
			
			<td>
				<?php echo $published;?>
			</td>
		</tr>
		<?php
	}
	?>
	<tfoot>
		<td colspan="100%">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="record.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->orders['order'];?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->orders['order_Dir'];?>" />
</form>