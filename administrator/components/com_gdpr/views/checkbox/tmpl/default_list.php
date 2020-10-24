<?php 
/** 
 * @package GDPR::CHECKBOX::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage links
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="headerlist">
		<tr>
			<td class="left">
				<div>
					<div class="input-prepend">
						<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_FILTER_CHECKBOX' ); ?>:</span>
						<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->searchword, ENT_COMPAT, 'UTF-8');?>" class="text_area"/>
					</div>
					<button class="btn btn-primary btn-mini" onclick="this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
				</div>
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
			<th width="15%">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_CHECKBOX_NAME', 's.name', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th width="15%">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_CHECKBOX_PLACEHOLDER', 's.placeholder', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_CHECKBOX_DESCRIPTION', 's.descriptionhtml', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_CHECKBOX_FORMSELECTOR', 's.formselector', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_PUBLISHED', 's.published', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_ACCESS', 's.access', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_GDPR_ID', 's.id', @$this->orders['order_Dir'], @$this->orders['order'], 'checkbox.display' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$canCheckin = $this->user->authorise('core.manage', 'com_checkin');
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
		$row = $this->items[$i];
		$link =  'index.php?option=com_gdpr&task=checkbox.editEntity&cid[]='. $row->id ;
		$taskPublishing	= !isset($row->published) || !$row->published ? 'checkbox.publish' : 'checkbox.unpublish';
		$altPublishing 	= !isset($row->published) || !$row->published ? JText::_( 'COM_GDPR_PUBLISH' ) : JText::_( 'COM_GDPR_UNPUBLISH' );
		
		$checked = null;
		// Access check.
		if($this->user->authorise('core.edit', 'com_gdpr')) {
			$checked = $row->checked_out && $row->checked_out != $this->user->id ?
			JHtml::_('jgrid.checkedout', $i, JFactory::getUser($row->checked_out)->name, $row->checked_out_time, 'checkbox.', $canCheckin) . '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>' :
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
					echo $row->name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_GDPR_EDIT_RECORD' ); ?>">
						<span class="icon-pencil"></span>
						<?php echo $row->name; ?>
					</a>
					<?php
				}
				?>
			</td>
			<td align="center">
				<span class="label label-info"><?php echo $row->placeholder; ?></span>
			</td>
			<td align="center" class="hidden-phone">
				<?php echo strip_tags($row->descriptionhtml); ?>
			</td>
			<td align="center" class="hidden-phone hidden-tablet">
				<span class="label label-primary"><?php echo $row->formselector; ?></span>
			</td>
			<td>
				<?php echo $published;?>
			</td>
			<td>
				<span class="label label-warning"><?php echo $row->accesslevel;?></span>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
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
	<input type="hidden" name="task" value="checkbox.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->orders['order'];?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->orders['order_Dir'];?>" />
</form>