<?php
/**
 * @version     1.3.6
 * @package     com_services
 * @copyright	Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Token controller class.
 *
 * @since  1.6
 */
class ServicesControllerToken extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 *
	 * @since 1.0
	 */
	public function __construct()
	{
		$this->view_list = 'tokens';
		parent::__construct();
	}
}
