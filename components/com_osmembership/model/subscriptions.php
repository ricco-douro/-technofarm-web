<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

class OSMembershipModelSubscriptions extends MPFModelList
{
	/**
	 * Instantiate the model.
	 *
	 * @param array $config configuration data for the model
	 */
	public function __construct($config = array())
	{
		$config['ignore_request'] = true;
		$config['table'] = '#__osmembership_subscribers';

		parent::__construct($config);
	}

	/**
	 * Builds SELECT columns list for the query
	 *
	 * @param JDatabaseQuery $query
	 *
	 * @return $this
	 */
	protected function buildQueryColumns(JDatabaseQuery $query)
	{
		$query->select('tbl.*')
			->select('b.title AS plan_title, b.lifetime_membership, b.enable_renewal, b.recurring_subscription')
			->select('b.currency, b.currency_symbol');

		return $this;
	}

	/**
	 * Builds JOINS clauses for the query
	 *
	 * @param JDatabaseQuery $query
	 *
	 * @return $this
	 */
	protected function buildQueryJoins(JDatabaseQuery $query)
	{
		$query->leftJoin('#__osmembership_plans AS b  ON tbl.plan_id = b.id');

		return $this;
	}

	/**
	 * Builds a WHERE clause for the query
	 *
	 * @param JDatabaseQuery $query
	 *
	 * @return $this
	 */
	protected function buildQueryWhere(JDatabaseQuery $query)
	{
		$config = OSMembershipHelper::getConfig();

		$query->where('tbl.user_id = ' . JFactory::getUser()->id);

		if (!$config->get('show_incomplete_payment_subscriptions', 1))
		{
			$query->where('(tbl.published != 0 OR tbl.gross_amount = 0 OR tbl.payment_method LIKE "os_offline%")');
		}

		return $this;
	}
}
