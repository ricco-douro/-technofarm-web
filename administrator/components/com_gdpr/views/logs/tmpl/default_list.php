<?php 
/** 
 * @package GDPR::LOGS::administrator::components::com_gdpr
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
	<table class="full headerlist">
		<tr>
			<td class="left">
				<div>
					<div class="input-prepend">
						<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_FILTER_USER' ); ?>:</span>
						<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->searchword, ENT_COMPAT, 'UTF-8');?>" class="text_area"/>
					</div>
					
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search_editor').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
				</div>
				<div>
					<div class="input-prepend">
						<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_FILTER_EDITOR' ); ?>:</span>
						<input type="text" name="search_editor" id="search_editor" value="<?php echo htmlspecialchars($this->search_editorword, ENT_COMPAT, 'UTF-8');?>" class="text_area"/>
					</div>
					
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
					<button class="btn btn-primary btn-mini" onclick="document.getElementById('search_editor').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
				</div>
				<div class="clr vspacer"></div>
				<div class="input-prepend active">
					<span class="add-on"><span class="icon-calendar"></span> <?php echo JText::_('COM_GDPR_FILTER_BY_DATE_FROM' ); ?>:</span>
					<input type="text" name="fromperiod" id="fromPeriod" data-role="calendar" autocomplete="off"  value="<?php echo $this->dates['start'];?>" class="text_area"/>
				</div>
				
				<div class="input-prepend active">
					<span class="add-on"><span class="icon-calendar"></span> <?php echo JText::_('COM_GDPR_FILTER_BY_DATE_TO' ); ?>:</span>
					<input type="text" name="toperiod" id="toPeriod" data-role="calendar" autocomplete="off" value="<?php echo $this->dates['to'];?>" class="text_area"/>
				</div>
				<button class="btn btn-primary btn-mini" onclick="document.adminForm.task.value='logs.display';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('fromPeriod').value='';document.getElementById('toPeriod').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
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

	<table class="adminlist table table-striped table-hover">
	<thead>
		<tr>
			<th width="1%">
				<?php echo JText::_('COM_GDPR_NUM' ); ?>
			</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value=""  onclick="Joomla.checkAll(this)" />
			</th>
			<th width="6%">
				<?php echo JHtml::_('grid.sort',  'COM_GDPR_LOGS_NAME', 's.name', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th width="6%" class="hidden-phone">
				<?php echo JHtml::_('grid.sort',  'COM_GDPR_LOGS_USERNAME', 's.username', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th width="8%" class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',  'COM_GDPR_LOGS_EMAIL', 's.email', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<?php if($this->logUserIpaddress):?>
			<th width="8%" class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',  'COM_GDPR_LOGS_IPADDRESS', 's.ipaddress', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<?php endif;?>
			<th class="hidden-phone">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_NAME', 's.change_name', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_USERNAME', 's.change_username', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_PASSWORD', 's.change_password', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_EMAIL', 's.change_email', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_PARAMS', 's.change_params', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_REQUIRERESET', 's.change_requirereset', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_BLOCK', 's.change_block', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_SENDEMAIL', 's.change_sendemail', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_USERGROUPS', 's.change_usergroups', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_ACTIVATION', 's.change_activation', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CREATED_USER', 's.created_user', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_DELETED_USER', 's.deleted_user', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<?php if($this->revokablePrivacyPolicy):?>
			<th class="hidden-phone hidden-tablet">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_PRIVACY_POLICY', 's.privacy_policy', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<?php endif;?>
			<th>
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_EDITOR_NAME', 's.editor_name', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_EDITOR_USERNAME', 's.editor_username', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
			<th>
				<?php echo JHtml::_('grid.sort',   'COM_GDPR_LOGS_CHANGE_DATE', 's.change_date', @$this->orders['order_Dir'], @$this->orders['order'], 'logs.display' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
		$row = $this->items[$i];
		$link =  'index.php?option=com_gdpr&task=logs.showEntity&cid[]='. $row->id ;
		
		$checked = null;
		// Access check.
		if($this->user->authorise('core.edit')) {
			$checked = JHtml::_('grid.id', $i, $row->id);		
		} else {
			$checked = '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>';
		}
		
		$deleted = null;
		if($row->deleted_user) {
			$deleted = ' stroked';
		}
		?>
		<tr>
			<td align="center">
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td class="<?php echo $deleted;?>">
				<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_GDPR_VIEW_LOG_DETAILS' ); ?>">
					<?php echo $row->name; ?>
				</a>
			</td>
			<td class="hidden-phone<?php echo $deleted;?>">
				<?php echo $row->username; ?>
			</td>
			<td class="hidden-phone hidden-tablet<?php echo $deleted;?>">
				<?php echo $row->email; ?>
			</td>
			<?php if($this->logUserIpaddress):?>
			<td class="hidden-phone hidden-tablet<?php echo $deleted;?>">
				<?php echo $row->ipaddress; ?>
			</td>
			<?php endif;?>
			<td class="hidden-phone">
				<?php echo $row->change_name ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_NAME_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone">
				<?php echo $row->change_username ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_USERNAME_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone">
				<?php echo $row->change_password ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_PASSWORD_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_email ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_EMAIL_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_params ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_PARAMS_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_requirereset ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_REQUIRERESET_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_block ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_BLOCKED_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_sendemail ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_SENDEMAIL_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_usergroups ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_USERGROUPS_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->change_activation ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CHANGED_ACTIVATION_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->created_user ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_CREATED_USER_TICK') . '" />': '-'; ?>
			</td>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->deleted_user ? '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_DELETED_USER_TICK') . '" />': '-'; ?>
			</td>
			<?php if($this->revokablePrivacyPolicy):?>
			<td class="hidden-phone hidden-tablet">
				<?php echo $row->privacy_policy ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_USER_TICK') . '" />': '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_USER_TICK') . '" />'; ?>
			</td>
			<?php endif;?>
			<td>
				<span class="label label-info"><?php echo $row->editor_name; ?></span>
			</td>
			<td class="hidden-phone">
				<span class="label label-info"><?php echo $row->editor_username; ?></span>
			</td>
			<td>
				<?php echo JHtml::_('date', $row->change_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
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
	<input type="hidden" name="task" value="logs.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->orders['order'];?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->orders['order_Dir'];?>" />
</form>