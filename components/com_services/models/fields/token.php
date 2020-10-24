<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldToken extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'token';

	/**
	 * Method to get the field input markup.
	 *
	 * @return   string  The field input markup.
	 *
	 * @throws Exception
	 *
	 * @since 1.3.5
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		$token = $this->value;

		if($token === ''){
			$token = str_shuffle(base64_encode(bin2hex(JCrypt::genRandomBytes(12)).random_int(100,999)).random_int(100,999));
		}

		$html[] = '<input required="true" readonly="true" class="readonly" name="' . $this->name . '" value="' . $token . '" />';

		return implode($html);
	}
}
