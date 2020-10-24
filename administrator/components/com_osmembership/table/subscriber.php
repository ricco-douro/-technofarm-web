<?php
/**
 * Subscriber table
 *
 * @property $id
 * @property $plan_id
 * @property $user_id
 * @property $coupon_id
 * @property $avatar
 * @property $first_name
 * @property $last_name
 * @property $organization
 * @property $address
 * @property $address2
 * @property $city
 * @property $state
 * @property $zip
 * @property $country
 * @property $phone
 * @property $fax
 * @property $email
 * @property $comment
 * @property $created_date
 * @property $payment_date
 * @property $from_date
 * @property $to_date
 * @property $invoice_number
 * @property $is_profile
 * @property $profile_id
 * @property $membership_id
 * @property $act
 * @property $published
 * @property $setup_fee
 * @property $tax_rate
 * @property $amount
 * @property $tax_amount
 * @property $discount_amount
 * @property $gross_amount
 * @property $payment_processing_fee
 * @property $payment_method
 * @property $transaction_id
 * @property $language
 * @property $plan_main_record
 * @property $plan_subscription_from_date
 * @property $plan_subscription_to_date
 * @property $plan_subscription_status
 * @property $subscription_id
 * @property $upgrade_option_id
 * @property $renew_option_id
 * @property $payment_currency
 * @property $trial_payment_amount
 * @property $payment_amount
 * @property $params
 * @property $group_admin_id
 * @property $first_reminder_sent
 * @property $second_reminder_sent
 * @property $third_reminder_sent
 * @property $is_free_trial
 * @property $receiver_email
 * @property $payment_made
 * @property $gateway_customer_id
 * @property $auto_subscribe_processed
 * @property $parent_id
 * @property $refunded
 */

class OSMembershipTableSubscriber extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_subscribers', 'id', $db);
	}
}
