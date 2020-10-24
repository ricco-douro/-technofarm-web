<?php
/**
 * Country table
 */

class OSMembershipTableCountry extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_countries', 'id', $db);
	}
}
