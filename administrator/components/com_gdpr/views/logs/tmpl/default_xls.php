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
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>
<body>
<table>
	<?php if($this->searchword):?>
		<tr>
			<td>
				<font size="2" color="#CE1300"><?php echo JText::_('COM_GDPR_FILTER_USER' ); ?>:</font>
			</td>
			<td>
				<?php echo $this->searchword;?></span>
			</td>
		</tr>
		<tr><td></td></tr>
	<?php endif;?>
	<?php if($this->search_editorword):?>
		<tr>
			<td color="#FFF">
				<font size="2" color="#CE1300"><?php echo JText::_('COM_GDPR_FILTER_EDITOR' ); ?>:</font>
			</td>
			<td>
				<?php echo $this->search_editorword;?></span>
			</td>
		</tr>
		<tr><td></td></tr>
	<?php endif;?>
	<?php if($this->dates['start']):?>
		<tr>
			<td color="#FFF">
				<font size="2" color="#CE1300"><?php echo JText::_('COM_GDPR_FILTER_BY_DATE_FROM' ); ?>:</font>
			</td>
			<td>
				<?php echo $this->dates['start'];?></span>
			</td>
		</tr>
		<tr><td></td></tr>
	<?php endif;?>
	<?php if($this->dates['to']):?>
		<tr>
			<td color="#FFF">
				<font size="2" color="#CE1300"><?php echo JText::_('COM_GDPR_FILTER_BY_DATE_TO' ); ?>:</font>
			</td>
			<td>
				<?php echo $this->dates['to'];?></span>
			</td>
		</tr>
		<tr><td></td></tr>
	<?php endif;?>
	<?php if($this->state):?>
		<tr>
			<td color="#FFF">
				<font size="2" color="#CE1300"><?php echo JText::_('COM_GDPR_STATE' ); ?>:</font>
			</td>
			<td>
				<?php echo JText::_('COM_GDPR_LOGS_' . strtoupper($this->state));?></span>
			</td>
		</tr>
		<tr><td></td></tr>
	<?php endif;?>
</table>
	
<table>
<thead>
	<tr>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_NUM' ); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_USER_ID'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_NAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_USERNAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_EMAIL'); ?></font>
		</th>
		<?php if($this->logUserIpaddress):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_IPADDRESS'); ?></font>
		</th>
		<?php endif;?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_NAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_USERNAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_PASSWORD'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_EMAIL'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_PARAMS'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_REQUIRERESET'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_BLOCK'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_SENDEMAIL'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_USERGROUPS'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_ACTIVATION'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CREATED_USER'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_DELETED_USER'); ?></font>
		</th>
		<?php if($this->revokablePrivacyPolicy):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_PRIVACY_POLICY'); ?></font>
		</th>
		<?php endif;?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_EDITOR_USER_ID'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_EDITOR_NAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_EDITOR_USERNAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGE_DATE'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_CHANGES_DETAILS' ); ?></font>
		</th>
	</tr>
</thead>
<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
	$row = $this->items[$i];
	?>
	<tr>
		<td align="center">
			<?php echo $i + 1; ?>
		</td>
		<td>
			<?php echo $row->user_id; ?>
		</td>
		<td>
			<?php echo $row->name; ?>
		</td>
		<td>
			<?php echo $row->username; ?>
		</td>
		<td>
			<?php echo $row->email; ?>
		</td>
		<?php if($this->logUserIpaddress):?>
		<td>
			<?php echo $row->ipaddress; ?>
		</td>
		<?php endif;?>
		<td>
			<?php echo $row->change_name ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_username ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_password ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_email ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_params ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_requirereset ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_block ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_sendemail ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_usergroups ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->change_activation ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->created_user ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<td>
			<?php echo $row->deleted_user ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php if($this->revokablePrivacyPolicy):?>
		<td>
			<?php echo $row->privacy_policy ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php endif;?>
		<td>
			<?php echo $row->editor_user_id; ?>
		</td>
		<td>
			<?php echo $row->editor_name; ?>
		</td>
		<td>
			<?php echo $row->editor_username; ?>
		</td>
		<td>
			<?php echo JHtml::_('date', $row->change_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
		</td>
		<td>
			<?php 
				$decodedChangesStructure = json_decode($row->changes_structure, true);
				$changesStructure = $decodedChangesStructure['changes'];
				if(is_array($changesStructure) && count($changesStructure)):?>
				<?php foreach ($changesStructure as $changeType=>$changeValues):?>
					(<span><?php echo JText::_('COM_GDPR_LOGS_' . strtoupper($changeType));?>: </span>
					<?php 
						$cellValue = null;
						switch($changeValues['oldvalue']){
							case '1':
								if($changeType == 'change_block') {
									$cellValue = JText::_('COM_GDPR_LOGS_BLOCKED_USER');
								} else {
									$cellValue = JText::_('JYES');
								}
								break;
								
							case '0':
								if($changeType == 'change_block') {
									$cellValue = JText::_('COM_GDPR_LOGS_ENABLED_USER');
								} else {
									$cellValue = JText::_('JNO');
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
										$cellValue = '[' . $cellValue . ']';
									} else {
										$cellValue = implode(',', $changeValues['oldvalue']);
									}
								} else {
									$cellValue = $changeValues['oldvalue'];
								}
						}
					?>
					<span><?php echo $cellValue;?></span>
					<span>=></span>
					<?php 
						$cellValue = null;
						switch($changeValues['newvalue']){
							case '1':
								if($changeType == 'change_block') {
									$cellValue = JText::_('COM_GDPR_LOGS_BLOCKED_USER');
								} else {
									$cellValue = JText::_('JYES');
								}
								break;
								
							case '0':
								if($changeType == 'change_block') {
									$cellValue = JText::_('COM_GDPR_LOGS_ENABLED_USER');
								} else {
									$cellValue = JText::_('JNO');
								}
								break;
								
							case '':
								if($changeType == 'change_activation') {
									$cellValue = $changeValues['newvalue'] ? $changeValues['newvalue'] : "''";
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
										$cellValue = '[' . $cellValue . ']';
									} else {
										$cellValue = implode(',', $changeValues['newvalue']);
									}
								} else {
									$cellValue = $changeValues['newvalue'];
								}
						}
					?>
					<span><?php echo $cellValue;?></span>)
				<?php endforeach;?>
			<?php endif;?>
		</td>
	</tr>
	<?php
}
?>
</table>
</body>
</html>
