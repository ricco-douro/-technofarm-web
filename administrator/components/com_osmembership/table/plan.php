<?php
/**
 * Plan table
 *
 * @property $id
 * @property $title
 * @property $price
 * @property $setup_fee
 * @property $currency
 * @property $subscription_length
 * @property $subscription_length_unit
 * @property $lifetime_membership
 * @property $expired_date
 * @property $prorated_signup_cost
 * @property $recurring_subscription
 * @property $trial_duration
 * @property $trial_duration_unit
 * @property $trial_amount
 * @property $number_payments
 * @property $free_plan_subscription_status
 * @property $params
 */

class OSMembershipTablePlan extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_plans', 'id', $db);
	}
}
