<?php
/**
 * @package     MPF
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2016 Ossolution Team, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Joomla CMS Base View Html Class
 *
 * @package      MPF
 * @subpackage   View
 * @since        2.0
 */
jimport('joomla.filesystem.path');

class MPFViewHtml extends MPFView
{
	/**
	 * The view layout.
	 *
	 * @var string
	 */
	protected $layout = 'default';

	/**
	 * The paths queue.
	 *
	 * @var array
	 */
	protected $paths = array();

	/**
	 * Default Itemid variable value for the links in the view
	 *
	 * @var int
	 */
	public $Itemid;

	/**
	 * The input object passed from the controller while creating the view
	 *
	 * @var MPFInput
	 */

	protected $input;

	/**
	 * This is a front-end or back-end view.
	 * We need this field to determine whether we need to addToolbar or build the filter
	 *
	 * @var boolean
	 */
	protected $isAdminView = false;

	/**
	 * Options to allow hide default toolbar buttons from backend view
	 *
	 * @var array
	 */
	protected $hideButtons = array();

	/**
	 * Method to instantiate the view.
	 *
	 * @param array $config A named configuration array for object construction
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (isset($config['layout']))
		{
			$this->layout = $config['layout'];
		}

		if (isset($config['paths']))
		{
			$this->paths = $config['paths'];
		}
		else
		{
			$this->paths = array();
		}

		if (!empty($config['is_admin_view']))
		{
			$this->isAdminView = $config['is_admin_view'];
		}

		if (!empty($config['Itemid']))
		{
			$this->Itemid = $config['Itemid'];
		}

		if (isset($config['input']))
		{
			$this->input = $config['input'];
		}

		if (isset($config['hide_buttons']))
		{
			$this->hideButtons = $config['hide_buttons'];
		}
	}

	/**
	 * Method to display the view
	 */
	public function display()
	{
		echo $this->render();
	}

	/**
	 * Magic toString method that is a proxy for the render method.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Method to escape output.
	 *
	 * @param string $output The output to escape.
	 *
	 * @return string The escaped output.
	 */
	public function escape($output)
	{
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Method to get the view layout.
	 *
	 * @return string The layout name.
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Method to get the layout path.
	 *
	 * @param string $layout The layout name.
	 *
	 * @return mixed The layout file name if found, false otherwise.
	 */
	public function getPath($layout)
	{
		$layouts = OSMembershipHelperHtml::getPossibleLayouts($layout);

		foreach ($layouts as $layout)
		{
			// Get the layout file name.
			$file = JPath::clean($layout . '.php');

			// Find the layout file path.
			$path = JPath::find($this->paths, $file);

			if ($path !== false)
			{
				return $path;
			}
		}

		return false;
	}

	/**
	 * Method to get the view paths.
	 *
	 * @return array The paths queue.
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Method to render the view.
	 *
	 * @return string The rendered view.
	 *
	 * @throws RuntimeException
	 */
	public function render()
	{
		// Get the layout path.
		$path = $this->getPath($this->getLayout());

		// Check if the layout path was found.
		if (!$path)
		{
			throw new RuntimeException('Layout Path Not Found');
		}

		// Start an output buffer.
		ob_start();

		// Load the layout.
		include $path;

		// Get the layout contents.
		return ob_get_clean();
	}

	/**
	 * Load sub-template for the current layout
	 *
	 * @param string $template
	 *
	 * @throws RuntimeException
	 *
	 * @return string The output of sub-layout
	 */
	public function loadTemplate($template, $data = array())
	{
		// Get the layout path.
		$path = $this->getPath($this->getLayout() . '_' . $template);

		// Check if the layout path was found.
		if (!$path)
		{
			throw new RuntimeException('Layout Path Not Found');
		}

		extract($data);

		// Start an output buffer.
		ob_start();

		// Load the layout.
		include $path;

		// Get the layout contents.
		return ob_get_clean();
	}

	/**
	 * Load common template for the view
	 *
	 * @param string $layout
	 *
	 * @throws RuntimeException
	 *
	 * @return string The output of common layout
	 */
	public function loadCommonLayout($layout, $data = array())
	{
		$app       = JFactory::getApplication();
		$themeFile = str_replace('/tmpl', '', $layout);

		if (JFile::exists(JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_osmembership/' . $themeFile))
		{
			$path = JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_osmembership/' . $themeFile;
		}
		elseif (JFile::exists(JPATH_ROOT . '/components/com_osmembership/view/' . $layout))
		{
			$path = JPATH_ROOT . '/components/com_osmembership/view/' . $layout;
		}
		else
		{
			throw new RuntimeException(JText::_('The given shared layout is not exist'));
		}

		// Start an output buffer.
		ob_start();
		extract($data);

		// Load the layout.
		include $path;

		// Get the layout contents.
		return ob_get_clean();
	}

	/**
	 * Method to set the view layout.
	 *
	 * @param string $layout The layout name.
	 *
	 * @return MPFViewHtml Method supports chaining.
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;

		return $this;
	}

	/**
	 * Method to set the view paths.
	 *
	 * @param array $paths The paths queue.
	 *
	 * @return MPFViewHtml Method supports chaining.
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;

		return $this;
	}

	/**
	 * Set document meta data
	 *
	 * @param Joomla\Registry\Registry $params
	 *
	 * @return void
	 */
	protected function setDocumentMetadata($params)
	{
		/* @var JDocumentHtml $document */
		$document         = JFactory::getDocument();
		$siteNamePosition = JFactory::getConfig()->get('sitename_pagetitles');
		$siteName         = JFactory::getConfig()->get('sitename');

		if ($pageTitle = $params->get('page_title'))
		{
			if ($siteNamePosition == 0)
			{
				$document->setTitle($pageTitle);
			}
			elseif ($siteNamePosition == 1)
			{
				$document->setTitle($siteName . ' - ' . $pageTitle);
			}
			else
			{
				$document->setTitle($pageTitle . ' - ' . $siteName);
			}
		}

		if ($params->get('menu-meta_keywords'))
		{
			$document->setMetaData('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('menu-meta_description'))
		{
			$document->setDescription($params->get('menu-meta_description'));
		}

		if ($params->get('robots'))
		{
			$document->setMetaData('robots', $params->get('robots'));
		}
	}

	/**
	 * Method to request user login before they can access to thsi page
	 *
	 * @param   string  $msg  The redirect message
	 *
	 * @throws Exception
	 */
	protected function requestLogin($msg = 'OSM_PLEASE_LOGIN')
	{
		if (!JFactory::getUser()->get('id'))
		{
			$app    = JFactory::getApplication();
			$active = $app->getMenu()->getActive();

			$option = isset($active->query['option']) ? $active->query['option'] : '';
			$view   = isset($active->query['view']) ? $active->query['view'] : '';

			if ($option == 'com_osmembership' && $view == strtolower($this->getName()))
			{
				$returnUrl = 'index.php?Itemid=' . $active->id;
			}
			else
			{
				$returnUrl = JUri::getInstance()->toString();
			}

			$url = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($returnUrl), false);

			$app->enqueueMessage(JText::_($msg));
			$app->redirect($url);
		}
	}
}
