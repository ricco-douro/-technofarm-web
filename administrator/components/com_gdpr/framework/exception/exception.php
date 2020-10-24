<?php
// namespace administrator\components\com_gdpr\framework\exception;
/**
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage exception
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Gdpr Exception object
 *
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage exception
 * @since 1.6.5
 */
class GdprException extends Exception {
	/**
	 * Error level
	 * @access private
	 * @var string
	 */
	private $errorLevel;
	
	/**
	 * Error level accessor method
	 * @access public
	 * @return string
	 */
	public function getErrorLevel() {
		return $this->errorLevel;
	}
	
	/**
	 * Class constructor
	 * @access public
	 * @return Object&
	 */
	public function __construct($message, $level, $code = null) {
		parent::__construct($message, $code);
	
		// Set error level
		$this->errorLevel = $level;
	}
}