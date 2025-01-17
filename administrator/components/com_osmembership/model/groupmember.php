<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

/**
 * Membership Pro Component Groupmember Model
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipModelGroupmember extends MPFModelAdmin
{
	/**
	 * Instantiate the model.
	 *
	 * @param array $config configuration data for the model
	 */
	public function __construct($config = array())
	{
		$config['table'] = '#__osmembership_subscribers';
		parent::__construct($config);
	}

	/**
	 * Initialize data for group member
	 *
	 *
	 * @return JTable
	 */
	public function getData()
	{
		$row = $this->getTable('Subscriber');
		if (count($this->state->cid))
		{
			$this->state->id = (int) $this->state->cid[0];
		}
		if ($this->state->id)
		{
			$row->load($this->state->id);
		}

		return $row;
	}

	/**
	 * Override store function to perform specific saving
	 * @see OSModel::store()
	 *
	 * @param MPFInput $input
	 * @param array    $ignore
	 */
	public function store($input, $ignore = array())
	{
		$config    = OSMembershipHelper::getConfig();
		$db        = $this->getDbo();
		$query     = $db->getQuery(true);
		$row       = $this->getTable('Subscriber');
		$isNew     = true;
		$published = 0;

		// Convert datetime fields value to format expected by database
		$dateFormat     = str_replace('%', '', $config->get('date_field_format', '%Y-%m-%d'));
		$dateTimeFormat = $dateFormat . ' H:i:s';
		$dateTimeFields = [
			'created_date',
			'from_date',
			'to_date',
		];

		foreach ($dateTimeFields as $field)
		{
			$dateValue = $input->getString($field);

			if (!$dateValue)
			{
				continue;
			}

			try
			{
				$date = DateTime::createFromFormat($dateTimeFormat, $dateValue);

				if ($date !== false)
				{
					$input->set($field, $date->format('Y-m-d H:i:s'));
				}
			}
			catch (Exception $e)
			{
				// Do nothing
			}
		}

		$data = $input->getData();

		if (!$data['id'] && $data['username'] && $data['password'] && empty($data['user_id']))
		{
			//Store this account into the system and get the username
			jimport('joomla.user.helper');
			$params      = JComponentHelper::getParams('com_users');
			$newUserType = $params->get('new_usertype', 2);

			$data['groups']   = array();
			$data['groups'][] = $newUserType;
			$data['block']    = 0;
			$data['name']     = $data['first_name'] . ' ' . $data['last_name'];
			$data['email1']   = $data['email2'] = $data['email'];
			$user             = new JUser();
			$user->bind($data);
			if (!$user->save())
			{
				throw new Exception($user->getError());
			}
			$data['user_id'] = $user->id;
		}

		if ($data['id'])
		{
			$isNew = false;
			$row->load($data['id']);
			$published = $row->published;
		}

		$row->bind($data);

		if ($isNew)
		{
			$row->user_id      = (int) $row->user_id;
			$row->published    = 1;
			$row->created_date = gmdate('Y-m-d H:i:s');
			$row->from_date    = gmdate('Y-m-d H:i:s');
			$row->is_profile   = 1;

			// Calculate to_date
			$query->select('MAX(to_date)')
				->from('#__osmembership_subscribers')
				->where('user_id=' . $row->group_admin_id . ' AND plan_id=' . $row->plan_id . ' AND published = 1');
			$db->setQuery($query);
			$row->to_date = $db->loadResult();
		}
		elseif ($published == 2)
		{
			// Check subscription end date, if end date > today date, setup subscription to active
			$date      = JFactory::getDate($row->to_date);
			$todayDate = JFactory::getDate();
			$diff      = $todayDate->diff($date);

			if ($diff->days > 1)
			{
				$row->published = 1;
			}
		}

		if (!$row->store())
		{
			throw new Exception($row->getError());
		}

		if ($isNew)
		{
			$row->profile_id = $row->id;
			$row->store();
		}

		$rowFields = OSMembershipHelper::getProfileFields($row->plan_id, false);
		$form      = new MPFForm($rowFields);
		$form->storeData($row->id, $data);

		if ($isNew && $row->user_id)
		{
			$app = JFactory::getApplication();
			JPluginHelper::importPlugin('osmembership');
			$app->triggerEvent('onAfterStoreSubscription', array($row));

			if ($isNew || ($published == 1 && $row->published == 1))
			{
				$app->triggerEvent('onMembershipActive', array($row));
			}
		}

		return true;
	}

	/**
	 * Delete the selected group members
	 *
	 * @param array $cid
	 */
	public function delete($cid = array())
	{
		JPluginHelper::importPlugin('osmembership');
		$app = JFactory::getApplication();

		$row   = $this->getTable('Subscriber');
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		foreach ($cid as $id)
		{
			$row->load($id);
			$query->clear()
				->delete('#__osmembership_field_value')
				->where('subscriber_id = ' . $id);
			$db->setQuery($query);
			$db->execute();

			$app->triggerEvent('onMembershipExpire', array($row));

			// Delete the subscription record
			$row->delete();
		}
	}

	/**
	 * Delete custom fields data related to selected subscribers, trigger event before actual delete the data
	 *
	 * @param array $cid
	 */
	protected function beforeDelete($cid)
	{
		if (count($cid))
		{
			//
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__osmembership_field_value')
				->where('subscriber_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
			JPluginHelper::importPlugin('osmembership');
			$app = JFactory::getApplication();
			$row = $this->getTable('Subscriber');

			foreach ($cid as $id)
			{
				$row->load($id);
				$app->triggerEvent('onMembershipExpire', array($row));
			}
		}
	}

	/**
	 * Pre-process before publishing the actual record
	 *
	 * @param array $cid
	 * @param int   $state
	 *
	 * @throws Exception
	 */
	protected function beforePublish($cid, $state)
	{
		if ($state == 1)
		{
			$app = JFactory::getApplication();
			$row = $this->getTable('Subscriber');
			JPluginHelper::importPlugin('osmembership');

			foreach ($cid as $id)
			{
				$row->load($id);

				if (!$row->published)
				{
					$app->triggerEvent('onMembershipActive', array($row));
					OSMembershipHelper::sendMembershipApprovedEmail($row);
				}
			}
		}

		parent::publish($cid, $state);

	}

	/**
	 * Get JTable object for the model
	 *
	 * @param string $name
	 *
	 * @return JTable
	 */
	public function getTable($name = 'Subscriber')
	{

		return parent::getTable($name);
	}
}
