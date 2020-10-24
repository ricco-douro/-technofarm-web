<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;


class plgSystemScheduleContent extends JPlugin
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
	 * Render setting form
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

		return array('title' => JText::_('OSM_SCHEULE_CONTENT_MANAGER'),
		             'form'  => $form,
		);
	}

	/**
	 * Store setting into database, in this case, use params field of plans table
	 *
	 * @param OSMembershipTablePlan $row
	 * @param bool                  $isNew true if create new plan, false if edit
	 */
	public function onAfterSaveSubscriptionPlan($context, $row, $data, $isNew)
	{
		$scheduleContents   = isset($data['schedulecontent']) ? $data['schedulecontent'] : [];
		$scheduleContentIds = [];
		$ordering           = 1;

		foreach ($scheduleContents as $scheduleContent)
		{
			if (empty($scheduleContent['article_id']) || (empty($scheduleContent['number_days']) && empty($scheduleContent['release_date'])))
			{
				continue;
			}

			/* @var OSMembershipTableScheduleContent $rowScheduleContent */
			$rowScheduleContent = JTable::getInstance('ScheduleContent', 'OSMembershipTable');
			$rowScheduleContent->bind($scheduleContent);
			$rowScheduleContent->plan_id  = $row->id;
			$rowScheduleContent->ordering = $ordering++;
			$rowScheduleContent->store();
			$scheduleContentIds[] = $rowScheduleContent->id;
		}

		if (!$isNew)
		{
			$query = $this->db->getQuery(true);
			$query->delete('#__osmembership_schedulecontent')
				->where('plan_id = ' . $row->id);

			if (count($scheduleContentIds))
			{
				$query->where('id NOT IN (' . implode(',', $scheduleContentIds) . ')');
			}

			$this->db->setQuery($query)
				->execute();
		}
	}

	/**
	 * Render setting form
	 *
	 * @param JTable $row
	 *
	 * @return array
	 */
	public function onProfileDisplay($row)
	{
		ob_start();
		$this->drawScheduleContent($row);
		$form = ob_get_contents();
		ob_end_clean();

		return array('title' => JText::_('OSM_MY_SCHEDULE_CONTENT'),
		             'form'  => $form,
		);
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
            return;
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

		if ($option != 'com_content' || $view != 'article')
		{
			return true;
		}

		$query     = $this->db->getQuery(true);
		$articleId = $this->app->input->getInt('id');

		$query->select('*')
			->from('#__osmembership_schedulecontent')
			->where('article_id = ' . $articleId);
		$this->db->setQuery($query);
		$rows = $this->db->loadObjectList();

		if (empty($rows))
		{
			return;
		}

		$releaseArticleOlderThanXDays = (int) $this->params->get('release_article_older_than_x_days', 0);

		if ($releaseArticleOlderThanXDays > 0)
		{
			$query->select('*')
				->from('#__content')
				->where('id = ' . $articleId);
			$this->db->setQuery($query);
			$rowArticle = $this->db->loadObject();

			if ($rowArticle->publish_up && $rowArticle->publish_up != $this->db->getNullDate())
			{
				$publishedDate = $rowArticle->publish_up;
			}
			else
			{
				$publishedDate = $rowArticle->created;
			}

			$today         = JFactory::getDate();
			$publishedDate = JFactory::getDate($publishedDate);
			$numberDays    = $publishedDate->diff($today)->days;

			// This article is older than configured number of days, it can be accessed for free
			if ($today >= $publishedDate && $numberDays >= $releaseArticleOlderThanXDays)
			{
				return true;
			}
		}

		// Require library + register autoloader
		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$canAccess     = false;
		$subscriptions = OSMembershipHelperSubscription::getUserSubscriptionsInfo();

		foreach ($rows as $row)
		{
			if (isset($subscriptions[$row->plan_id]))
			{
				$subscription = $subscriptions[$row->plan_id];

				if ($subscription->active_in_number_days >= $row->number_days)
				{
					$canAccess = true;
					break;
				}
			}
		}

		if (!$canAccess)
		{
			if (!$user->id)
			{
				// Redirect user to login page
				$this->app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString())));
			}
			else
			{
				OSMembershipHelper::loadLanguage();

				throw new Exception(JText::_('OSM_SCHEDULE_CONTENT_LOCKED'), 403);
			}
		}
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$numberArticlesEachTime      = $this->params->get('number_new_articles_each_time', 10);
		$form                        = JForm::getInstance('schedulecontent', JPATH_ROOT . '/plugins/system/schedulecontent/form/schedulecontent.xml');
		$formData['schedulecontent'] = [];

		// Load existing schedule articles for this plan
		if ($row->id)
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from('#__osmembership_schedulecontent')
				->where('plan_id = ' . $row->id)
				->order('ordering');
			$this->db->setQuery($query);

			foreach ($this->db->loadObjectList() as $scheduleContent)
			{
				$formData['schedulecontent'][] = [
					'id'           => $scheduleContent->id,
					'article_id'   => $scheduleContent->article_id,
					'number_days'  => $scheduleContent->number_days,
					'release_date' => $scheduleContent->release_date,
				];
			}
		}

		for ($i = 0; $i < $numberArticlesEachTime; $i++)
		{
			$formData['schedulecontent'][] = [
				'id '          => 0,
				'article_id'   => 0,
				'number_days'  => '',
				'release_date' => ''
			];
		}

		$form->bind($formData);

		foreach ($form->getFieldset() as $field)
		{
			echo $field->input;
		}
	}

	/**
	 * Display Display List of Documents which the current subscriber can download from his subscription
	 *
	 * @param object $row
	 */
	private function drawScheduleContent($row)
	{
		$config = OSMembershipHelper::getConfig();

		$subscriptions = OSMembershipHelperSubscription::getUserSubscriptionsInfo();

		if (empty($subscriptions))
		{
			return;
		}

		$accessiblePlanIds = array_keys($subscriptions);

		$query = $this->db->getQuery(true);
		$query->select('a.id, a.catid, a.title, a.alias, a.hits, a.created, a.publish_up, c.title AS category_title, b.plan_id, b.number_days')
			->from('#__content AS a')
			->innerJoin('#__categories AS c ON a.catid = c.id')
			->innerJoin('#__osmembership_schedulecontent AS b ON a.id = b.article_id')
			->where('b.plan_id IN (' . implode(',', $accessiblePlanIds) . ')')
			->where('a.state = 1')
			->order('plan_id')
			->order('b.number_days');
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();

		if (empty($items))
		{
			return;
		}

		$releaseArticleOlderThanXDays = (int) $this->params->get('release_article_older_than_x_days', 0);

		JLoader::register('ContentHelperRoute', JPATH_ROOT . '/components/com_content/helpers/route.php');

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
        $centerClass = $bootstrapHelper->getClassMapping('center');
		?>
        <table class="adminlist <?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>" id="adminForm">
            <thead>
            <tr>
                <th class="title"><?php echo JText::_('OSM_TITLE'); ?></th>
                <th class="title"><?php echo JText::_('OSM_CATEGORY'); ?></th>
                <th class="title <?php echo $centerClass; ?>"><?php echo JText::_('OSM_ACCESSIBLE_ON'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			$openArticle = $this->params->get('open_article');

			foreach ($items as $item)
			{
				$articleLink  = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
				$subscription = $subscriptions[$item->plan_id];
				$date         = JFactory::getDate($subscription->active_from_date);
				$date->add(new DateInterval('P' . $item->number_days . 'D'));

				$articleReleased = false;

				if ($releaseArticleOlderThanXDays > 0)
				{
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
					if ($today >= $publishedDate && $numberDays >= $releaseArticleOlderThanXDays)
					{
						$articleReleased = true;
					}
				}
				?>
                <tr>
                    <td>
                        <i class="icon-file"></i>
						<?php
						if ($articleReleased || ($subscription->active_in_number_days >= $item->number_days))
						{
							?>
                            <a href="<?php echo $articleLink ?>"<?php echo($openArticle ? '' : ' target="_blank"'); ?>><?php echo $item->title; ?></a>
							<?php
						}
						else
						{
							echo $item->title . ' <span class="label">' . JText::_('OSM_LOCKED') . '</span>';
						}
						?>
                    </td>
                    <td><?php echo $item->category_title; ?></td>
                    <td class="<?php echo $centerClass; ?>">
						<?php echo JHtml::_('date', $date->format('Y-m-d H:i:s'), $config->date_format); ?>
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
