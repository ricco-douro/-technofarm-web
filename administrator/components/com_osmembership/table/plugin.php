<?php
/**
 * Plugin table
 */

class OSMembershipTablePlugin extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_plugins', 'id', $db);
	}
}
