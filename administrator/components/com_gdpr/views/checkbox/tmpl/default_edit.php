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
if (!class_exists('JEditor')) {
	$editor = JFactory::getEditor();
} else {
	$config = JFactory::getConfig();
	$editor = JEditor::getInstance($config->get('editor'));
}
?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="accordion-group">
		<div class="accordion-heading opened">
			<div class="accordion-toggle noaccordion">
				<h4><span class="icon-pencil"></span><?php echo JText::_( 'COM_GDPR_CHECKBOX_DETAILS' ); ?></h4>
			</div>
		</div>
		<div id="details" class="accordion-body collapse in">
	      	<div class="accordion-inner">
				<table class="admintable">
					<tbody>
						<tr>
							<td class="key left_title">
								<label for="placeholder" class="hasPopover" data-content="<?php echo JText::_('COM_GDPR_CHECKBOX_PLACEHOLDER_DESC' ); ?>">
									<?php echo JText::_('COM_GDPR_CHECKBOX_PLACEHOLDER' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input data-role="copyclipboard" class="inputbox-large" type="text" readonly name="placeholder" id="placeholder" value="<?php echo $this->record->placeholder;?>" />
								<button data-role="copyclipboard" data-success="<?php echo JText::_('COM_GDPR_CHECKBOX_COPY_CLIPBOARD_COPIED');?>" class="btn btn-mini btn-success"><?php echo JText::_('COM_GDPR_CHECKBOX_COPY_CLIPBOARD');?></button>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="name" class="hasPopover" data-content="<?php echo JText::_('COM_GDPR_CHECKBOX_NAME_DESC' ); ?>">
									<?php echo JText::_('COM_GDPR_CHECKBOX_NAME' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input class="inputbox-large" type="text" id="name" name="name" data-validation="required" value="<?php echo $this->record->name;?>" />
							</td>
						</tr>
						
						<tr>
							<td class="key left_title">
								<label for="description" class="hasPopover" data-content="<?php echo JText::_('COM_GDPR_CHECKBOX_DESCRIPTION_DESC' ); ?>">
									<?php echo JText::_('COM_GDPR_CHECKBOX_DESCRIPTION' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<?php echo $editor->display('descriptionhtml', $this->record->descriptionhtml, '600px', '400px', '70', '15', true); ?>
							</td>
						</tr>
					
						<tr>
							<td class="key left_title">
								<label for="formselector" class="hasPopover" data-content="<?php echo JText::_('COM_GDPR_CHECKBOX_FORMSELECTOR_DESC' ); ?>">
									<?php echo JText::_('COM_GDPR_CHECKBOX_FORMSELECTOR' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<input class="inputbox-large" type="text" id="formselector" name="formselector" value="<?php echo $this->record->formselector;?>" />
							</td>
						</tr>
						
						<tr>
							<td class="key left_title">
								<label class="hasPopover" data-content="<?php echo JText::_('COM_GDPR_CHECKBOX_REQUIRED_DESC' ); ?>">
									<?php echo JText::_('COM_GDPR_CHECKBOX_REQUIRED' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group">
									<?php echo $this->lists['required']; ?>
								</fieldset>
							</td>
						</tr> 
						
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
								<label>
									<?php echo JText::_('COM_GDPR_ACCESS' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group">
									<?php echo $this->lists['access']; ?>
								</fieldset>
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