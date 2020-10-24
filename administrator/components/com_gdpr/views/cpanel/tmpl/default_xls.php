<?php 
/** 
 * @package GDPR::LOGS::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage cpanel
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
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_NUM' ); ?></font>
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
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_REGISTERDATE'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_STATUS'); ?></font>
		</th>
		<?php if($this->showUserNotes):?>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_PRIVACYPOLICY_DESCRIPTION'); ?></font>
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
		<td align="center">
			<?php echo $i + 1; ?>
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
		<td>
			<?php echo JHtml::_('date', $row->registerDate, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
		</td>
		<td>
			<?php echo $row->profile_value ? JText::_('COM_GDPR_LOGS_ACCEPTED') : JText::_('COM_GDPR_LOGS_NOT_ACCEPTED'); ?>
		</td>
		<?php if($this->showUserNotes):?>
		<td>
			<?php echo $row->body ? $row->body : JText::_('COM_GDPR_LOGS_XLS_NA'); ?>
		</td>
		<?php endif;?>
	</tr>
	<?php
}
?>
</table>
</body>
</html>