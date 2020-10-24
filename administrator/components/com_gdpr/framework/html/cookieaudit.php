<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldCookieaudit extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Cookieaudit';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		$uri = JUri::root();
		$hostDomain = trim(preg_replace('/http(s?):\/\//i', '', $uri), '/');
		$domain = 'https://cookiepedia.co.uk/website/' . urlencode($hostDomain);
		$html = '<a class="label label-success label-audit hasPopover" data-content="' . JText::_('COM_GDPR_COOKIEPEDIA_AUDIT_DESC') .'" href="' . $domain . '" target="_blank">' . JText::_('COM_GDPR_COOKIEPEDIA_AUDIT') .'</a>';

		return $html;
	}

}
