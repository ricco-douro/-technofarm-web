<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class plgSystemMPSPPageBuilder extends JPlugin
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

		$this->canRun = file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php') &&
			file_exists(JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/sppagebuilder.php');

		if ($this->canRun)
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';
		}
	}

	/**
	 * Render articles restriction setting form
	 *
	 * @param OSMembershipTablePlan $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		if (!$this->canRun)
		{
			return;
		}

		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('OSM_SPPAGEBUILDER_RESTRICTION_SETTINGS'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database
	 *
	 * @param OSMembershipTablePlan $row
	 * @param Boolean               $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
	    if (!$this->canRun)
        {
            return;
        }

        $query   = $this->db->getQuery(true);
		$planId  = $row->id;

		if (!$isNew)
		{
			$query->delete('#__osmembership_sppagebuilder_pages')->where('plan_id=' . (int) $planId);
			$this->db->setQuery($query);
			$this->db->execute();
		}

		if (!empty($data['sppagesbuilder_page_ids']))
		{
			$pageIds = explode(',', $data['sppagesbuilder_page_ids']);

			$query->clear()
                ->insert('#__osmembership_sppagebuilder_pages')
				->columns('plan_id, page_id');

			for ($i = 0; $i < count($pageIds); $i++)
			{
				$pageId = $pageIds[$i];
				$query->values(implode(',', $this->db->quote([$row->id, $pageId])));
			}

			$this->db->setQuery($query);
			$this->db->execute();
		}

		if (isset($data['sppagebuilder_category_ids']))
		{
			$selectedCategories = $data['sppagebuilder_category_ids'];
		}
		else
		{
			$selectedCategories = [];
		}

		$params = new Registry($row->params);
		$params->set('sppagebuilder_category_ids', implode(',', $selectedCategories));
		$row->params = $params->toString();
		$row->store();
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param OSMembershipTablePlan $row
	 */
	private function drawSettingForm($row)
	{
		$query = $this->db->getQuery(true)
			->select('id, title')
			->from('#__categories')
			->where('extension = "com_sppagebuilder"')
			->where('published = 1');
		$this->db->setQuery($query);
		$categories = $this->db->loadObjectList();
		$query->clear()
			->select('id, title, catid')
			->from('#__sppagebuilder')
			->where('`published` = 1');
		$this->db->setQuery($query);
		$pages = $this->db->loadObjectList();

		if (!count($pages))
		{
			return;
		}

		$listPages = [];
		$listPages[0] = [];

		foreach ($pages as $page)
		{
			$listPages[$page->catid][] = $page;
		}

		// Remove categories which don't have any articles
		for ($i = 0, $n = count($categories); $i < $n; $i++)
		{
			$category = $categories[$i];

			if (!isset($listPages[$category->id]))
			{
				unset($categories[$i]);
			}
		}

		reset($categories);

		//Get plan pages
		$query->clear()
			->select('page_id')
			->from('#__osmembership_sppagebuilder_pages')
			->where('plan_id = ' . (int) $row->id);
		$this->db->setQuery($query);
		$planPagebuilders = $this->db->loadColumn();

		$params             = new Registry($row->params);
		$selectedCategories = explode(',', $params->get('sppagebuilder_category_ids', ''));

		if(count($categories))
        {
        ?>
            <h2><?php echo JText::_('OSM_SPPAGEBUILDER_CATEGORIES'); ?></h2>
            <p class="text-info"><?php echo JText::_('OSM_SPPAGEBUILDER_CATEGORIES_EXPLAIN'); ?></p>
            <table class="admintable adminform" style="width: 100%;">
		        <?php
		        foreach ($categories as $category)
		        {
			    ?>
                    <tr>
                        <td>
                            <label class="checkbox">
                                <input type="checkbox"<?php if (in_array($category->id, $selectedCategories)) echo ' checked="checked"'; ?>
                                       value="<?php echo $category->id ?>"
                                       name="sppagebuilder_category_ids[]"/> <strong><?php echo $category->title; ?></strong>
                            </label>
                        </td>
                    </tr>
			    <?php
		        }
		        ?>
            </table>
        <?php
        }
		?>
        <h2><?php echo JText::_('OSM_SPPAGEBUILDER_PAGES'); ?></h2>
        <p class="text-info"><?php echo JText::_('OSM_SPPAGEBUILDER_PAGES_EXPLAIN'); ?></p>
        <table class="admintable adminform" style="width: 100%;">
            <tr>
                <td>
                    <div class="accordion" id="sppagebuilder-accordion2">
						<?php
						if (count($listPages[0]))
						{
							$category = new stdClass;
							$category->id = 0;
							$category->title = 'Un-categorized';

							$categories[] = $category;
						}

						$count = 0;

						foreach ($categories as $category)
						{
						?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#sppagebuilder-accordion2" href="#sppagebuilder-collapse<?php echo $category->id; ?>" style="display: inline;">
										<?php echo $category->title; ?>
                                    </a>
                                    <label class="checkbox">
                                        <input type="checkbox" value="<?php echo $category->id ?>" data-category-id="<?php echo $category->id ?>" class="sppagebuilder-category-checkall" />
                                        <strong>#</strong>
                                    </label>
                                </div>
                                <div id="sppagebuilder-collapse<?php echo $category->id; ?>"
                                     class="accordion-body collapse <?php if ($count == 0) echo ' in'; ?>">
                                    <div class="accordion-inner">
										<?php
										$categoryPages = $listPages[$category->id];

										foreach ($categoryPages as $page)
										{
										?>
                                            <label class="checkbox" style="display: block;">
                                                <input type="checkbox" <?php if (in_array($page->id, $planPagebuilders)) echo ' checked="checked" '; ?>
                                                        value="<?php echo $page->id; ?>"
                                                        id="spagebuilder-page-<?php echo $page->id; ?>"
                                                        name="sppagebuilder_page_id[]"
                                                        class="sppagebuilder-page-category-<?php echo $category->id ?> sppagebuilder-page-checkbox"/>
                                                <strong><?php echo $page->title; ?></strong>
                                            </label>
										<?php
										}
										?>
                                    </div>
                                </div>
                            </div>
						<?php
						    $count++;
						}
						?>
                    </div>
                </td>
            </tr>
			<?php
			if ($row->id)
			{
			?>
                <input type="hidden" value="<?php echo implode(',', $planPagebuilders) ?>" name="sppagesbuilder_page_ids"
                       id="sppagebuilder-page-ids"/>
			<?php
			}
			else
			{
			?>
                <input type="hidden" value="" name="sppagesbuilder_page_ids" id="sppagebuilder-page-ids"/>
			<?php
			}
			?>
        </table>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    $(".sppagebuilder-category-checkall").click(function () {
                        var ID = $(this).data("category-id");

                        if ($(this).is(':checked'))
                        {
                            $('.sppagebuilder-page-category-' + ID).attr("checked", true);
                        }
                        else
                        {
                            $('.sppagebuilder-page-category-' + ID).attr("checked", false);
                        }

                        $('#sppagebuilder-page-ids').val(getPageIds());
                    });

                    $(".sppagebuilder-page-checkbox").click(function () {
                        $('#sppagebuilder-page-ids').val(getPageIds());
                    });

                    getPageIds = (function () {
                        var pageIdArray = [];
                        $('.sppagebuilder-page-checkbox:checked').each(function () {
                            pageIdArray.push($(this).val());
                        });

                        return pageIdArray;
                    })
                })
            })(jQuery)
        </script>
		<?php
	}

	/**
	 * Protect access to articles
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function onAfterRoute()
	{
		if (!$this->canRun)
		{
			return true;
		}

		if ($this->app->isAdmin())
		{
			return true;
		}

		$user = JFactory::getUser();

		if ($user->authorise('core.admin'))
		{
			return true;
		}

		$option = $this->app->input->getCmd('option');
		$view   = $this->app->input->getCmd('view');

		if ($option != 'com_sppagebuilder' || $view != 'page')
		{
			return true;
		}

		$pageId = $this->app->input->getInt('id');

		if ($this->isPageReleased($pageId))
		{
			return true;
		}

		if ($this->isOwner($pageId))
		{
			return true;
		}

		$planIds = $this->getRequiredPlanIds($pageId);

		if (count($planIds))
		{
			//Check to see the current user has an active subscription plans
			$activePlans = OSMembershipHelper::getActiveMembershipPlans();

			if (!count(array_intersect($planIds, $activePlans)))
			{
				OSMembershipHelper::loadLanguage();

				$msg = JText::_('OSM_SPPAGEBUILDER_PAGE_ACCESS_RESITRICTED');
				$msg = str_replace('[PLAN_TITLES]', $this->getPlanTitles($planIds), $msg);

				// Try to find the best redirect URL
				$redirectUrl = $this->findRedirectUrl($planIds);

				// Add the required plans to redirect URL
				$redirectUri = JUri::getInstance($redirectUrl);
				$redirectUri->setVar('filter_plan_ids', implode(',', $planIds));

				// Store URL of this page to redirect user back after user logged in if they have active subscription of this plan
				$session = JFactory::getSession();
				$session->set('osm_return_url', JUri::getInstance()->toString());
				$session->set('required_plan_ids', $planIds);

				// Redirect to subscription page to allow users to subscribe or logging in
				$this->app->enqueueMessage($msg);
				$this->app->redirect($redirectUri->toString());
			}
		}
	}

	/**
	 * Display list of articles on profile page
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @return array
	 */
	public function onProfileDisplay($row)
	{
		if (!$this->canRun || !$this->params->get('display_pages_in_profile'))
		{
			return;
		}

		ob_start();
		$this->displayPage($row);

		$form = ob_get_clean();

		return array('title' => JText::_('OSM_MY_PAGES'),
		             'form'  => $form,
		);
	}

	/**
	 * Check if article released
	 *
	 * @param int $articleId
	 *
	 * @return bool
	 */
	private function isPageReleased($pageId)
	{
		$query = $this->db->getQuery(true)
			->select('created_on')
			->from('#__sppagebuilder')
			->where('id = ' . (int) $pageId);
		$this->db->setQuery($query);
		$createdOn = $this->db->loadResult();

		$today         = JFactory::getDate();
		$publishedDate = JFactory::getDate($createdOn);
		$numberDays    = $publishedDate->diff($today)->days;

		if (!$this->params->get('release_pages_older_than_x_days'))
		{
			return false;
		}

		// This page is older than configured number of days, it can be accessed for free
		if ($today >= $publishedDate && $numberDays >= $this->params->get('release_pages_older_than_x_days'))
		{
			return true;
		}

		return false;
	}

	/**
	 * The the Ids of the plans which users can subscribe for to access to the given article
	 *
	 * @param int $articleId
	 *
	 * @return array
	 */
	private function getRequiredPlanIds($pageId)
	{
		$query = $this->db->getQuery(true);
		$query->select('DISTINCT plan_id')
			->from('#__osmembership_sppagebuilder_pages')
			->where('page_id = ' . (int) $pageId);
		$this->db->setQuery($query);

		try
		{
			$planIds = $this->db->loadColumn();
		}
		catch (Exception $e)
		{
			$planIds = array();
		}


		$query->clear()
			->select('catid')
			->from('#__sppagebuilder')
			->where('id = ' . (int) $pageId);
		$this->db->setQuery($query);
		$catId = $this->db->loadResult();

		$query->clear()
			->select('id, params')
			->from('#__osmembership_plans')
			->where('published = 1');
		$this->db->setQuery($query);
		$plans = $this->db->loadObjectList();

		foreach ($plans as $plan)
		{
			$params = new Registry($plan->params);

			if ($pageCategories = $params->get('sppagebuilder_category_ids'))
			{
				$pageCategories = explode(',', $pageCategories);

				if (in_array($catId, $pageCategories))
				{
					$planIds[] = $plan->id;
				}
			}
		}

		$query->clear()
			->select('id')
			->from('#__osmembership_plans')
			->where('published = 0');
		$this->db->setQuery($query);

		return array_diff($planIds, $this->db->loadColumn());
	}

	/**
	 * Get imploded titles of the given plans
	 *
	 * @param array $planIds
	 *
	 * @return string
	 */
	private function getPlanTitles($planIds)
	{
		$query = $this->db->getQuery(true);
		$query->select('title')
			->from('#__osmembership_plans')
			->where('id IN (' . implode(',', $planIds) . ')')
			->where('published = 1')
			->order('ordering');
		$this->db->setQuery($query);

		return implode(' ' . JText::_('OSM_OR') . ' ', $this->db->loadColumn());
	}


	/**
	 * Find the best match URL which users can access to subscribe for the one of the given plans
	 *
	 * @param array $planIds
	 *
	 * @return mixed|string
	 */
	private function findRedirectUrl($planIds)
	{
		// Try to find the best redirect URL
		$redirectUrl = OSMembershipHelper::getRestrictionRedirectUrl($planIds);

		if (empty($redirectUrl))
		{
			$redirectUrl = $this->params->get('redirect_url', OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register')));
		}

		if (!$redirectUrl)
		{
			$redirectUrl = JUri::root();
		}

		return $redirectUrl;
	}

	/**
	 * Display articles which subscriber can access to
	 *
	 * @param OSMembershipTableSubscriber $row
	 *
	 * @throws Exception
	 */
	private function displayPage($row)
	{
		$activePlanIds = OSMembershipHelper::getActiveMembershipPlans();
		$items         = array();

		if (count($activePlanIds) > 1)
		{
			$query = $this->db->getQuery(true)
				->select('params')
				->from('#__osmembership_plans')
                ->where('id IN ('.implode(',', $activePlanIds).')');
			$this->db->setQuery($query);
			$rowPlans = $this->db->loadObjectList();
			$selectedCategories = [];

			foreach($rowPlans as $rowPlan)
            {
	            $params             = new Registry($rowPlan->params);
	            $selectedCategories = array_merge($selectedCategories, array_filter(explode(',', $params->get('sppagebuilder_category_ids'))));
            }

            if (!count($selectedCategories))
            {
                $selectedCategories = [0];
            }

			$query->clear()
				->select('a.id, a.catid, a.title, a.hits, c.title AS category_title')
				->from('#__sppagebuilder AS a')
				->leftJoin('#__osmembership_sppagebuilder_pages AS b ON a.id = b.page_id')
				->leftJoin('#__categories AS c ON a.catid = c.id')
				->where('(b.plan_id IN (' . implode(',', $activePlanIds) . ') OR a.catid IN (' . implode(',', $selectedCategories) . '))')
				->where('a.published = 1')
				->order('plan_id')
				->order('a.title');
			$this->db->setQuery($query);
			$items = $this->db->loadObjectList();
		}

		if (empty($items))
		{
			return;
		}
		?>
        <table class="adminlist table table-striped" id="adminForm">
            <thead>
            <tr>
                <th class="title"><?php echo JText::_('OSM_TITLE'); ?></th>
                <th class="title"><?php echo JText::_('OSM_CATEGORY'); ?></th>
                <th class="center"><?php echo JText::_('OSM_HITS'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php';
			$displayedPageIds = array();

			foreach ($items as $item)
			{
				if (in_array($item->id, $displayedPageIds))
				{
					continue;
				}

				$displayedPageIds[] = $item->id;

				$pageLink = JRoute::_(SppagebuilderHelperRoute::getPageRoute($item->id));
				?>
                <tr>
                    <td><a href="<?php echo $pageLink ?>"><?php echo $item->title; ?></a></td>
                    <td><?php echo $item->category_title; ?></td>
                    <td class="center">
						<?php echo $item->hits; ?>
                    </td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
		<?php
	}

	/**
     * Method to check if the current user is the page author
     *
	 * @param int $pageId
	 *
	 * @return bool
	 */
	private function isOwner($pageId = 0)
	{
		if (!$pageId)
        {
            return false;
        }

		$query  = $this->db->getQuery(true)
		    ->select('COUNT(id)')
            ->from('#__sppagebuilder')
            ->where('created_by = ' . JFactory::getUser()->id)
            ->where('id = ' . $pageId);
		$this->db->setQuery($query);

		return $this->db->loadResult() > 0;
	}
}
