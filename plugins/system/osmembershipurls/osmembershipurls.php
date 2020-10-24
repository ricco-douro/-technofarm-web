<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class plgSystemOSMembershipUrls extends JPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 */
	protected $app;
	/**
	 * Database object
	 *
	 * @var JDatabaseDriver
	 */
	protected $db;

	/**
	 * Whether the plugin should be run when events are triggered
	 *
	 * @var bool
	 */
	protected $canRun;

	/**
	 * Constructor
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);

		$this->canRun = file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php');
	}

	/**
	 * Render settings from
	 *
	 * @param PlanOSMembership $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_JOOMLA_URLS_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database
	 *
	 * @param PlanOsMembership $row
	 * @param Boolean          $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$query  = $this->db->getQuery(true);
		$urls   = array_filter(explode("\r\n", $data['urls']));
		$titles = array_filter(explode("\r\n", $data['titles']));

		if (!$isNew)
		{
			$query->delete('#__osmembership_urls')
				->where('plan_id = ' . $row->id);
			$this->db->setQuery($query);
			$this->db->execute();

			$query->clear();
		}

		if (count($urls))
		{
			$query->insert('#__osmembership_urls')
				->columns('plan_id, url, title');

			for ($i = 0, $n = count($urls); $i < $n; $i++)
			{
				$url = trim($urls[$i]);

				if ($url)
				{
					$title = !empty($titles[$i]) ? $titles[$i] : '';
					$url   = $this->db->quote($url);
					$title = $this->db->quote($title);
					$query->values("$row->id, $url, $title");
				}
			}

			$this->db->setQuery($query);
			$this->db->execute();
		}
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$urls   = array();
		$titles = array();

		if ($row->id > 0)
		{
			$query = $this->db->getQuery(true);
			$query->select('title, url')
				->from('#__osmembership_urls')
				->where('plan_id = ' . $row->id);
			$this->db->setQuery($query);
			$rows = $this->db->loadObjectList();

			foreach ($rows as $row)
			{
				$urls[]   = $row->url;
				$titles[] = $row->title;
			}
		}
		?>
        <table class="admintable adminform" style="width: 90%;">
            <tr>
                <td class="key" width="110">
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_URLS'); ?>
                </td>
                <td>
					<textarea rows="20" cols="70" name="urls"
                              class="input-xxlarge"><?php echo implode("\r\n", $urls); ?></textarea>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_URLS_EXPLAIN'); ?>
                </td>
            </tr>
            <tr>
                <td class="key" width="110">
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_URLS_TITLE'); ?>
                </td>
                <td>
					<textarea rows="20" cols="70" name="titles"
                              class="input-xxlarge"><?php echo implode("\r\n", $titles); ?></textarea>
                </td>
                <td>
					<?php echo JText::_('PLG_OSMEMBERSHIP_JOOMLA_URLS_TITLE_EXPLAIN'); ?>
                </td>
            </tr>
        </table>
		<?php
	}

	/**
	 * Restrict access to the current URL if it is needed
	 *
	 * @return bool|void
	 * @throws Exception
	 */
	public function onAfterInitialise()
	{
	    if (!$this->canRun)
        {
            return;
        }

        if ($this->app->isAdmin())
		{
			return true;
		}

		if (JFactory::getUser()->authorise('core.admin'))
		{
			return true;
		}

		$currentUrl = trim(JUri::getInstance()->toString());

		//remove www in the url
		$currentUrl = str_replace('www.', '', $currentUrl);
		$siteUrl    = JUri::root();
		$siteUrl    = str_replace('www.', '', $siteUrl);

		if ($siteUrl == $currentUrl)
		{
			//Don't prevent access to homepage
			return;
		}

		$planIds = $this->getRequiredPlanIds($currentUrl);

		$query = $this->db->getQuery(true);

		$query->select('id')
			->from('#__osmembership_plans')
			->where('published = 0');
		$this->db->setQuery($query);
		$unpublishedPlanIds = $this->db->loadColumn();
		$planIds            = array_diff($planIds, $unpublishedPlanIds);

		if (count($planIds))
		{
			// Require library + register autoloader
			require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

			//Check to see the current user has an active subscription plans
			$activePlans = OSMembershipHelper::getActiveMembershipPlans();

			if (!count(array_intersect($planIds, $activePlans)))
			{
				//Load language file
				OSMembershipHelper::loadLanguage();

				//Get title of these subscription plans
				$query->clear()
					->select('title')
					->from('#__osmembership_plans')
					->where('id IN (' . implode(',', $planIds) . ')')
					->where('published = 1')
					->order('ordering');
				$this->db->setQuery($query);

				$planTitles = implode(' ' . JText::_('OSM_OR') . ' ', $this->db->loadColumn());
				$msg        = JText::_('OS_MEMBERSHIP_URL_ACCESS_RESITRICTED');
				$msg        = str_replace('[PLAN_TITLES]', $planTitles, $msg);

				$redirectUrl = $this->params->get('redirect_url', '');

				// Try to find the best redirect URL
				if (!$redirectUrl)
				{
					$redirectUrl = OSMembershipHelper::getRestrictionRedirectUrl($planIds);
				}

				if (!$redirectUrl)
				{
					$redirectUrl = OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register'));
				}

				if (!$redirectUrl)
				{
					$redirectUrl = JUri::root();
				}

				// Add the required plans to redirect URL
				$redirectUri = JUri::getInstance($redirectUrl);
				$redirectUri->setVar('plan_ids', implode(',', $planIds));

				// Store URL of this page to redirect user back after user logged in if they have active subscription of this plan
				$session = JFactory::getSession();
				$session->set('osm_return_url', JUri::getInstance()->toString());
				$session->set('required_plan_ids', $planIds);

				$this->app->enqueueMessage($msg);
				$this->app->redirect($redirectUri->toString());
			}
		}
	}

	/**
	 * Display list of accessible URLs on profile page
	 *
	 * @param JTable $row
	 *
	 * @return array
	 */
	public function onProfileDisplay($row)
	{
		if (!$this->params->get('display_urls_in_profile'))
		{
			return;
		}

		ob_start();
		$this->displayUrls($row);
		$form = ob_get_clean();

		return array('title' => JText::_('OSM_MY_PAGES'),
		             'form'  => $form,
		);
	}

	/**
	 * Method to get the required plan Ids to access to the given URLs
	 *
	 * @param  string $url
	 *
	 * @return array
	 */
	protected function getRequiredPlanIds($url)
	{
		$query   = $this->db->getQuery(true);
		$planIds = array();

		switch ($this->params->get('compare_method', 0))
		{
			case 0:
				$query->select('plan_id')
					->from('#__osmembership_urls')
					->where($this->db->quoteName('url') . ' = ' . $this->db->quote($url));
				$this->db->setQuery($query);

				return $this->db->loadColumn();
				break;
			case 1:
				$query->select('url, plan_id')
					->from('#__osmembership_urls');
				$this->db->setQuery($query);
				$rows = $this->db->loadObjectList();

				foreach ($rows as $row)
				{
					$matches = array();

					if (preg_match('~' . preg_quote($row->url) . '~', $url, $matches))
					{
						$planIds[] = $row->plan_id;
					}
				}
				break;
			case 2:
				$query->select('url, plan_id')
					->from('#__osmembership_urls');
				$this->db->setQuery($query);
				$rows = $this->db->loadObjectList();

				foreach ($rows as $row)
				{
					$matches = array();

					if (preg_match('~' . $row->url . '~', $url, $matches))
					{
						$planIds[] = $row->plan_id;
					}
				}
				break;
		}

		return $planIds;
	}

	/**
	 * Display pages which subscriber can access to
	 *
	 * @throws Exception
	 */
	protected function displayUrls()
	{
		$query = $this->db->getQuery(true);

		$activePlanIds = OSMembershipHelper::getActiveMembershipPlans();

		$query->select('title, url')
			->from('#__osmembership_urls')
			->where('plan_id IN (' . implode(',', $activePlanIds) . ')')
			->order('id');
		$this->db->setQuery($query);

		$urls = $this->db->loadObjectList();

		if (empty($urls))
		{
			return;
		}

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		?>
        <table class="adminlist <?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
            <thead>
            <tr>
                <th class="title"><?php echo JText::_('OSM_PAGE_URL'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ($urls as $url)
			{
			?>
                <tr>
                    <td><a href="<?php echo $url->url ?>"
                           target="_blank"><?php echo $url->title ? $url->title : $url->url; ?></a></td>
                </tr>
			<?php
			}
			?>
            </tbody>
        </table>
		<?php
	}
}
