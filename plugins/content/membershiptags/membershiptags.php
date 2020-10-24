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

class plgContentMembershipTags extends JPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 */
	protected $app;

	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
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
	 * Parse and display membership tags in the article
	 *
	 * @param $context
	 * @param $article
	 * @param $params
	 * @param $limitstart
	 *
	 * @return bool
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if (!$this->canRun)
		{
			return true;
		}

		if ($this->app->getName() != 'site')
		{
			return true;
		}

		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$config = OSMembershipHelper::getConfig();
		$user   = JFactory::getUser();
		$item   = OSMembershipHelperSubscription::getMembershipProfile($user->id);

		if ($item && OSMembershipHelper::isUniquePlan($item->user_id))
		{
			$planId = $item->plan_id;
		}
		else
		{
			$planId = 0;
		}

		// Form
		$rowFields = OSMembershipHelper::getProfileFields($planId);

		if ($item)
		{
			$data = OSMembershipHelper::getProfileData($item, $planId, $rowFields);
		}
		else
		{
			$data = [];
		}

		$replaces = [];

		foreach ($rowFields as $rowField)
		{
			if (isset($data[$rowField->name]))
			{
				$replaces[$rowField->name] = $data[$rowField->name];
			}
			else
			{
				$replaces[$rowField->name] = '';
			}
		}

		if ($item)
		{
			$replaces['membership_id'] = OSMembershipHelper::formatMembershipId($item, $config);
			$replaces['created_date']  = JHtml::_('date', $item->created_date, $config->date_format);

			$query = $this->db->getQuery(true);
			$query->select('username')
				->from('#__users')
				->where('id = ' . (int) $item->user_id);
			$this->db->setQuery($query);
			$replaces['username'] = $this->db->loadResult();
		}
		else
		{
			$replaces['membership_id'] = '';
			$replaces['created_date']  = '';
			$replaces['username']      = '';
		}

		foreach ($replaces as $key => $value)
		{
			$article->text = str_ireplace("[$key]", $value, $article->text);
		}

		return true;
	}
}
