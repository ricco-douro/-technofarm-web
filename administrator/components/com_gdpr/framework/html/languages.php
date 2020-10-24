<?php
// namespace administrator\components\com_gdpr\framework\html;
/**  
 * @package GDPR::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage html
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport('joomla.language.helper');

/**
 * Languages available
 *
 * @package GDPR::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage html
 *        
 */
class GdprHtmlLanguages extends JObject {
	/**
	 * Build the multiple select list for Menu Links/Pages
	 * 
	 * @access public
	 * @return array
	 */
	public static function getAvailableLanguageOptions() {
		$knownLangs = JLanguageHelper::getLanguages();
		 
		$langs[] = JHtml::_('select.option',  '*', '- '. JText::_('COM_GDPR_ALL_LANGUAGES' ) .' -' );
		
		// Create found languages options
		foreach ($knownLangs as $langObject) {
			// Extract tag lang
			$langs[] = JHtml::_('select.option',  $langObject->lang_code, $langObject->title );
		}
		 
		return $langs;
	}
}