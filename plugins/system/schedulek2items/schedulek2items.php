<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_osmembership/osmembership.php'))
{
	return;
}

class plgSystemScheduleK2items extends JPlugin
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
	    if (!$this->canRun)
        {
            return;
        }

        ob_start();
		$this->drawSettingForm($row);
		$form = ob_get_clean();

		return array('title' => JText::_('OSM_SCHEULE_K2ITEM_MANAGER'),
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
	    if (!$this->canRun)
        {
            return;
        }

        $scheduleK2Items   = isset($data['schedulek2item']) ? $data['schedulek2item'] : [];
		$scheduleK2ItemIds = [];
		$ordering          = 1;

		foreach ($scheduleK2Items as $scheduleK2Item)
		{
			if (empty($scheduleK2Item['item_id']) || empty($scheduleK2Item['number_days']))
			{
				continue;
			}

			/* @var OSMembershipTableScheduleContent $rowScheduleK2Item */
			$rowScheduleK2Item = JTable::getInstance('ScheduleK2Item', 'OSMembershipTable');
			$rowScheduleK2Item->bind($scheduleK2Item);
			$rowScheduleK2Item->plan_id  = $row->id;
			$rowScheduleK2Item->ordering = $ordering++;
			$rowScheduleK2Item->store();
			$scheduleK2ItemIds[] = $rowScheduleK2Item->id;
		}

		if (!$isNew)
		{
			$query = $this->db->getQuery(true);
			$query->delete('#__osmembership_schedulecontent')
				->where('plan_id = ' . $row->id);

			if (count($scheduleK2ItemIds))
			{
				$query->where('id NOT IN (' . implode(',', $scheduleK2ItemIds) . ')');
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
	    if (!$this->canRun)
        {
            return;
        }

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
	 *
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
		$task   = $this->app->input->getCmd('task');

		if ($option != 'com_k2' || ($view != 'item' && $task != 'download'))
		{
			return true;
		}

		$k2ItemId = $this->app->input->getInt('id');

		if ($this->isItemReleased($k2ItemId))
		{
			return true;
		}

		$query = $this->db->getQuery(true)
			->select('*')
			->from('#__osmembership_schedule_k2items')
			->where('item_id = ' . $k2ItemId);
		$this->db->setQuery($query);
		$rows = $this->db->loadObjectList();

		if (empty($rows))
		{
			return true;
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
			OSMembershipHelper::loadLanguage();

			throw new Exception(JText::_('OSM_SCHEDULE_ITEM_LOCKED'), 403);
		}
	}

	/**
	 * Display form allows users to change settings on subscription plan add/edit screen
	 *
	 * @param object $row
	 */
	private function drawSettingForm($row)
	{
		$numberItemsEachTime = $this->params->get('number_new_articles_each_time', 5);

		$form                       = JForm::getInstance('schedulek2item', JPATH_ROOT . '/plugins/system/schedulek2items/form/schedulek2item.xml');
		$formData['schedulek2item'] = [];

		// Load existing schedule articles for this plan
		if ($row->id)
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from('#__osmembership_schedule_k2items')
				->where('plan_id = ' . $row->id)
				->order('ordering');
			$this->db->setQuery($query);

			foreach ($this->db->loadObjectList() as $scheduleContent)
			{
				$formData['schedulek2item'][] = [
					'id'          => $scheduleContent->id,
					'item_id'     => $scheduleContent->item_id,
					'number_days' => $scheduleContent->number_days,
				];
			}
		}

		for ($i = 0; $i < $numberItemsEachTime; $i++)
		{
			$formData['schedulek2item'][] = [
				'id '         => 0,
				'item_id'     => 0,
				'number_days' => '',
			];
		}

		$form->bind($formData);

		foreach ($form->getFieldset() as $field)
		{
			echo $field->input;
		}
		?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(document).on('subform-row-add', function (event, row) {
                    jQuery(row).find('[data-k2-modal="iframe"]').magnificPopup({type: 'iframe'});
                })
            });
        </script>
		<?php
	}

	/**
	 * Display Display List of K2 items which the current subscriber can download from his subscription
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
		$query->select('a.id, a.catid, a.title, a.alias, a.hits, c.name AS category_title, b.plan_id, b.number_days')
			->from('#__k2_items AS a')
			->innerJoin('#__k2_categories AS c ON a.catid = c.id')
			->innerJoin('#__osmembership_schedule_k2items AS b ON a.id = b.item_id')
			->where('b.plan_id IN (' . implode(',', $accessiblePlanIds) . ')')
			->order('b.number_days');
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();

		if (empty($items))
		{
			return;
		}

		JLoader::register('K2HelperRoute', JPATH_ROOT . '/components/com_k2/helpers/route.php');

		$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
		$centerClass     = $bootstrapHelper->getClassMapping('center');
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
			foreach ($items as $item)
			{
				$articleLink  = JRoute::_(K2HelperRoute::getItemRoute($item->id, $item->catid));
				$subscription = $subscriptions[$item->plan_id];
				$date         = JFactory::getDate($subscription->active_from_date);
				$date->add(new DateInterval('P' . $item->number_days . 'D'));

				$itemReleased = $this->isItemReleased($item->id);
				?>
                <tr>
                    <td>
                        <i class="icon-file"></i>
						<?php
						if ($itemReleased || ($subscription->active_in_number_days >= $item->number_days))
						{
						?>
                            <a href="<?php echo $articleLink ?>" target="_blank"><?php echo $item->title; ?></a>
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

	/**
	 * Check if the K2 items released
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	private function isItemReleased($id)
	{
		if (!$this->params->get('release_article_older_than_x_days', 0))
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
		if ($today >= $publishedDate && $numberDays >= $this->params->get('release_item_older_than_x_days'))
		{
			return true;
		}

		return false;
	}
}
