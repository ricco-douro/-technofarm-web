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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>
<body>
<table>
<thead>
	<tr>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_URL' ); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMID'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMNAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_USERID'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_SESSIONID'); ?></font>
		</th>
		<?php if($this->logUserIpaddress):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_IPADDRESS'); ?></font>
		</th>
		<?php endif;?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_NAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_USERNAME'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_EMAIL'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_CONSENTDATE'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_FORMFIELDS'); ?></font>
		</th>
	</tr>
</thead>
<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
	$row = $this->items[$i];
	?>
	<tr>
		<td>
			<?php echo $row->url != '*' ? $row->url : JText::_('COM_GDPR_CONSENTS_REGISTRY_URL_ALL_PAGES'); ?>
		</td>
		<td>
			<?php echo $row->formid; ?>
		</td>
		<td>
			<?php echo $row->formname; ?>
		</td>
		<td>
			<?php echo (int)$row->user_id ? $row->user_id : ' '; ?>
		</td>
		<td>
			<?php echo $row->session_id; ?>
		</td>
		<?php if($this->logUserIpaddress):?>
		<td>
			<?php echo $row->ipaddress; ?>
		</td>
		<?php endif;?>
		<td>
			<?php echo $row->name; ?>
		</td>
		<td>
			<?php echo $row->username; ?>
		</td>
		<td>
			<?php echo $row->email; ?>
		</td>
		<td>
			<?php echo JHtml::_('date', $row->consent_date, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
		</td>
		
		<td>
			<?php $formFields = json_decode($row->formfields, true);
				if(is_array($formFields) && count($formFields)):?>
					<?php foreach ($formFields as $formFieldName=>$formFieldValue):?>
						(<span><?php echo ucfirst($formFieldName);?>: </span>
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
							<span><?php echo $cellValue;?></span>)
						<?php
					endforeach;
				endif;
			?>
		</td>
	</tr>
	<?php
}
?>
</table>
</body>
</html>