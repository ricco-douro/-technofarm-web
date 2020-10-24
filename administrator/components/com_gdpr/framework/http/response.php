<?php
// namespace administrator\components\com_gdpr\framework\http;
/**
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage http
 * @author Joomla! Extensions Store
 * @copyright (C) 2018 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * HTTP response data object class.
 *
 * @package GDPR::FRAMEWORK::administrator::components::com_gdpr
 * @subpackage framework
 * @subpackage http
 * @since 1.6
 */
class GdprHttpResponse {
	/**
	 * @var    integer  The server response code.
	 * @since  11.3
	 */
	public $code;

	/**
	 * @var    array  Response headers.
	 * @since  11.3
	 */
	public $headers = array();

	/**
	 * @var    string  Server response body.
	 * @since  11.3
	 */
	public $body;
}
