<?php
/**
 * Config table
 */

class OSMembershipTableConfig extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_configs', 'id', $db);
	}
}
