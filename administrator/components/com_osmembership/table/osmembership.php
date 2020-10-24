<?php
/**
 * Plan table
 */

class PlanOsMembership extends JTable
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

/**
 * Subscriber table
 */
class SubscriberOSMembership extends JTable
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

/**
 * Fieldvalue table
 */
class FieldValueOsMembership extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_field_value', 'id', $db);
	}
}
