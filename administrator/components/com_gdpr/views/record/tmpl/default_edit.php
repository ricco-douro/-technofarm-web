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
defined ( '_JEXEC' ) or die ( 'Restricted access' ); ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="accordion-group">
		<div class="accordion-heading opened">
			<div class="accordion-toggle noaccordion">
				<h4><span class="icon-pencil"></span><?php echo JText::_( 'COM_GDPR_PROCESSING_ACTIVITIES_DESCRIPTION_SECTION' ); ?></h4>
			</div>
		</div>
		<div id="details" class="accordion-body collapse in">
	      	<div class="accordion-inner">
				<table class="admintable">
				<tbody>
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_GDPR_PUBLISHED' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<fieldset class="radio btn-group">
								<?php echo $this->lists['published']; ?>
							</fieldset>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="structure">
								<?php echo JText::_('COM_GDPR_RECORD_STRUCTURE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="structure" name="fields[structure]" rows="5" cols="15" data-validation="required"><?php echo @$this->record->fields['structure'];?></textarea>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="treatment_name">
								<?php echo JText::_('COM_GDPR_RECORD_TREATMENT_NAME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="treatment_name" name="fields[treatment_name]" rows="5" cols="15" data-validation="required"><?php echo @$this->record->fields['treatment_name'];?></textarea>
						</td>
					</tr>
					
					<tr>
						<td class="key left_title">
							<label for="treatment_reason">
								<?php echo JText::_('COM_GDPR_RECORD_TREATMENT_REASON' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="treatment_reason" name="fields[treatment_reason]" list="treatment_reason_list" class="inputbox-large" value="<?php echo @$this->record->fields['treatment_reason'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="treatment_reason_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ACCESS_CONTROL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ACCIDENTS_INSURANCE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ACCOUNTING');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_BUSINESS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CLIENT_ANALYSIS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CREDIT_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CUSTOMER_CARE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CUSTOMER_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CUSTOMER_REGISTRY');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_DIRECT_MARKETING');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FRAUD_PREVENTION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_HEALTH_INSURANCE_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_HISTORICAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_INSURANCE_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_MARKET_RESEARCH');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_MARKETING');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PHYSICAL_SECURITY');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PUBLIC_RELATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_REGISTER_SUPPLIERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SCHEDULING');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SUPPLIER_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_STAFF_MANAGEMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_STATISTICAL');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="software_management">
								<?php echo JText::_('COM_GDPR_RECORD_SOFTWARE_MANAGEMENT' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="software_management" name="fields[software_management]" rows="5" cols="15"><?php echo @$this->record->fields['software_management'];?></textarea>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="target_users">
								<?php echo JText::_('COM_GDPR_RECORD_TARGET_USERS' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="target_users" name="fields[target_users]" list="target_users_list" class="inputbox-large" value="<?php echo @$this->record->fields['target_users'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="target_users_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ADMINISTRATORS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CITIZENS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CLIENTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_COUNTERPARTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_EMPLOYEES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_JOINED_USERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_MEMBERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PATIENTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROFESSIONISTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROVIDERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROSPECT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PUBLIC_ADMINISTRATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_REVIEWERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_USERS');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="personal_data_category">
								<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_CATEGORY' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="personal_data_category" name="fields[personal_data_category]" list="personal_data_category_list" class="inputbox-large" value="<?php echo @$this->record->fields['personal_data_category'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="personal_data_category_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ACADEMIC_CURRICULUM');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_AFFILIATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_BIOMETRIC_IDENTIFICATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CONVENTIONS_AGREEMENTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CURRENT_JOB');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CURRICULUM_VITAE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_DATA_MENTAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_DATA_PHYSICAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_DATA_SEXUAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ELECTRONIC_IDENTIFICATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FINANCIAL_ASSISTANCE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FINANCIAL_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FINANCIAL_IDENTIFICATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FINANCIAL_MEANS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_FINANCIAL_TRANSACTIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_GENETIC_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_GEOLOCATION_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_HABITS_PREFERENCES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_IMAGES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_INSURANCE_DETAILS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_IMMIGRATION_STATUS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_JUDICIAL_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_LIFESTYLE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_MORTGAGES_LOANS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_INFORMATIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_IDENTIFICATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROFESSIONAL_ACTIVITIES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PHILOSOPHICAL_CONVICTIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PSYCHICAL_DESCRIPTIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PHYSICAL_DESCRIPTION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_POLITICAL_OPINIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_POLITICAL_TRENDS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROFESSIONAL_QUALIFICATIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROFESSIONAL_EXPERIENCE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PAYABLES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PAYMENTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_RACIAL_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SAFETY');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SOCIAL_CONTACTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SOUND_RECORDINGS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_TRAVEL_DETAILS');?>">
							</datalist>
						</td>
					</tr>
					
					<tr>
						<td class="key left_title">
							<label for="personal_data_type">
								<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_TYPE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="personal_data_type" name="fields[personal_data_type]" list="personal_data_type_list" class="inputbox-large" value="<?php echo @$this->record->fields['personal_data_type'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="personal_data_type_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SENSITIVE_DATA');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_JUDICIAL_DATA');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="third_party_recipients">
								<?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_RECIPIENTS' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="third_party_recipients" name="fields[third_party_recipients]" list="third_party_recipients_list" class="inputbox-large" value="<?php echo @$this->record->fields['third_party_recipients'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="third_party_recipients_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ADMINISTRATIONS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_BANKS_INSURANCE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CONSULTANTS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_EMPLOYER');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_IT_SERVICE_PROVIDERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_JUSTICE_POLICE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_MARKETING_COMPANIES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_OWNER');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PRIVATE_COMPANIES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROCESSING_PLATFORMS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PUBLIC_SERVICES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SOCIAL_SECURITY');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="third_party_responsible">
								<?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_RESPONSIBLE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="third_party_responsible" name="fields[third_party_responsible]" rows="5" cols="15"><?php echo @$this->record->fields['third_party_responsible'];?></textarea>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="third_party_country">
								<?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_COUNTRY' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="third_party_country" name="fields[third_party_country]" rows="5" cols="15"><?php echo @$this->record->fields['third_party_country'];?></textarea>
						</td>
					</tr>
					
					<tr>
						<td class="key left_title">
							<label for="third_party_transfer_guarantee">
								<?php echo JText::_('COM_GDPR_RECORD_THIRD_PARTY_TRANSFER_GUARANTEE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="third_party_transfer_guarantee" name="fields[third_party_transfer_guarantee]" rows="5" cols="15"><?php echo @$this->record->fields['third_party_transfer_guarantee'];?></textarea>
						</td>
					</tr>
					
					<tr>
						<td class="key left_title">
							<label for="data_storage_time">
								<?php echo JText::_('COM_GDPR_RECORD_DATA_STORAGE_TIME' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="data_storage_time" name="fields[data_storage_time]" rows="5" cols="15"><?php echo @$this->record->fields['data_storage_time'];?></textarea>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="security_measure">
								<?php echo JText::_('COM_GDPR_RECORD_SECURITY_MEASURE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<textarea id="security_measure" name="fields[security_measure]" rows="5" cols="15"><?php echo @$this->record->fields['security_measure'];?></textarea>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="legal_basis_art6">
								<?php echo JText::_('COM_GDPR_RECORD_LEGAL_BASIS_ART6' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="legal_basis_art6" name="fields[legal_basis_art6]" list="legal_basis_art6_list" class="inputbox-large" value="<?php echo @$this->record->fields['legal_basis_art6'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="legal_basis_art6_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CONSENT_PARTY');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_EXECUTION_CONTRACT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_LEGAL_OBLIGATION');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_LEGITIMATE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PRIVATE_COMPANIES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PUBLIC_POWERS');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_SAFEGUARDING');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="legal_basis_art9">
								<?php echo JText::_('COM_GDPR_RECORD_LEGAL_BASIS_ART9' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="legal_basis_art9" name="fields[legal_basis_art9]" list="legal_basis_art9_list" class="inputbox-large" value="<?php echo @$this->record->fields['legal_basis_art9'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="legal_basis_art9_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ARCHIVING');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_CONSENT_INTERESTED');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_HISTORICAL_STATISTICAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_JUDICIAL_TREATMENT');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PERSONAL_DATA_PUBLIC');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROFESSIONAL_SECRECY');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PROTECTS_VITAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PURPOSES_MEDICINE');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_PUBLIC_INTEREST_HEALTH');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="data_storage">
								<?php echo JText::_('COM_GDPR_RECORD_DATA_STORAGE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="data_storage" name="fields[data_storage]" list="data_storage_list" class="inputbox-large" value="<?php echo @$this->record->fields['data_storage'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="data_storage_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ANALOGIC');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_DIGITAL');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_ANALOGIC_DIGITAL');?>">
							</datalist>
						</td>
					</tr> 
					
					<tr>
						<td class="key left_title">
							<label for="code_certification">
								<?php echo JText::_('COM_GDPR_RECORD_CODE_CERTIFICATION' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" id="code_certification" name="fields[code_certification]" list="code_certification_list" class="inputbox-large" value="<?php echo @$this->record->fields['code_certification'];?>">
							<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
							<datalist id="code_certification_list">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_YES');?>">
								<option value="<?php echo JText::_('COM_GDPR_RECORD_NO');?>">
							</datalist>
						</td>
					</tr> 
				</tbody>
				</table>
			</div>
		</div>
		
		<div class="accordion-heading opened">
			<div class="accordion-toggle noaccordion">
				<h4><span class="icon-pencil"></span><?php echo JText::_( 'COM_GDPR_DATA_PROTECTION_IMPACT_ASSESSMENT' ); ?></h4>
			</div>
		</div>
		<div id="details2" class="accordion-body collapse in">
			<div class="accordion-inner">
				<table class="admintable">
					<tbody>
						<tr>
							<td class="key left_title">
								<label for="required_assessment">
									<?php echo JText::_('COM_GDPR_RECORD_REQUIRED_ASSESSMENT' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input type="text" id="required_assessment" name="fields[required_assessment]" list="required_assessment_list" class="inputbox-large" value="<?php echo @$this->record->fields['required_assessment'];?>">
								<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
								<datalist id="required_assessment_list">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_YES');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_NO');?>">
								</datalist>
							</td>
						</tr>
						
						<tr>
							<td class="key left_title">
								<label for="risk_analysis_event">
									<?php echo JText::_('COM_GDPR_RECORD_RISK_ANALYSIS_EVENT' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<textarea id="risk_analysis_event" name="fields[risk_analysis_event]" rows="5" cols="15"><?php echo @$this->record->fields['risk_analysis_event'];?></textarea>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="risk_level_probability">
									<?php echo JText::_('COM_GDPR_RECORD_RISK_LEVEL_PROBABILITY' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input type="text" id="risk_level_probability" name="fields[risk_level_probability]" list="risk_level_probability_list" class="inputbox-large" value="<?php echo @$this->record->fields['risk_level_probability'];?>">
								<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
								<datalist id="risk_level_probability_list">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_VERY_LOW');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_LOW');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_AVERAGE');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_HIGH');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_VERY_HIGH');?>">
								</datalist>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="risk_level_consequences">
									<?php echo JText::_('COM_GDPR_RECORD_RISK_LEVEL_CONSEQUENCES' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input type="text" id="risk_level_consequences" name="fields[risk_level_consequences]" list="risk_level_consequences_list" class="inputbox-large" value="<?php echo @$this->record->fields['risk_level_consequences'];?>">
								<button class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_RECORD_CLEAR');?></button>
								<datalist id="risk_level_consequences_list">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_VERY_LOW');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_LOW');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_AVERAGE');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_HIGH');?>">
									<option value="<?php echo JText::_('COM_GDPR_RECORD_VERY_HIGH');?>">
								</datalist>
							</td>
						</tr>
						
						<tr>
							<td class="key left_title">
								<label for="security_measures">
									<?php echo JText::_('COM_GDPR_RECORD_SECURITY_MEASURES' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<textarea id="security_measures" name="fields[security_measures]" rows="5" cols="15"><?php echo @$this->record->fields['security_measures'];?></textarea>
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