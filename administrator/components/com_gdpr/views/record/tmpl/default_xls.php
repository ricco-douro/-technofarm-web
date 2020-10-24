<?php 
/** 
 * @package GDPR::RECORD::administrator::components::com_gdpr
 * @subpackage views
 * @subpackage links
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>

<!doctype html public "-//w3c//dtd html 3.2//en">

<html>
<head>
	
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
	<title><?php echo JText::_('COM_GDPR_RECORD_TITLE');?></title>
	<style>
		<!-- 
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"arial"; font-size:x-small }
		 -->
	</style>
	
</head>

<body text="#000000">
<table frame=void cellspacing=0 cols=22 rules=none border=0>
	<tbody>
		<tr>
			<td colspan=2 width=591 height=39 align=center bgcolor="#ff9900"><b><font face="times new roman" size=5 color="#ffffff"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER');?></font></b></td>
			<td colspan=2 width=900 align=center bgcolor="#CC66FF"><b><font face="times new roman" size=5 color="#ffffff"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE');?></font></b></td>
			<td colspan=2 width=561 align=center bgcolor="#009900"><b><font face="times new roman" size=5 color="#ffffff"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR');?></font></b></td>
			<td width=203 align=left><br></td>
			<td width=204 align=left><br></td>
			<td width=433 align=left><br></td>
			<td width=220 align=left><br></td>
			<td width=457 align=left><br></td>
			<td width=428 align=left><br></td>
			<td width=292 align=left><br></td>
			<td width=408 align=left><br></td>
			<td width=306 align=left><br></td>
			<td width=285 align=left><br></td>
			<td width=221 align=left><br></td>
			<td width=399 align=left><br></td>
			<td width=260 align=left><br></td>
			<td width=290 align=left><br></td>
			<td width=181 align=left><br></td>
			<td width=208 align=left><br></td>
			<td width=215 align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_COMPANY_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_company_name');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_COMPANY_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_company_name');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_COMPANY_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_company_name');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_PERSON_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_person_name');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_PERSON_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_person_name');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_PERSON_NAME');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_person_name');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_ADDRESS');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_address');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_ADDRESS');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_address');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_ADDRESS');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_address');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_VAT');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_vat');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_VAT');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_vat');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_VAT');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_vat');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_PHONE');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_phone');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_PHONE');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_phone');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_PHONE');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_phone');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_email');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_email');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_email');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td height=39 align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_DIGITAL_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_digital_email');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_CONTROLLER_REPRESENTATIVE_DIGITAL_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_controller_representative_digital_email');?></td>
			<td align=left bgcolor="#ccffff"><b><font face="times new roman" color="#000000"><?php echo JText::_('COM_GDPR_DATA_PROCESSOR_DIGITAL_EMAIL');?></font></b></td>
			<td align=left><?php echo $this->cParams->get('data_processor_digital_email');?></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
			<td align=left><br></td>
		</tr>
		<tr>
			<td colspan=17 height=38 align=center bgcolor="#3399ff"><b><font face="times new roman" size=5 color="#ffffff"><?php echo JText::_('COM_GDPR_PROCESSING_ACTIVITIES_DESCRIPTION_SECTION'); ?></font></b></td>
			<td colspan=5 align=center bgcolor="#9999ff"><b><font face="times new roman" size=5 color="#ffffff"><?php echo JText::_('COM_GDPR_DATA_PROTECTION_IMPACT_ASSESSMENT'); ?></font></b></td>
			</tr>
		<tr>
			<td height=67 align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_STRUCTURE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_TREATMENT_NAME'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_TREATMENT_REASON'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_SOFTWARE_MANAGEMENT'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_TARGET_USERS'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_CATEGORY'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_TYPE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_RECIPIENTS'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_RESPONSIBLE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_COUNTRY'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_TRANSFER_GUARANTEE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_DATA_STORAGE_TIME'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_SECURITY_MEASURE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_LEGAL_BASIS_ART6'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_LEGAL_BASIS_ART9'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_DATA_STORAGE'); ?></font></b></td>
			<td align=center bgcolor="#ccffff"><b><font face="times new roman" size=3><?php echo JText::_('COM_GDPR_RECORD_CODE_CERTIFICATION'); ?></font></b></td>
			<td align=center bgcolor="#e6e6ff"><b><font face="times new roman" size=3 color="#000000"><?php echo JText::_('COM_GDPR_RECORD_REQUIRED_ASSESSMENT'); ?></font></b></td>
			<td align=center bgcolor="#e6e6ff"><b><font face="times new roman" size=3 color="#000000"><?php echo JText::_('COM_GDPR_RECORD_RISK_ANALYSIS_EVENT'); ?></font></b></td>
			<td align=center bgcolor="#e6e6ff"><b><font face="times new roman" size=3 color="#000000"><?php echo JText::_('COM_GDPR_RECORD_RISK_LEVEL_PROBABILITY'); ?></font></b></td>
			<td align=center bgcolor="#e6e6ff"><b><font face="times new roman" size=3 color="#000000"><?php echo JText::_('COM_GDPR_RECORD_RISK_LEVEL_CONSEQUENCES' ); ?></font></b></td>
			<td align=center bgcolor="#e6e6ff"><b><font face="times new roman" size=3 color="#000000"><?php echo JText::_('COM_GDPR_RECORD_SECURITY_MEASURES' ); ?></font></b></td>
		</tr>

<?php
$k = 0;
for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
	$row = $this->items[$i];
	$row->fields = json_decode($row->fields, true);
	?>
		<tr>
			<td height=35 align=left><font face="times new roman"><?php echo @$row->fields['structure']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['treatment_name']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['treatment_reason']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['software_management']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['target_users']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['personal_data_category']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['personal_data_type']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['third_party_recipients']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['third_party_responsible']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['third_party_country']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['third_party_transfer_guarantee']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['data_storage_time']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['security_measure']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['legal_basis_art6']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['legal_basis_art9']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['data_storage']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['code_certification']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['required_assessment']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['risk_analysis_event']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['risk_level_probability']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['risk_level_consequences']; ?></font></td>
			<td align=left><font face="times new roman"><?php echo @$row->fields['security_measures']; ?></font></td>
		</tr>
	<?php
}
?>
	</tbody>
</table>
<!-- ************************************************************************** -->
</body>

</html>