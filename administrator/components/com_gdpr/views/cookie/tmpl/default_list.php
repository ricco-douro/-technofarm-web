<?php 
/** 
 * @package GDPR::COOKIE::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage cookie
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
						<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_FILTER_RECORD' ); ?>:</span>
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
				<button class="btn btn-primary btn-mini" onclick="document.adminForm.task.value='cookie.display';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('fromPeriod').value='';document.getElementById('toPeriod').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
			</td>
			<td class="right">
				<div class="input-prepend active hidden-phone">
					<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_STATE' ); ?></span>
					<?php
						echo $this->lists['cookie_consent_type'];
						echo $this->lists['cookie_consent_user'];
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
				<th width="5%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_USERID', 'a.user_id', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<th width="5%" class="title hidden-tablet hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_SESSIONID', 'a.session_id', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php if($this->logUserIpaddress):?>
				<th width="8%" class="title hidden-tablet hidden-phone">
					<?php echo JHtml::_('grid.sort',  'COM_GDPR_CONSENTS_REGISTRY_IPADDRESS', 'a.ipaddress', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php endif;?>
				<th width="7%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_NAME', 'u.name', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<th width="7%" class="title hidden-tablet hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_USERNAME', 'u.username', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<th width="8%" class="title hidden-tablet hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_EMAIL', 'u.email', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<th width="6%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CONSENTDATE', 'a.consent_date', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_GENERIC', 'a.generic', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php if($this->isCategory1Enabled):?>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CATEGORY1', 'a.category1', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php endif;?>
				<?php if($this->isCategory2Enabled):?>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CATEGORY2', 'a.category2', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php endif;?>
				<?php if($this->isCategory3Enabled):?>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CATEGORY3', 'a.category3', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php endif;?>
				<?php if($this->isCategory4Enabled):?>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CATEGORY4', 'a.category4', @$this->orders['order_Dir'], @$this->orders['order'], 'cookie.display' ); ?>
				</th>
				<?php endif;?>
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
				
				$checked = null;
				// Access check.
				if($this->user->authorise('core.edit')) {
					$checked = JHtml::_('grid.id', $i, $row->id);
				} else {
					$checked = '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>';
				}
				
				// Expired consents highlighting
				$deleted = null;
				$consentLabelClass = "label-success";
				if(strtotime($row->consent_date) < strtotime("-1 year", time())) {
					$consentLabelClass = 'label-important';
					$deleted = ' stroked';
				}
				
				?>
					<tr>
						<td class="title">
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td align="center">
							<?php echo $checked; ?>
						</td>
						<td class="title">
							<?php echo (int)$row->user_id ? $row->user_id : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-tablet hidden-phone<?php echo $deleted;?>">
							<?php echo $row->session_id; ?>
						</td>
						<?php if($this->logUserIpaddress):?>
						<td class="title hidden-tablet hidden-phone<?php echo $deleted;?>">
							<?php echo $row->ipaddress; ?>
						</td>
						<?php endif;?>
						<td class="title hidden-phone<?php echo $deleted;?>">
							<?php echo $row->name ? $row->name : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-tablet hidden-phone<?php echo $deleted;?>">
							<?php echo $row->username ? $row->username : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-tablet hidden-phone<?php echo $deleted;?>">
							<?php echo $row->email ? $row->email : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title">
							<span class="label <?php echo $consentLabelClass;?>"><?php echo JHtml::_('date', $row->consent_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?></span>
						</td>
						<td class="title hidden-phone">
							<?php echo $row->generic ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_ACCEPTED') . '" />' : '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_NOACCEPTED') . '" />'; ?>
						</td>
						<?php if($this->isCategory1Enabled):?>
						<td class="title hidden-phone">
							<?php echo $row->category1 ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_ACCEPTED') . '" />' : '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_NOACCEPTED') . '" />'; ?>
						</td>
						<?php endif;?>
						<?php if($this->isCategory2Enabled):?>
						<td class="title hidden-phone">
							<?php echo $row->category2 ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_ACCEPTED') . '" />' : '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_NOACCEPTED') . '" />'; ?>
						</td>
						<?php endif;?>
						<?php if($this->isCategory3Enabled):?>
						<td class="title hidden-phone">
							<?php echo $row->category3 ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_ACCEPTED') . '" />' : '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_NOACCEPTED') . '" />'; ?>
						</td>
						<?php endif;?>
						<?php if($this->isCategory4Enabled):?>
						<td class="title hidden-phone">
							<?php echo $row->category4 ? '<img src="components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_ACCEPTED') . '" />' : '<img src="components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_CONSENTS_REGISTRY_NOACCEPTED') . '" />'; ?>
						</td>
						<?php endif;?>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="cookie.display" />
	<input type="hidden" name="boxchecked" value="0" /> 
	<input type="hidden" name="filter_order" value="<?php echo $this->orders['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orders['order_Dir']; ?>" /> 
</form>