<?php
/**
 * Message table
 */

class OSMembershipTableMessage extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_messages', 'id', $db);
	}
}
