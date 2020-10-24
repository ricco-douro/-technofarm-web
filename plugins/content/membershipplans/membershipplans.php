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

class plgContentMembershipPlans extends JPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 */
	protected $app;

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
	 * Parse article and display plans if configured
	 *
	 * @param $context
	 * @param $article
	 * @param $params
	 * @param $limitstart
	 *
	 * @return true
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if (!$this->canRun)
		{
			return true;
		}

		if ($this->app->getName() != 'site'
			|| strpos($article->text, 'membershipplans') === false)
		{
			return true;
		}

		$regex         = '#{membershipplans ids="(.*?)"}#s';
		$article->text = preg_replace_callback($regex, array(&$this, 'displayPlans'), $article->text);

		return true;
	}

	/**
	 * Replace callback function
	 *
	 * @param $matches
	 *
	 * @return string
	 * @throws Exception
	 */
	public function displayPlans($matches)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_osmembership/loader.php';

		$planIds = $matches[1];
		$layout  = $this->params->get('layout_type', 'default');
		OSMembershipHelper::loadLanguage();
		$request = array('option' => 'com_osmembership', 'view' => 'plans', 'layout' => $layout, 'filter_plan_ids' => $planIds, 'limit' => 0, 'hmvc_call' => 1, 'Itemid' => OSMembershipHelper::getItemid());
		$input   = new MPFInput($request);
		$config  = array(
			'default_controller_class' => 'OSMembershipController',
			'default_view'             => 'plans',
			'class_prefix'             => 'OSMembership',
			'language_prefix'          => 'OSM',
			'remember_states'          => false,
			'ignore_request'           => false,
		);

		ob_start();

		//Initialize the controller, execute the task
		MPFController::getInstance('com_osmembership', $input, $config)
			->execute();

		return ob_get_clean();
	}
}
