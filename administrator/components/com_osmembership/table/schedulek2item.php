<?php
/**
 * ScheduleK2Item table
 *
 * @property $id
 * @property $plan_id
 * @property $item_id
 * @property $number_days
 * @property $ordering
 */

class OSMembershipTableScheduleK2Item extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_schedule_k2items', 'id', $db);
	}
}
