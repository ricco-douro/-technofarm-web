<?php
/**
 * State Table Class
 */

class OSMembershipTableState extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_states', 'id', $db);
	}
}
