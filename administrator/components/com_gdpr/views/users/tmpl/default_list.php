<?php 
/** 
 * @package GDPR::USERS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage users
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="headerlist">
		<tr>
			<td class="left">
				<div>
					<div class="input-prepend">
						<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_FILTER_USER' ); ?>:</span>
						<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->searchword, ENT_COMPAT, 'UTF-8');?>" class="text_area"/>
					</div>
					<button class="btn btn-primary btn-mini" onclick="this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
				</div>
				<div class="clr vspacer"></div>
				<div class="input-prepend active">
					<span class="add-on"><span class="icon-calendar"></span> <?php echo JText::_('COM_GDPR_FILTER_BY_DATE_FROM' ); ?>:</span>
					<input type="text" name="fromperiod" id="fromPeriod" data-role="calendar" autocomplete="off" value="<?php echo $this->dates['start'];?>" class="text_area"/>
				</div>
				
				<div class="input-prepend active">
					<span class="add-on"><span class="icon-calendar"></span> <?php echo JText::_('COM_GDPR_FILTER_BY_DATE_TO' ); ?>:</span>
					<input type="text" name="toperiod" id="toPeriod" data-role="calendar" autocomplete="off" value="<?php echo $this->dates['to'];?>" class="text_area"/>
				</div>
				<button class="btn btn-primary btn-mini" onclick="document.adminForm.task.value='users.display';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('fromPeriod').value='';document.getElementById('toPeriod').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
			</td>
			<td class="right">
				<div class="input-prepend active hidden-phone">
					<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_STATE' ); ?></span>
					<?php
						echo $this->lists['violated_user'];
						echo $this->pagination->getLimitBox();
					?>
				</div>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover">
		<thead>
			<tr>
				<th width="1%" class="title">
					<?php echo JText::_('COM_GDPR_NUM' ); ?>
				</th>
				<th width="1%">
					<input type="checkbox" name="toggle" value=""  onclick="Joomla.checkAll(this)" />
				</th>
				<th width="2%" class="title hidden-tablet hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_JOOMLA_USERID', 'a.id', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>  
				<th width="10%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_USERNAME', 'a.username', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
				<th width="10%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_NAME', 'a.name', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
				<th width="10%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_EMAIL', 'a.email', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
				<th width="10%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_LOGS_REGISTERDATE', 'a.registerDate', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
				<th width="10%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_LOGS_LASTVISITDATE', 'a.lastvisitDate', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_VIOLATEDUSER', 'u.violated_user', @$this->orders['order_Dir'], @$this->orders['order'], 'users.display' ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="100%">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
				$row = $this->items[$i];
				$taskPublishing	= !isset($row->violated_user) || !$row->violated_user ? 'users.violatedEntity' : 'users.unviolatedEntity';
				$altPublishing 	= !isset($row->violated_user) || !$row->violated_user ? JText::_( 'COM_GDPR_MARK_AS_VIOLATED_PROFILE' ) : JText::_( 'COM_GDPR_UNMARK_AS_VIOLATED_PROFILE' );
				
				// Access check.
				$checked = null;
				// Access check.
				if($this->user->authorise('core.edit') && $this->user->authorise('core.edit.state', 'com_gdpr')) {
					$checked = JHtml::_('grid.id', $i, $row->id);
				} else {
					$checked = '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>';
				}
				
				if($this->user->authorise('core.edit.state', 'com_gdpr')) {
					$violatedUser = '<a href="index.php?option=com_gdpr&task=' . $taskPublishing . '&cid[]=' . $row->id . '">';
					$violatedUser .= !isset($row->violated_user) || $row->violated_user == 0 ? '<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0"/>' :
																							   '<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-deny.png" width="16" height="16" border="0"/>';
					$violatedUser .= '</a>';
				} else {
					$violatedUser = '<img alt="' . $altPublishing . '" src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0"/>';
				}
				?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						
						<td align="center">
							<?php echo $checked; ?>
						</td>
						
						<td class="title hidden-phone">
							<?php echo $row->id; ?>
						</td>
						
						<td>
							<span class="label label-info"><?php echo $row->username; ?></span>
						</td>
						
						<td class="title hidden-phone">
							<span class="label label-warning"><?php echo $row->name; ?></span>
						</td>
						
						<td class="title hidden-phone">
							<span class="label label-primary"><?php echo $row->email; ?></span>
						</td>
						
						<td>
							<?php echo $row->registerDate == $this->nullDate ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $row->registerDate, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
						</td>
						
						<td>
							<?php echo $row->lastvisitDate == $this->nullDate ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $row->lastvisitDate, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
						</td>
						<td>
							<?php echo $violatedUser;?>
						</td>
						
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="users.display" /> 
	<input type="hidden" name="boxchecked" value="0" /> 
	<input type="hidden" name="filter_order" value="<?php echo $this->orders['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orders['order_Dir']; ?>" /> 
</form>