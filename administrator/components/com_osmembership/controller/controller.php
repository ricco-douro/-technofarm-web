<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

JLoader::register('OSMembershipControllerData', JPATH_ROOT . '/components/com_osmembership/controller/data.php');

/**
 * Membership Pro controller
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipController extends MPFControllerAdmin
{
	use OSMembershipControllerData;

	/**
	 * Display information
	 */
	public function display($cachable = false, array $urlparams = array())
	{
		if ($this->app->isAdmin())
		{
			// Check and make sure only users with proper permission can access to the page
			$viewName = $this->input->get('view', $this->config['default_view']);
			$this->checkAccessPermission($viewName);

			$document = JFactory::getDocument();
			$rootUri  = JUri::root(true);
			$document->addStyleSheet($rootUri . '/administrator/components/com_osmembership/assets/css/style.css');

			$customCssFile = JPATH_ADMINISTRATOR . '/components/com_osmembership/assets/css/custom.css';

			if (file_exists($customCssFile) && filesize($customCssFile) > 0)
			{
				$document->addStyleSheet($rootUri . '/administrator/components/com_osmembership/assets/css/custom.css');
			}

			JHtml::_('jquery.framework');
			JHtml::_('script', 'media/com_osmembership/assets/js/jquery-noconflict.js', false, false);
			$document->addScriptDeclaration('var siteUrl="' . OSMembershipHelper::getSiteUrl() . '";');
		}

		parent::display($cachable, $urlparams);

		if ($this->app->isAdmin() && $this->input->getCmd('format', 'html') == 'html')
		{
			OSMembershipHelper::displayCopyRight();
		}
	}

	/**
	 * Check to see the installed version is up to date or not
	 *
	 * @return int 0 : error, 1 : Up to date, 2 : outof date
	 */
	public function check_update()
	{
		// Get the caching duration.
		$component     = JComponentHelper::getComponent('com_installer');
		$params        = $component->params;
		$cache_timeout = $params->get('cachetimeout', 6, 'int');
		$cache_timeout = 3600 * $cache_timeout;

		// Get the minimum stability.
		$minimum_stability = $params->get('minimum_stability', JUpdater::STABILITY_STABLE);

		if (version_compare(JVERSION, '4.0.0-dev', 'ge'))
		{
			$model = new \Joomla\Component\Joomlaupdate\Administrator\Model\UpdateModel;
		}
		else
		{
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_installer/models');

			/** @var InstallerModelUpdate $model */
			$model = JModelLegacy::getInstance('Update', 'InstallerModel');
		}

		$model->purge();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id')
			->from('#__extensions')
			->where('`type` = "package"')
			->where('`element` = "pkg_osmembership"');
		$db->setQuery($query);
		$eid = (int) $db->loadResult();

		$result['status'] = 0;

		if ($eid)
		{
			$ret = JUpdater::getInstance()->findUpdates($eid, $cache_timeout, $minimum_stability);

			if ($ret)
			{
				$model->setState('list.start', 0);
				$model->setState('list.limit', 0);
				$model->setState('filter.extension_id', $eid);
				$updates          = $model->getItems();
				$result['status'] = 2;

				if (count($updates))
				{
					$result['message'] = JText::sprintf('OSM_UPDATE_CHECKING_UPDATEFOUND', $updates[0]->version);
				}
				else
				{
					$result['message'] = JText::sprintf('OSM_UPDATE_CHECKING_UPDATEFOUND', null);
				}
			}
			else
			{
				$result['status']  = 1;
				$result['message'] = JText::_('OSM_UPDATE_CHECKING_UPTODATE');
			}
		}

		echo json_encode($result);
		$this->app->close();
	}

	/**
	 * Download invoice
	 */
	public function download_invoice()
	{
		$id = $this->input->getInt('id');
		OSMembershipHelper::downloadInvoice($id);
	}

	/**
	 * Download file uploaded by subscriber
	 */
	public function download_file()
	{
		$filePath = 'media/com_osmembership/upload';
		$fileName = $this->input->getString('file_name', '');

		if (file_exists(JPATH_ROOT . '/' . $filePath . '/' . $fileName))
		{
			while (@ob_end_clean()) ;
			OSMembershipHelper::processDownload(JPATH_ROOT . '/' . $filePath . '/' . $fileName, $fileName, true);
			exit();
		}
		else
		{
			$this->setRedirect('index.php?option=com_osmembership', JText::_('OSM_FILE_NOT_EXIST'));
		}
	}

	/**
	 * Check and make sure only user with proper permischeckAccessPermissionsion can access to certain view
	 *
	 * @param $view
	 */
	protected function checkAccessPermission($view)
	{
		if (!OSMembershipHelper::canAccessThisView($view))
		{
			$this->app->enqueueMessage("You don't have permission to access to this section of Membership Pro", 'error');
			$this->app->redirect('index.php?option=com_osmembership&view=dashboard', 403);
		}
	}
}
