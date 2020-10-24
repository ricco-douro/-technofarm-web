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

class plgContentMembership extends JPlugin
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
	 * @param    JForm $form The form to be altered.
	 * @param    array $data The associated data for the form.
	 *
	 * @return    boolean
	 * @since    2.1.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!$this->canRun)
		{
			return;
		}

		if ($this->app->isSite())
		{
			return;
		}

		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		$name = $form->getName();

		if ($name == 'com_content.article')
		{
			JForm::addFormPath(dirname(__FILE__) . '/form');
			$form->loadFile('membership', false);
		}

		return true;
	}

	/**
	 * @param $context
	 * @param $article
	 * @param $isNew
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
		if (!$this->canRun)
		{
			return;
		}

		if ($context != 'com_content.article')
		{
			return true;
		}

		$articleId = $article->id;
		$data      = $this->app->input->get('jform', array(), 'array');

		if ($articleId)
		{
			try
			{
				$query = $this->db->getQuery(true);
				$query->delete('#__osmembership_articles');
				$query->where('article_id = ' . $this->db->Quote($articleId));
				$this->db->setQuery($query);
				$this->db->execute();

				if (!empty($data['plan_ids']))
				{
					$query->clear()
						->insert('#__osmembership_articles')
						->columns('plan_id,article_id');

					foreach ($data['plan_ids'] as $planId)
					{
						$query->values("$planId, $articleId");
					}

					$this->db->setQuery($query);
					$this->db->execute();
				}
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());

				return false;
			}
		}
	}

	/**
	 * @param    string $context The context for the data
	 * @param    object $data    The user id
	 *
	 * @return    boolean
	 * @since    2.1.0
	 */
	public function onContentPrepareData($context, $data)
	{
		if (!$this->canRun)
		{
			return;
		}

		if ($context != 'com_content.article' || !is_object($data))
		{
			return true;
		}

		$articleId = isset($data->id) ? $data->id : 0;

		if ($articleId > 0)
		{
			$query = $this->db->getQuery(true);
			$query->select('plan_id');
			$query->from('#__osmembership_articles');
			$query->where('article_id = ' . $this->db->Quote($articleId));
			$this->db->setQuery($query);
			$results = $this->db->loadColumn();
			$data->set('plan_ids', $results);
		}
	}
}
