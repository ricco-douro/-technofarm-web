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
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_LOGS_LASTVISITDATE'); ?></font>
		</th>
		<th>
			<font size="2" color="#0028D3"><?php echo JText::_('COM_GDPR_VIOLATEDUSER' ); ?></font>
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
			<?php echo $row->name; ?>
		</td>
		<td>
			<?php echo $row->username; ?>
		</td>
		<td>
			<?php echo $row->email; ?>
		</td>
		<td>
			<?php echo $row->registerDate == $this->nullDate ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $row->registerDate, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
		</td>
		<td>
			<?php echo $row->lastvisitDate == $this->nullDate ? JText::_('COM_GDPR_NEVER') : JHtml::_('date', $row->lastvisitDate, JText::_('COM_GDPR_DATE_FORMAT_FILTER_DATETIME')); ?>
		</td>
		<td>
			<?php echo $row->violated_user ? JText::_('JYES') : JText::_('JNO'); ?>
		</td>
	</tr>
	<?php
}
?>
</table>
</body>
</html>
