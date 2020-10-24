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

class plgSystemOSMembershipk2 extends JPlugin
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

		$this->canRun = file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php')
			&& file_exists(JPATH_ROOT . '/components/com_k2/k2.php');

		if ($this->canRun)
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';
		}
	}

	/**
	 * Render settings form
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
		$form = ob_get_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_K2_ITEMS_RESTRICTION_SETTINGS'),
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
		if (!$this->canRun)
		{
			return;
		}

		$query      = $this->db->getQuery(true);
		$planId     = $row->id;
		$articleIds = $data['k2_article_ids'];

		if (!$isNew)
		{
			$query->delete('#__osmembership_k2items')->where('plan_id=' . (int) $planId);
			$this->db->setQuery($query);
			$this->db->execute();
		}

		if (!empty($articleIds))
		{
			$articleIds = explode(',', $articleIds);

			for ($i = 0; $i < count($articleIds); $i++)
			{
				$articleId = $articleIds[$i];
				$query->clear()
					->insert('#__osmembership_k2items')
					->columns('plan_id, article_id')
					->values("$row->id,$articleId");
				$this->db->setQuery($query);
				$this->db->execute();
			}
		}

		if (isset($data['k2_item_categories']))
		{
			$selectedCategories = $data['k2_item_categories'];
		}
		else
		{
			$selectedCategories = array();
		}

		$params = new Registry($row->params);
		$params->set('k2_item_categories', implode(',', $selectedCategories));
		$row->params = $params->toString();
		$row->store();
	}

	/**
	 * Display form allows users to change setting for this subscription plan
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		//Get categories
		$query = $this->db->getQuery(true);
		$query->select('id, name')
			->from('#__k2_categories')
			->where('published = 1');
		$this->db->setQuery($query);
		$categories = $this->db->loadObjectList('id');

		if (!count($categories))
		{
			return;
		}

		$categoryIds = array_keys($categories);
		$query->clear()
			->select('id, title, catid')
			->from('#__k2_items')
			->where('`published` = 1')
			->where('catid IN (' . implode(',', $categoryIds) . ')');
		$this->db->setQuery($query);
		$rowArticles = $this->db->loadObjectList();

		if (!count($rowArticles))
		{
			return;
		}

		$articles = array();

		foreach ($rowArticles as $rowArticle)
		{
			$articles[$rowArticle->catid][] = $rowArticle;
		}

		for ($i = 0, $n = count($categories); $i < $n; $i++)
		{
			$category = $categories[$i];

			if (!isset($rowArticles[$category->id]))
			{
				unset($categories[$i]);
			}
		}

		reset($categories);

		//Get plans articles
		$query->clear()
			->select('article_id')
			->from('#__osmembership_k2items')
			->where('plan_id=' . (int) $row->id);
		$this->db->setQuery($query);
		$planArticles = $this->db->loadColumn();

		$params             = new Registry($row->params);
		$selectedCategories = explode(',', $params->get('k2_item_categories', ''));
		?>

        <h2><?php echo JText::_('OSM_K2_CATEGORIES'); ?></h2>
        <p class="text-info"><?php echo JText::_('OSM_K2_CATEGORIES_EXPLAIN'); ?></p>
        <table class="admintable adminform" style="width: 100%;">
			<?php
			foreach ($categories as $category)
			{
				?>
                <tr>
                    <td>
                        <label class="checkbox">
                            <input
                                    type="checkbox"<?php if (in_array($category->id, $selectedCategories)) echo ' checked="checked"'; ?>
                                    value="<?php echo $category->id ?>"
                                    name="k2_item_categories[]"/> <strong><?php echo $category->name; ?></strong>
                        </label>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>

        <h2><?php echo JText::_('OSM_K2_ITEMS'); ?></h2>
        <p class="text-info"><?php echo JText::_('OSM_K2_ITEMS_EXPLAIN'); ?></p>
        <table class="admintable adminform" style="width: 100%;">
            <tr>
                <td>
                    <div class="accordion" id="k2accordion2">
						<?php
						$count = 0;
						foreach ($categories as $category)
						{
							if (!isset($articles[$category->id]))
							{
								continue;
							}
							?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#k2accordion2"
                                       href="#k2collapse<?php echo $category->id; ?>" style="display: inline;">
										<?php echo $category->name; ?>
                                    </a>
                                    <label class="checkbox"> <input type="checkbox" value="<?php echo $category->id ?>"
                                                                    id="<?php echo $category->id ?>" class="k2checkAll"
                                                                    name=""> <strong>#</strong> </label>
                                </div>
                                <div id="k2collapse<?php echo $category->id; ?>"
                                     class="accordion-body collapse <?php if ($count == 0) echo ' in'; ?>">
                                    <div class="accordion-inner">
										<?php
										$categoryArticles = $articles[$category->id];
										foreach ($categoryArticles as $article)
										{
											?>
                                            <label class="checkbox" style="display: block;">
                                                <input
                                                        type="checkbox" <?php if (in_array($article->id, $planArticles)) echo ' checked="checked" '; ?>
                                                        value="<?php echo $article->id; ?>"
                                                        id="k2article_<?php echo $article->id; ?>"
                                                        name="k2_article_id[]"
                                                        class="k2checkall_<?php echo $category->id ?> k2_item_checkbox"/>
                                                <strong><?php echo $article->title; ?></strong>
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
                <input type="hidden" value="<?php echo implode(',', $planArticles) ?>" name="k2_article_ids"
                       class="k2_article_ids"/>
				<?php
			}
			else
			{
				?>
                <input type="hidden" value="" name="k2_article_ids" class="k2_article_ids"/>
				<?php
			}
			?>
        </table>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    $(".k2checkAll").click(function () {
                        var ID = $(this).attr("id");
                        if ($(this).is(':checked')) {
                            $('.k2checkall_' + ID).attr("checked", true);
                        }
                        else {
                            $('.k2checkall_' + ID).attr("checked", false);
                        }
                        $('.k2_article_ids').val(getItemIds());
                    });

                    $(".k2_item_checkbox").click(function () {
                        $('.k2_article_ids').val(getItemIds());
                    });

                    var k2itemIdArray = new Array;
                    getItemIds = (function () {
                        k2itemIdArray = [];
                        $('.k2_item_checkbox:checked').each(function () {
                            k2itemIdArray.push($(this).val());
                        });
                        console.log(k2itemIdArray);
                        return k2itemIdArray;
                    })

                })
            })(jQuery)
        </script>
		<?php
	}

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

		if ($this->params->get('protection_method', 0) == 1)
		{
			return true;
		}

		if ($this->params->get('allow_search_engine', 0) == 1 && $this->app->client->robot)
		{
			return true;
		}

		$option    = $this->app->input->getCmd('option');
		$view      = $this->app->input->getCmd('view');
		$task      = $this->app->input->getCmd('task');
		$articleId = $this->app->input->getInt('id', 0);

		if ($option != 'com_k2' || ($view != 'item' && $task != 'download') || !$articleId)
		{
			return true;
		}

		if ($this->isItemReleased($articleId))
		{
			return true;
		}

		$planIds = $this->getRequiredPlanIds($articleId);

		if (count($planIds))
		{
			//Check to see the current user has an active subscription plans
			$activePlans = OSMembershipHelper::getActiveMembershipPlans();

			if (!count(array_intersect($planIds, $activePlans)))
			{
				OSMembershipHelper::loadLanguage();

				$msg = JText::_('OS_MEMBERSHIP_K2_ARTICLE_ACCESS_RESITRICTED');
				$msg = str_replace('[PLAN_TITLES]', $this->getPlanTitles($planIds), $msg);
				$msg = JHtml::_('content.prepare', $msg);

				// Try to find the best redirect URL
				$redirectUrl = OSMembershipHelper::getRestrictionRedirectUrl($planIds);

				if (!$redirectUrl)
				{
					$redirectUrl = $this->params->get('redirect_url', OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register')));
				}

				if (!$redirectUrl)
				{
					$redirectUrl = JUri::root();
				}

				// Store URL of this page to redirect user back after user logged in if they have active subscription of this plan
				$session = JFactory::getSession();
				$session->set('osm_return_url', JUri::getInstance()->toString());
				$session->set('required_plan_ids', $planIds);

				$this->app->enqueueMessage($msg);
				$this->app->redirect($redirectUrl);
			}
		}
	}

	/**
	 * Hide fulltext of article to none-subscribers
	 *
	 * @param     $context
	 * @param     $row
	 * @param     $params
	 * @param int $page
	 *
	 * @return bool|void
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
	    if (!$this->canRun)
        {
            return;
        }

        if ($this->params->get('protection_method', 0) == 0)
		{
			return;
		}

		if ($this->params->get('allow_search_engine', 0) == 1 & $this->app->client->robot)
		{
			return;
		}

		if (!is_object($row))
		{
			return;
		}

		if ($context != 'com_k2.item')
		{
			return;
		}

		if ($this->isItemReleased($row->id))
		{
			return;
		}

		$planIds = $this->getRequiredPlanIds($row->id);

		if (count($planIds))
		{
			//Check to see the current user has an active subscription plans
			$activePlans = OSMembershipHelper::getActiveMembershipPlans();

			if (!count(array_intersect($planIds, $activePlans)))
			{
				$message     = OSMembershipHelper::getMessages();
				$fieldSuffix = OSMembershipHelper::getFieldSuffix();

				if (strlen($message->{'content_restricted_message' . $fieldSuffix}))
				{
					$msg = $message->{'content_restricted_message' . $fieldSuffix};
				}
				else
				{
					$msg = $message->content_restricted_message;
				}

				$msg = str_replace('[PLAN_TITLES]', $this->getPlanTitles($planIds), $msg);

				// Try to find the best redirect URL
				$redirectUrl = OSMembershipHelper::getRestrictionRedirectUrl($planIds);

				if (!$redirectUrl)
				{
					$redirectUrl = $this->params->get('redirect_url', OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register')));
				}

				if (!$redirectUrl)
				{
					$redirectUrl = JUri::root();
				}

				// Add the required plans to redirect URL
				$redirectUri = JUri::getInstance($redirectUrl);
				$redirectUri->setVar('filter_plan_ids', implode(',', $planIds));

				// Store URL of this page to redirect user back after user logged in if they have active subscription of this plan
				$session = JFactory::getSession();
				$session->set('osm_return_url', JUri::getInstance()->toString());
				$session->set('required_plan_ids', $planIds);

				$msg = str_replace('[SUBSCRIPTION_URL]', $redirectUri->toString(), $msg);

				$t[]       = $row->introtext;
				$t[]       = '<div class="text-info">' . $msg . '</div>';
				$row->text = implode(' ', $t);
			}
		}

		return true;
	}

	/**
	 * Check if the K2 items released
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	private function isItemReleased($id)
	{
		if (!$this->params->get('release_article_older_than_x_days', 0) &&
			!$this->params->get('make_new_item_free_for_x_days', 0))
		{
			return false;
		}

		$query = $this->db->getQuery(true)
			->select('*')
			->from('#__k2_items')
			->where('id = ' . (int) $id);
		$this->db->setQuery($query);
		$item = $this->db->loadObject();

		if ($item->publish_up && $item->publish_up != $this->db->getNullDate())
		{
			$publishedDate = $item->publish_up;
		}
		else
		{
			$publishedDate = $item->created;
		}

		$today         = JFactory::getDate();
		$publishedDate = JFactory::getDate($publishedDate);
		$numberDays    = $publishedDate->diff($today)->days;

		// This article is older than configured number of days, it can be accessed for free
		if ($today >= $publishedDate
			&& $this->params->get('release_item_older_than_x_days') > 0 &&
			$numberDays >= $this->params->get('release_item_older_than_x_days'))
		{
			return true;
		}


		// This article is just published and it's still free for access for the first X-days
		if ($today >= $publishedDate
			&& $this->params->get('make_new_item_free_for_x_days') > 0 &&
			$numberDays <= $this->params->get('make_new_item_free_for_x_days'))
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
	private function getRequiredPlanIds($articleId)
	{
		$query = $this->db->getQuery(true);

		$query->select('DISTINCT plan_id')
			->from('#__osmembership_k2items')
			->where('article_id = ' . $articleId)
			->where('plan_id IN (SELECT id FROM #__osmembership_plans WHERE published = 1)');
		$this->db->setQuery($query);

		try
		{
			$planIds = $this->db->loadColumn();
		}
		catch (Exception $e)
		{
			$planIds = array();
		}

		// Check categories
		$query->clear()
			->select('catid')
			->from('#__k2_items')
			->where('id = ' . (int) $articleId);
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

			if ($articleCategories = $params->get('k2_item_categories'))
			{
				$articleCategories = explode(',', $articleCategories);

				if (in_array($catId, $articleCategories))
				{
					$planIds[] = $plan->id;
				}
			}
		}

		return $planIds;
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
	 * Display k2 items which subscriber can access to in his profile
	 *
	 * @param $row
	 *
	 * @return array|void
	 */
	public function onProfileDisplay($row)
	{
		if (!$this->canRun || !$this->params->get('display_k2_items_in_profile'))
		{
			return;
		}

		ob_start();
		$this->displayK2Items();
		$form = ob_get_clean();

		return array('title' => JText::_('OSM_MY_K2_ITMES'),
		             'form'  => $form,
		);
	}

	/**
	 * Display list of accessible k2 items
	 */
	private function displayK2Items()
	{
		$query = $this->db->getQuery(true);

		$items         = array();
		$activePlanIds = OSMembershipHelper::getActiveMembershipPlans();

		// Get categories
		$query->select('id, params')
			->from('#__osmembership_plans')
			->where('id IN (' . implode(',', $activePlanIds) . ')');
		$this->db->setQuery($query);
		$plans  = $this->db->loadObjectList();
		$catIds = array();

		foreach ($plans as $plan)
		{
			$params = new Registry($plan->params);

			if ($articleCategories = $params->get('k2_item_categories'))
			{
				$catIds = array_merge($catIds, explode(',', $articleCategories));
			}
		}

		if (count($activePlanIds) > 1)
		{
			$query->clear()
				->select('a.id, a.catid, a.title, a.alias, a.hits, c.name AS category_name')
				->from('#__k2_items AS a')
				->innerJoin('#__k2_categories AS c ON a.catid = c.id')
				->innerJoin('#__osmembership_k2items AS b ON a.id = b.article_id')
				->where('b.plan_id IN (' . implode(',', $activePlanIds) . ')')
				->where('a.published = 1')
				->order('plan_id')
				->order('a.ordering');
			$this->db->setQuery($query);

			$items = array_merge($items, $this->db->loadObjectList());
		}

		if (count($catIds))
		{
			$query->clear()
				->select('a.id, a.catid, a.title, a.alias, a.hits, c.name AS category_name')
				->from('#__k2_items AS a')
				->innerJoin('#__k2_categories AS c ON a.catid = c.id')
				->where('a.catid IN (' . implode(',', $catIds) . ')')
				->where('a.published = 1')
				->order('a.ordering');
			$this->db->setQuery($query);

			$items = array_merge($items, $this->db->loadObjectList());
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
			require_once JPATH_ROOT . '/components/com_k2/helpers/route.php';

			foreach ($items as $item)
			{
				$k2itemLink = JRoute::_(K2HelperRoute::getItemRoute($item->id, $item->catid));
				?>
                <tr>
                    <td><a href="<?php echo $k2itemLink ?>"><?php echo $item->title; ?></a></td>
                    <td><?php echo $item->category_name; ?></td>
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
}