<?php
/**
 * Renewoption table
 */

class OSMembershipTableRenewoption extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_renewrates', 'id', $db);
	}
}
