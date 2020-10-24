<?php
/**
 * Renewaldiscount table
 */

class OSMembershipTableRenewaldiscount extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_renewaldiscounts', 'id', $db);
	}
}
