<?php 
/** 
 * @package GDPR::CONSENTS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage consents
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
				<button class="btn btn-primary btn-mini" onclick="document.adminForm.task.value='consents.display';this.form.submit();"><?php echo JText::_('COM_GDPR_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('fromPeriod').value='';document.getElementById('toPeriod').value='';this.form.submit();"><?php echo JText::_('COM_GDPR_RESET' ); ?></button>
			</td>
			<td class="right">
				<div class="input-prepend active hidden-phone">
					<span class="add-on"><span class="icon-filter"></span> <?php echo JText::_('COM_GDPR_STATE' ); ?></span>
					<?php
						echo $this->lists['registered_user'];
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
				<th width="10%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_URL', 'a.url', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>  
				<th width="8%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_FORMID', 'a.formid', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_FORMNAME', 'a.formname', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="5%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_USERID', 'a.user_id', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="5%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_SESSIONID', 'a.session_id', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<?php if($this->logUserIpaddress):?>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort',  'COM_GDPR_CONSENTS_REGISTRY_IPADDRESS', 'a.ipaddress', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<?php endif;?>
				<th width="7%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_NAME', 'u.name', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="7%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_USERNAME', 'u.username', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="8%" class="title hidden-phone">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_EMAIL', 'u.email', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="6%" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_GDPR_CONSENTS_REGISTRY_CONSENTDATE', 'a.consent_date', @$this->orders['order_Dir'], @$this->orders['order'], 'consents.display' ); ?>
				</th>
				<th width="20%" class="title hidden-phone">
					<?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMFIELDS'); ?>
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
				
				$checked = null;
				// Access check.
				if($this->user->authorise('core.edit')) {
					$checked = JHtml::_('grid.id', $i, $row->id);
				} else {
					$checked = '<input type="checkbox" style="display:none" data-enabled="false" id="cb' . $i . '" name="cid[]" value="' . $row->id . '"/>';
				}
				?>
					<tr>
						<td class="title">
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td align="center">
							<?php echo $checked; ?>
						</td>
						<td class="title hidden-phone">
							<?php echo $row->url != '*' ? $row->url : JText::_('COM_GDPR_CONSENTS_REGISTRY_URL_ALL_PAGES'); ?>
						</td>
						<td class="title">
							<span class="label label-info"><?php echo $row->formid ? $row->formid : JText::_('COM_GDPR_LOGS_NA'); ?></span>
						</td>
						<td class="title hidden-phone">
							<span class="label label-warning"><?php echo $row->formname ? $row->formname : JText::_('COM_GDPR_LOGS_NA'); ?></span>
						</td>
						<td class="title">
							<?php echo (int)$row->user_id ? $row->user_id : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-phone">
							<?php echo $row->session_id; ?>
						</td>
						<?php if($this->logUserIpaddress):?>
						<td class="title hidden-phone">
							<?php echo $row->ipaddress; ?>
						</td>
						<?php endif;?>
						<td class="title hidden-phone">
							<?php echo $row->name ? $row->name : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-phone">
							<?php echo $row->username ? $row->username : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title hidden-phone">
							<?php echo $row->email ? $row->email : JText::_('COM_GDPR_LOGS_NA'); ?>
						</td>
						<td class="title">
							<span class="label label-primary"><?php echo JHtml::_('date', $row->consent_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?></span>
						</td>
						<td class="title hidden-phone">
							<?php $formFields = json_decode($row->formfields, true);
								if(is_array($formFields) && count($formFields)):?>
									<?php foreach ($formFields as $formFieldName=>$formFieldValue):?>
										( <span style="font-weight:bold"><?php echo ucfirst($formFieldName);?>: </span>
										<?php 
											$cellValue = null;
											switch($formFieldValue){
												case null:
												case '0':
													$cellValue = JText::_('COM_GDPR_LOGS_NA');
													break;
													
												default:
													$cellValue = $formFieldValue;
											}?>
											<span><?php echo $cellValue;?></span> )
										<?php
									endforeach;
								endif;
							?>
						</td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="consents.display" />
	<input type="hidden" name="boxchecked" value="0" /> 
	<input type="hidden" name="filter_order" value="<?php echo $this->orders['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orders['order_Dir']; ?>" /> 
</form>