<?php
//namespace components\com_gdpr\framework\html;
/**  
 * @package GDPR::components::com_gdpr
 * @subpackage framework 
 * @subpackage html
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */ 
defined ( '_JEXEC' ) or die ( 'Restricted access' );
if(!class_exists('JFormFieldList')) {
	require_once JPATH_SITE . '/libraries/joomla/form/fields/list.php';
}

/**
 * Form Field for categories tree
 * @package GDPR::components::com_gdpr
 * @subpackage framework 
 * @subpackage html
 * @since 1.0
 */
class JFormFieldUserCategories extends JFormFieldList {
	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return string The field input markup.
	 *
	 * @since 11.1
	 */
	protected function getInput() {
		// Initialize variables.
		$html = array ();
		$attr = '';
	
		// Default option translation
		$defaultTranslation = $this->element ['translation'] ? JText::_($this->element ['translation']) : JText::_('COM_GDPR_NO_CATEGORY');
	
		// Initialize some field attributes.
		$attr .= $this->element ['class'] ? ' class="' . ( string ) $this->element ['class'] . '"' : '';
	
		// To avoid user's confusion, readonly="true" should imply
		// disabled="true".
		if (( string ) $this->element ['readonly'] == 'true' || ( string ) $this->element ['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}
	
		$attr .= $this->element ['size'] ? ' size="' . ( int ) $this->element ['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
	
		// Initialize JavaScript field attributes.
		$attr .= $this->element ['onchange'] ? ' onchange="' . ( string ) $this->element ['onchange'] . '"' : '';
	
		// Get the field options.
		$options = ( array ) $this->getOptions ($defaultTranslation);
	
		$html = JHtml::_ ( 'select.genericlist', $options, $this->name, trim ( $attr ), 'value', 'text', $this->value, $this->id );
	
		return $html;
	}
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions($defaultTranslation = null) {
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('COM_GDPR_NO_CATEGORY'), 'value', 'text');
		$options = array_merge($options, JHtml::_('category.options', 'com_users'));
		
		return $options;
	}
}