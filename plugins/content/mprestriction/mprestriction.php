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

class plgContentMPRestriction extends JPlugin
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
	 * @param     $context
	 * @param     $row
	 * @param     $params
	 * @param     $page
	 *
	 * @return bool
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if (!$this->canRun)
		{
			return;
		}

		if ($this->app->getName() != 'site'
			|| strpos($row->text, 'mprestriction') === false)
		{
			return true;
		}

		// Search for this tag in the content
		$regex     = '#{mprestriction ids="(.*?)"}(.*?){/mprestriction}#s';
		$row->text = preg_replace_callback($regex, array(&$this, 'processRestriction'), $row->text);

		return true;
	}

	public function processRestriction($matches)
	{
		// Require library + register autoloader
		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$document = JFactory::getDocument();
		$rootUri  = JUri::base(true);

		$document->addStylesheet($rootUri . '/media/com_osmembership/assets/css/style.css', 'text/css', null, null);

		$customCssFile = JPATH_ROOT . '/media/com_osmembership/assets/css/custom.css';

		if (file_exists($customCssFile) && filesize($customCssFile) > 0)
		{
			$document->addStylesheet($rootUri . '/media/com_osmembership/assets/css/custom.css', 'text/css', null, null);
		}

		$message     = OSMembershipHelper::getMessages();
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();

		if (strlen($message->{'content_restricted_message' . $fieldSuffix}))
		{
			$restrictedText = $message->{'content_restricted_message' . $fieldSuffix};
		}
		else
		{
			$restrictedText = $message->content_restricted_message;
		}

		$requiredPlanIds = $matches[1];
		$protectedText   = $matches[2];

		// Super admin should see all text
		$user = JFactory::getUser();

		if ($user->authorise('core.admin'))
		{
			return $protectedText;
		}

		$activePlanIds = OSMembershipHelper::getActiveMembershipPlans();

		if (substr($requiredPlanIds, 0, 1) == '!')
		{
			$requiredPlanIds = substr($requiredPlanIds, 1);
			if ($requiredPlanIds == '*')
			{
				if (count($activePlanIds) == 1 && $activePlanIds[0] == 0)
				{
					return $protectedText;
				}
			}
			else
			{
				$requiredPlanIds = explode(',', $requiredPlanIds);
				if (!count(array_intersect($requiredPlanIds, $activePlanIds)))
				{
					return $protectedText;
				}
				else
				{
					return '';
				}
			}
		}
		else
		{
			if ($requiredPlanIds == '*')
			{
				$query = $this->db->getQuery(true);
				$query->select('id')
					->from('#__osmembership_plans')
					->where('published = 1')
					->order('ordering');
				$planIds = $this->db->loadColumn();
			}
			else
			{
				$planIds = explode(',', $requiredPlanIds);
			}

			$redirectUrl = $this->findRedirectUrl($planIds);

			// Add the required plans to redirect URL
			$redirectUri = JUri::getInstance($redirectUrl);
			$redirectUri->setVar('filter_plan_ids', implode(',', $planIds));

			// Store URL of this page to redirect user back after user logged in if they have active subscription of this plan
			$session = JFactory::getSession();
			$session->set('osm_return_url', JUri::getInstance()->toString());
			$session->set('required_plan_ids', $planIds);

			$restrictedText = str_replace('[SUBSCRIPTION_URL]', $redirectUri->toString(), $restrictedText);
			$restrictedText = JHtml::_('content.prepare', $restrictedText);

			if (count($activePlanIds) == 1 && $activePlanIds[0] == 0)
			{
				return '<div id="restricted_info">' . $restrictedText . '</div>';
			}
			elseif ($requiredPlanIds == '*')
			{
				return $protectedText;
			}
			else
			{
				$requiredPlanIds = explode(',', $requiredPlanIds);
				if (count(array_intersect($requiredPlanIds, $activePlanIds)))
				{
					return $protectedText;
				}
				else
				{
					return '<div id="restricted_info">' . $restrictedText . '</div>';
				}
			}
		}
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
}
