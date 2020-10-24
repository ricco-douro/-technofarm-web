<?php
// namespace components\com_gdpr\libraries;
/**
 *
 * @package GDPR::components::com_gdpr
 * @subpackage libraries
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.language.language' );

/**
 *
 * @package GDPR::components::com_gdpr
 * @subpackage libraries
 * @since 1.0
 */
class GdprHelpersLanguage extends JLanguage {
	/**
	 * Get the sef string for the current language
	 *
	 * @access public
	 * @return string
	 */
	public static function getCurrentSefLanguage() {
		$defaultLanguageSef = null;
		$knownLangs = JLanguageHelper::getLanguages ();
		
		// Setup predefined site language
		$defaultLanguageCode = JFactory::getLanguage ()->getTag ();
		
		foreach ( $knownLangs as $knownLang ) {
			if ($knownLang->lang_code == $defaultLanguageCode) {
				$defaultLanguageSef = $knownLang->sef;
				break;
			}
		}
		
		return $defaultLanguageSef;
	}
	
	/**
	 * Returns strings transliterated from UTF-8 to Latin
	 *
	 * @param string $string
	 *        	String to transliterate
	 * @param integer $case
	 *        	Optionally specify upper or lower case. Default to null.
	 *
	 * @return string Transliterated string
	 *
	 * @since 11.1
	 */
	public static function utf8_latin_to_ascii($string, $case = 0) {
		static $UTF8_LOWER_ACCENTS = null;
		static $UTF8_UPPER_ACCENTS = null;

		if ($case <= 0) {
			if (is_null ( $UTF8_LOWER_ACCENTS )) {
				$UTF8_LOWER_ACCENTS = array (
						'Ã ' => 'a',
						'Ã´' => 'o',
						'Ä�' => 'd',
						'á¸Ÿ' => 'f',
						'Ã«' => 'e',
						'Å¡' => 's',
						'Æ¡' => 'o',
						'ÃŸ' => 'ss',
						'Äƒ' => 'a',
						'Å™' => 'r',
						'È›' => 't',
						'Åˆ' => 'n',
						'Ä�' => 'a',
						'Ä·' => 'k',
						'Å�' => 's',
						'á»³' => 'y',
						'Å†' => 'n',
						'Äº' => 'l',
						'Ä§' => 'h',
						'á¹—' => 'p',
						'Ã³' => 'o',
						'Ãº' => 'u',
						'Ä›' => 'e',
						'Ã©' => 'e',
						'Ã§' => 'c',
						'áº�' => 'w',
						'Ä‹' => 'c',
						'Ãµ' => 'o',
						'á¹¡' => 's',
						'Ã¸' => 'o',
						'Ä£' => 'g',
						'Å§' => 't',
						'È™' => 's',
						'Ä—' => 'e',
						'Ä‰' => 'c',
						'Å›' => 's',
						'Ã®' => 'i',
						'Å±' => 'u',
						'Ä‡' => 'c',
						'Ä™' => 'e',
						'Åµ' => 'w',
						'á¹«' => 't',
						'Å«' => 'u',
						'Ä�' => 'c',
						'Ã¶' => 'oe',
						'Ã¨' => 'e',
						'Å·' => 'y',
						'Ä…' => 'a',
						'Å‚' => 'l',
						'Å³' => 'u',
						'Å¯' => 'u',
						'ÅŸ' => 's',
						'ÄŸ' => 'g',
						'Ä¼' => 'l',
						'Æ’' => 'f',
						'Å¾' => 'z',
						'áºƒ' => 'w',
						'á¸ƒ' => 'b',
						'Ã¥' => 'a',
						'Ã¬' => 'i',
						'Ã¯' => 'i',
						'á¸‹' => 'd',
						'Å¥' => 't',
						'Å—' => 'r',
						'Ã¤' => 'ae',
						'Ã­' => 'i',
						'Å•' => 'r',
						'Ãª' => 'e',
						'Ã¼' => 'ue',
						'Ã²' => 'o',
						'Ä“' => 'e',
						'Ã±' => 'n',
						'Å„' => 'n',
						'Ä¥' => 'h',
						'Ä�' => 'g',
						'Ä‘' => 'd',
						'Äµ' => 'j',
						'Ã¿' => 'y',
						'Å©' => 'u',
						'Å­' => 'u',
						'Æ°' => 'u',
						'Å£' => 't',
						'Ã½' => 'y',
						'Å‘' => 'o',
						'Ã¢' => 'a',
						'Ä¾' => 'l',
						'áº…' => 'w',
						'Å¼' => 'z',
						'Ä«' => 'i',
						'Ã£' => 'a',
						'Ä¡' => 'g',
						'á¹�' => 'm',
						'Å�' => 'o',
						'Ä©' => 'i',
						'Ã¹' => 'u',
						'Ä¯' => 'i',
						'Åº' => 'z',
						'Ã¡' => 'a',
						'Ã»' => 'u',
						'Ã¾' => 'th',
						'Ã°' => 'dh',
						'Ã¦' => 'ae',
						'Âµ' => 'u',
						'Ä•' => 'e',
						'Å“' => 'oe'
				);
			}

			$string = str_replace ( array_keys ( $UTF8_LOWER_ACCENTS ), array_values ( $UTF8_LOWER_ACCENTS ), $string );
		}

		if ($case >= 0) {
			if (is_null ( $UTF8_UPPER_ACCENTS )) {
				$UTF8_UPPER_ACCENTS = array (
						'Ã€' => 'A',
						'Ã”' => 'O',
						'ÄŽ' => 'D',
						'á¸ž' => 'F',
						'Ã‹' => 'E',
						'Å ' => 'S',
						'Æ ' => 'O',
						'Ä‚' => 'A',
						'Å˜' => 'R',
						'Èš' => 'T',
						'Å‡' => 'N',
						'Ä€' => 'A',
						'Ä¶' => 'K',
						'Åœ' => 'S',
						'á»²' => 'Y',
						'Å…' => 'N',
						'Ä¹' => 'L',
						'Ä¦' => 'H',
						'á¹–' => 'P',
						'Ã“' => 'O',
						'Ãš' => 'U',
						'Äš' => 'E',
						'Ã‰' => 'E',
						'Ã‡' => 'C',
						'áº€' => 'W',
						'ÄŠ' => 'C',
						'Ã•' => 'O',
						'á¹ ' => 'S',
						'Ã˜' => 'O',
						'Ä¢' => 'G',
						'Å¦' => 'T',
						'È˜' => 'S',
						'Ä–' => 'E',
						'Äˆ' => 'C',
						'Åš' => 'S',
						'ÃŽ' => 'I',
						'Å°' => 'U',
						'Ä†' => 'C',
						'Ä˜' => 'E',
						'Å´' => 'W',
						'á¹ª' => 'T',
						'Åª' => 'U',
						'ÄŒ' => 'C',
						'Ã–' => 'Oe',
						'Ãˆ' => 'E',
						'Å¶' => 'Y',
						'Ä„' => 'A',
						'Å�' => 'L',
						'Å²' => 'U',
						'Å®' => 'U',
						'Åž' => 'S',
						'Äž' => 'G',
						'Ä»' => 'L',
						'Æ‘' => 'F',
						'Å½' => 'Z',
						'áº‚' => 'W',
						'á¸‚' => 'B',
						'Ã…' => 'A',
						'ÃŒ' => 'I',
						'Ã�' => 'I',
						'á¸Š' => 'D',
						'Å¤' => 'T',
						'Å–' => 'R',
						'Ã„' => 'Ae',
						'Ã�' => 'I',
						'Å”' => 'R',
						'ÃŠ' => 'E',
						'Ãœ' => 'Ue',
						'Ã’' => 'O',
						'Ä’' => 'E',
						'Ã‘' => 'N',
						'Åƒ' => 'N',
						'Ä¤' => 'H',
						'Äœ' => 'G',
						'Ä�' => 'D',
						'Ä´' => 'J',
						'Å¸' => 'Y',
						'Å¨' => 'U',
						'Å¬' => 'U',
						'Æ¯' => 'U',
						'Å¢' => 'T',
						'Ã�' => 'Y',
						'Å�' => 'O',
						'Ã‚' => 'A',
						'Ä½' => 'L',
						'áº„' => 'W',
						'Å»' => 'Z',
						'Äª' => 'I',
						'Ãƒ' => 'A',
						'Ä ' => 'G',
						'á¹€' => 'M',
						'ÅŒ' => 'O',
						'Ä¨' => 'I',
						'Ã™' => 'U',
						'Ä®' => 'I',
						'Å¹' => 'Z',
						'Ã�' => 'A',
						'Ã›' => 'U',
						'Ãž' => 'Th',
						'Ã�' => 'Dh',
						'Ã†' => 'Ae',
						'Ä”' => 'E',
						'Å’' => 'Oe'
				);
			}

			$string = str_replace ( array_keys ( $UTF8_UPPER_ACCENTS ), array_values ( $UTF8_UPPER_ACCENTS ), $string );
		}

		return $string;
	}
	
	/**
	 * Injector language const to JS domain with same name mapping
	 * 
	 * @access protected
	 * @param $translations Object&        	
	 * @param $document Object&        	
	 * @return void
	 */
	public function injectJsTranslations(&$translations, &$document) {
		$jsInject = null;
		// Do translations
		foreach ( $translations as $translation ) {
			$jsTranslation = strtoupper ( $translation );
			$translated = JText::_ ( $jsTranslation, true );
			$jsInject .= <<<JS
				var $jsTranslation = '{$translated}'; 
JS;
		}
		$document->addScriptDeclaration ( $jsInject );
	}
	
	/**
	 * Override Language instantiator
	 *
	 * @access public
	 * @return JLanguage The Language object.
	 * @since 1.5
	 */
	public static function getInstance($lang = null, $debug = false) {
		static $lang;
		
		if (! is_object ( $lang )) {
			$conf = JFactory::getConfig ();
			$locale = $conf->get ( 'config.language' );
			$lang = new GDprHelpersLanguage ( $locale );
			$lang->setDebug ( $conf->get ( 'config.debug_lang' ) );
		}
		
		return $lang;
	}
}
