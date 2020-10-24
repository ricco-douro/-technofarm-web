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
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_GENERIC'); ?></font>
		</th>
		<?php if($this->isCategory1Enabled):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_CATEGORY1'); ?></font>
		</th>
		<?php endif;?>
		<?php if($this->isCategory2Enabled):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_CATEGORY2'); ?></font>
		</th>
		<?php endif;?>
		<?php if($this->isCategory3Enabled):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_CATEGORY3'); ?></font>
		</th>
		<?php endif;?>
		<?php if($this->isCategory4Enabled):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_CONSENTS_REGISTRY_CATEGORY4'); ?></font>
		</th>
		<?php endif;?>
	</tr>
</thead>
<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
	$row = $this->items[$i];
	?>
	<tr>
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
			<?php echo $row->generic ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php if($this->isCategory1Enabled):?>
		<td>
			<?php echo $row->category1 ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php endif;?>
		<?php if($this->isCategory2Enabled):?>
		<td>
			<?php echo $row->category2 ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php endif;?>
		<?php if($this->isCategory3Enabled):?>
		<td>
			<?php echo $row->category3 ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php endif;?>
		<?php if($this->isCategory4Enabled):?>
		<td>
			<?php echo $row->category4 ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
		<?php endif;?>
	</tr>
	<?php
}
?>
</table>
</body>
</html>