<?php
/**
 * Category table
 */

class OSMembershipTableCategory extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_categories', 'id', $db);
	}
}
