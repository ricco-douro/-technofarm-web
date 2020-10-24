<?php

/**
 * @version     1.3.6
 * @package     com_services
 * @copyright   Copyright (C) 2018 Annatech LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Steve Tsiopanos <steve.tsiopanos@annatech.com> - https://www.annatech.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Class ServicesController
 *
 * @since  1.6
 */
class ServicesController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return $this|JControllerLegacy
	 * @throws Exception
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$app  = JFactory::getApplication();
		$view = $app->input->getCmd('view', 'tokens');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}