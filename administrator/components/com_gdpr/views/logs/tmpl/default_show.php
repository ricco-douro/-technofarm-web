<?php 
/** 
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage views
 * @subpackage messages
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="accordion-group">
		<div class="accordion-heading opened">
			<div class="accordion-toggle noaccordion">
				<h4><span class="icon-pencil"></span><?php echo JText::_( 'COM_GDPR_LOG_DETAILS' ); ?></h4>
			</div>
		</div>
		<div id="details" class="accordion-body collapse in">
	      	<div class="accordion-inner">
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_CHANGE_DATE_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_DATE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo JHtml::_('date', $this->record->change_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_USER_ID_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_USER_ID' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->user_id;?>
							<a target="blank" href="index.php?option=com_users&task=user.edit&id=<?php echo $this->record->user_id;?>"> &nbsp;&nbsp;<span class="icon-out"></span></a>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_NAME_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_NAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->name;?>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_USERNAME_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_USERNAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->username;?>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_EMAIL_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_EMAIL' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->email;?>
						</td>
					</tr>
					<?php if($this->logUserIpaddress):?>
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_IPADDRESS_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_IPADDRESS' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->ipaddress;?>
						</td>
					</tr> 
					<?php endif;?>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_NAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_name == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_USERNAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_username == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_PASSWORD' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_password == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_EMAIL' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_email == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_PARAMS' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_params == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_REQUIRERESET' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_requirereset == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_BLOCK' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_block == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_SENDEMAIL' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_sendemail == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_USERGROUPS' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_usergroups == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CHANGE_ACTIVATION' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->change_activation == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_CREATED_USER' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->created_user == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_DELETED_USER' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->deleted_user == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-notice.png" width="16" height="16" border="0" alt="not deleted" />'; ?>
							</fieldset>
						</td>
					</tr>
					<?php if($this->revokablePrivacyPolicy):?>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_LOGS_PRIVACY_POLICY' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->record->privacy_policy == 1 ? '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-tick.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_CHANGED' ) . '" />' : '<img src="' . JUri::base(true) . '/components/com_gdpr/images/icon-16-publish_x.png" width="16" height="16" border="0" alt="' . JText::_('COM_GDPR_LOGS_SHOW_UNCHANGED' ) . '" />'; ?>
							</fieldset>
						</td>
					</tr>
					<?php endif;?>
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_EDITOR_USER_ID_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_EDITOR_USER_ID' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->editor_user_id;?>
							<a target="blank" href="index.php?option=com_users&task=user.edit&id=<?php echo $this->record->editor_user_id;?>"> &nbsp;&nbsp;<span class="icon-out"></span></a>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_EDITOR_NAME_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_EDITOR_NAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->editor_name;?>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_EDITOR_USERNAME_DESC' ); ?>" class="hasPopover">
								<?php echo JText::_('COM_GDPR_LOGS_EDITOR_USERNAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php echo $this->record->editor_username;?>
						</td>
					</tr> 
				</tbody>
				</table>
			</div>
		</div>
		
		
		<div class="accordion-heading opened">
			<div class="accordion-toggle noaccordion">
				<h4><span class="icon-pencil"></span><?php echo JText::_( 'COM_GDPR_LOGS_CHANGES_DETAILS' ); ?></h4>
			</div>
		</div>
		<div id="changes_details" class="accordion-body collapse in">
	      	<div class="accordion-inner">
				<table class="admintable">
				<tbody>
					<tr>
					<td class="key left_title">
						<label for="type" data-content="<?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW_DESC' ); ?>" class="hasPopover">
							<?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW' ); ?>:
						</label>
					</td>
					<td class="right_details">
						<?php 
							if(is_array($this->record->changes_structure['changes']) && count($this->record->changes_structure['changes'])):?>
							
								<table class="adminlist table table-striped table-hover table-showdetails">
									<thead>
										<tr>
											<th>
												<?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW_FIELD_CHANGE' ); ?>
											</th>
											<th>
												<?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW_OLDVALUE' ); ?>
											</th>
											<th>
												<?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS_ROW_NEWVALUE' ); ?>
											</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($this->record->changes_structure['changes'] as $changeType=>$changeValues):?>
											<tr>
												<td>
													<label class="label label-info"><?php echo JText::_('COM_GDPR_LOGS_' . strtoupper($changeType));?></label>
												</td>
												<?php 
													$cellValue = null;
													$cellClass = null;
													switch($changeValues['oldvalue']){
														case '1':
															if($changeType == 'change_block') {
																$cellValue = JText::_('COM_GDPR_LOGS_BLOCKED_USER');
																$cellClass = "class='table-cell-error'";
															} else {
																$cellValue = JText::_('JYES');
																$cellClass = "class='table-cell-success'";
															}
															
															break;
															
														case '0':
															if($changeType == 'change_block') {
																$cellValue = JText::_('COM_GDPR_LOGS_ENABLED_USER');
																$cellClass = "class='table-cell-success'";
															} else {
																$cellValue = JText::_('JNO');
																$cellClass = "class='table-cell-error'";
															}
															break;
															
														default:
															if(is_array($changeValues['oldvalue'])) {
																if($changeType == 'change_params') {
																	$cellValue = implode(', ', array_map(
																		function ($v, $k) { 
																			$cycledFieldNameTranslation = JText::_('COM_GDPR_LOGS_' . strtoupper($k) . '_PROFILE');
																			if(strpos($cycledFieldNameTranslation, 'COM_GDPR_') !== false) {
																				$cycledFieldNameTranslation = $k;
																			}
																			if(is_array($v)) {
																				$v = implode (', ', $v);
																			}
																			if($v == '1') {
																				$v = JText::_('JYES');
																			}
																			if($v == '0') {
																				$v = JText::_('JNO');
																			}
																			return sprintf("%s='%s'", $cycledFieldNameTranslation, $v); 
																		},
																		$changeValues['oldvalue'],
																		array_keys($changeValues['oldvalue'])
																	));
																} else {
																	$cellValue = implode(',', $changeValues['oldvalue']);
																}
															} else {
																$cellValue = $changeValues['oldvalue'];
															}
															$cellClass = "class='table-cell-info'";
													}
												?>
												<td <?php echo $cellClass;?>>
													<?php echo $cellValue;?>
													<div class="changearrow"></div>
												</td>
												<?php
													$cellValue = null;
													$cellClass = null;
													switch($changeValues['newvalue']){
														case '1':
															if($changeType == 'change_block') {
																$cellValue = JText::_('COM_GDPR_LOGS_BLOCKED_USER');
																$cellClass = "class='table-cell-error'";
															} else {
																$cellValue = JText::_('JYES');
																$cellClass = "class='table-cell-success'";
															}
															break;
													
														case '0':
															if($changeType == 'change_block') {
																$cellValue = JText::_('COM_GDPR_LOGS_ENABLED_USER');
																$cellClass = "class='table-cell-success'";
															} else {
																$cellValue = JText::_('JNO');
																$cellClass = "class='table-cell-error'";
															}
															break;
													
														default:
															if(is_array($changeValues['newvalue'])) {
																if($changeType == 'change_params') {
																	$cellValue = implode(', ', array_map(
																		function ($v, $k) {
																			$cycledFieldNameTranslation = JText::_('COM_GDPR_LOGS_' . strtoupper($k) . '_PROFILE');
																			if(strpos($cycledFieldNameTranslation, 'COM_GDPR_') !== false) {
																				$cycledFieldNameTranslation = $k;
																			}
																			if(is_array($v)) {
																				$v = implode (', ', $v);
																			}
																			if($v == '1') {
																				$v = JText::_('JYES');
																			}
																			if($v == '0') {
																				$v = JText::_('JNO');
																			}
																			return sprintf("%s='%s'", $cycledFieldNameTranslation, $v);
																		},
																		$changeValues['newvalue'],
																		array_keys($changeValues['newvalue'])
																	));
																} else {
																	$cellValue = implode(',', $changeValues['newvalue']);
																}
															} else {
																$cellValue = $changeValues['newvalue'];
															}
															$cellClass = "class='table-cell-info'";
													}
												?>
												<td <?php echo $cellClass;?>>
													<?php echo $cellValue;?>
												</td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							<?php endif;?>
						</td>
					</tr> 
				</tbody>
				</table>
			</div>
		</div>		
	</div>		
	
	<div class="clr"></div>
 
	<input type="hidden" name="option" value="<?php echo $this->option;?>" /> 
	<input type="hidden" name="id" value="<?php echo $this->record->id; ?>" />
	<input type="hidden" name="task" value="" /> 
</form>