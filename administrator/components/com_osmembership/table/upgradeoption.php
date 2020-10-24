<?php
/**
 * Upgraderule table
 */

class OSMembershipTableUpgradeoption extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_upgraderules', 'id', $db);
	}
}
