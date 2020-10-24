<?php
/**
 * Fieldvalue table
 */

class OSMembershipTableFieldvalue extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_field_value', 'id', $db);
	}
}
