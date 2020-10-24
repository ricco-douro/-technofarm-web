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

class plgSystemOSMembershipArticles extends JPlugin
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

		if ($this->canRun)
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';
		}
	}

	/**
	 * Render articles restriction setting form
	 *
	 * @param $row
	 *
	 * @return array
	 */
	public function onEditSubscriptionPlan($row)
	{
		ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('PLG_OSMEMBERSHIP_ARTICLES_RESTRICTION_SETTINGS'),
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
		$query      = $this->db->getQuery(true);
		$planId     = $row->id;
		$articleIds = $data['article_ids'];

		if (!$isNew)
		{
			$query->delete('#__osmembership_articles')->where('plan_id=' . (int) $planId);
			$this->db->setQuery($query);
			$this->db->execute();
		}

		if (!empty($articleIds))
		{
			$articleIds = explode(',', $articleIds);

			for ($i = 0; $i < count($articleIds); $i++)
			{
				$articleId = $articleIds[$i];
				$query->clear();
				$query->insert('#__osmembership_articles')
					->columns('plan_id,article_id')
					->values("$row->id,$articleId");
				$this->db->setQuery($query);
				$this->db->execute();
			}
		}

		if (isset($data['article_categories']))
		{
			$selectedCategories = $data['article_categories'];
		}
		else
		{
			$selectedCategories = array();
		}

		$params = new Registry($row->params);
		$params->set('article_categories', implode(',', $selectedCategories));
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
		//Get categories
		$categoryIds = $this->params->get('category_ids');
		$query       = $this->db->getQuery(true);
		$query->select('id, title')
			->from('#__categories')
			->where('extension = "com_content"')
			->where('published = 1');

		if (count($categoryIds) && !in_array(0, $categoryIds))
		{
			$query->where('id IN (' . implode(',', $categoryIds) . ')');
		}

		$this->db->setQuery($query);
		$categories = $this->db->loadObjectList('id');

		if (!count($categories))
		{
			return;
		}

		$categoryIds = array_keys($categories);
		$query->clear()
			->select('id, title, catid')
			->from('#__content')
			->where('`state` = 1')
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

		// Remove categories which don't have any articles
		for ($i = 0, $n = count($categories); $i < $n; $i++)
		{
			$category = $categories[$i];

			if (!isset($articles[$category->id]))
			{
				unset($categories[$i]);
			}
		}

		//Get plans articles
		$query->clear()
			->select('article_id')
			->from('#__osmembership_articles')
			->where('plan_id=' . (int) $row->id);
		$this->db->setQuery($query);
		$planArticles = $this->db->loadColumn();

		$params             = new Registry($row->params);
		$selectedCategories = explode(',', $params->get('article_categories', ''));
		?>
		<h2><?php echo JText::_('OSM_ARTICLES_CATEGORIES'); ?></h2>
		<p class="text-info"><?php echo JText::_('OSM_ARTICLES_CATEGORIES_EXPLAIN'); ?></p>
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
								name="article_categories[]"/> <strong><?php echo $category->title; ?></strong>
						</label>
					</td>
				</tr>
				<?php
			}
			?>
		</table>

		<h2><?php echo JText::_('OSM_ARTICLES'); ?></h2>
		<p class="text-info"><?php echo JText::_('OSM_ARTICLES_EXPLAIN'); ?></p>
		<table class="admintable adminform" style="width: 100%;">
			<tr>
				<td>
					<div class="accordion" id="accordion2">
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
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"
									   href="#collapse<?php echo $category->id; ?>" style="display: inline;">
										<?php echo $category->title; ?>
									</a>
									<label class="checkbox"> <input type="checkbox" value="<?php echo $category->id ?>"
									                                id="<?php echo $category->id ?>" class="checkAll"
									                                name=""> <strong>#</strong> </label>
								</div>
								<div id="collapse<?php echo $category->id; ?>"
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
													id="article_<?php echo $article->id; ?>" name="article_id[]"
													class="checkall_<?php echo $category->id ?> article_checkbox"/>
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
				<input type="hidden" value="<?php echo implode(',', $planArticles) ?>" name="article_ids"
				       class="article_ids"/>
				<?php
			}
			else
			{
				?>
				<input type="hidden" value="" name="article_ids" class="article_ids"/>
				<?php
			}
			?>
		</table>
		<script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    $(".checkAll").click(function () {
                        var ID = $(this).attr("id");
                        if ($(this).is(':checked')) {
                            $('.checkall_' + ID).attr("checked", true);
                        }
                        else {
                            $('.checkall_' + ID).attr("checked", false);
                        }
                        $('.article_ids').val(getArticleIds());
                    });


                    $(".article_checkbox").click(function () {
                        $('.article_ids').val(getArticleIds());
                    });

                    var articleIdArray = new Array;
                    getArticleIds = (function () {
                        articleIdArray = [];
                        $('.article_checkbox:checked').each(function () {
                            articleIdArray.push($(this).val());
                        });
                        console.log(articleIdArray);
                        return articleIdArray;
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

		if ($this->params->get('protection_method', 0) == 1)
		{
			return true;
		}

		if ($this->params->get('allow_search_engine', 1) == 0 && $this->app->client->robot)
		{
			return true;
		}

		$option = $this->app->input->getCmd('option');
		$view   = $this->app->input->getCmd('view');

		if ($option != 'com_content' || $view != 'article')
		{
			return true;
		}

		$articleId = $this->app->input->getInt('id');

		if ($this->isArticleReleased($articleId))
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

				$msg = JText::_('OS_MEMBERSHIP_ARTICLE_ACCESS_RESITRICTED');
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
			return true;
		}

		if ($this->app->isAdmin())
		{
			return true;
		}

		if ($this->params->get('protection_method', 0) == 0)
		{
			return;
		}

		if ($this->params->get('allow_search_engine', 0) == 1 && $this->app->client->robot)
		{
			return;
		}

		if (!is_object($row))
		{
			return;
		}

		if ($this->isArticleReleased($row->id))
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

				$redirectUrl = $this->findRedirectUrl($planIds);


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

				$row->params->set('show_readmore', 0);
			}
		}

		return true;
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
		if (!$this->params->get('display_articles_in_profile'))
		{
			return;
		}

		ob_start();
		$this->displayArticles($row);

		$form = ob_get_clean();

		return array('title' => JText::_('OSM_MY_ARTICLES'),
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
	private function isArticleReleased($articleId)
	{
		if (!$this->params->get('release_article_older_than_x_days', 0))
		{
			return false;
		}

		$query = $this->db->getQuery(true)
			->select('*')
			->from('#__content')
			->where('id = ' . (int) $articleId);
		$this->db->setQuery($query);
		$article = $this->db->loadObject();

		if ($article->publish_up && $article->publish_up != $this->db->getNullDate())
		{
			$publishedDate = $article->publish_up;
		}
		else
		{
			$publishedDate = $article->created;
		}

		$today         = JFactory::getDate();
		$publishedDate = JFactory::getDate($publishedDate);
		$numberDays    = $publishedDate->diff($today)->days;

		// This article is older than configured number of days, it can be accessed for free
		if ($today >= $publishedDate && $numberDays >= $this->params->get('release_article_older_than_x_days'))
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
			->from('#__osmembership_articles')
			->where('article_id = ' . (int) $articleId);
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
			->from('#__content')
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

			if ($articleCategories = $params->get('article_categories'))
			{
				$articleCategories = explode(',', $articleCategories);

				if (in_array($catId, $articleCategories))
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
	private function displayArticles($row)
	{
		$query         = $this->db->getQuery(true);
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

			if ($articleCategories = $params->get('article_categories'))
			{
				$catIds = array_merge($catIds, explode(',', $articleCategories));
			}
		}

		$items = array();

		if (count($activePlanIds) > 1)
		{
			$query->clear()
				->select('a.id, a.catid, a.title, a.alias, a.hits, c.title AS category_title')
				->from('#__content AS a')
				->innerJoin('#__categories AS c ON a.catid = c.id')
				->innerJoin('#__osmembership_articles AS b ON a.id = b.article_id')
				->where('b.plan_id IN (' . implode(',', $activePlanIds) . ')')
				->where('a.state = 1')
				->order('plan_id')
				->order('a.ordering');
			$this->db->setQuery($query);

			$items = array_merge($items, $this->db->loadObjectList());
		}

		if (count($catIds) > 0)
		{
			$query->clear()
				->select('a.id, a.catid, a.title, a.alias, a.hits, c.title AS category_title')
				->from('#__content AS a')
				->innerJoin('#__categories AS c ON a.catid = c.id')
				->where('a.catid IN (' . implode(',', $catIds) . ')')
				->where('a.state = 1')
				->order('a.ordering');

			$items = array_merge($items, $this->db->loadObjectList());
		}

		if (empty($items))
		{
			return;
		}

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$centerClass     = $bootstrapHelper->getClassMapping('center');
		?>
		<table class="adminlist <?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
			<thead>
			<tr>
				<th class="title"><?php echo JText::_('OSM_TITLE'); ?></th>
				<th class="title"><?php echo JText::_('OSM_CATEGORY'); ?></th>
				<th class="<?php echo $centerClass; ?>"><?php echo JText::_('OSM_HITS'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			JLoader::register('ContentHelperRoute', JPATH_ROOT . '/components/com_content/helpers/route.php');

			$displayedArticleIds = array();

			foreach ($items as $item)
			{
				if (in_array($item->id, $displayedArticleIds))
				{
					continue;
				}

				$displayedArticleIds[] = $item->id;

				$articleLink = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
				?>
				<tr>
					<td><a href="<?php echo $articleLink ?>"><?php echo $item->title; ?></a></td>
					<td><?php echo $item->category_title; ?></td>
					<td class="<?php echo $centerClass; ?>">
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
