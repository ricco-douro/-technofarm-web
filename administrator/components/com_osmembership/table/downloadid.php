<?php
/**
 * Downloadid table
 */

class OSMembershipTableDownloadid extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabaseDriver $db Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__osmembership_downloadids', 'id', $db);
	}
}
