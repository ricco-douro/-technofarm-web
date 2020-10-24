<?php
/**
 * Tax table
 */

class OSMembershipTableTax extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_taxes', 'id', $db);
	}
}
