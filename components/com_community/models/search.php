<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.utilities.date');
jimport('joomla.html.pagination');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelSearch extends JCCModel
{
	var $_data = null;
	var $_profile;
	var $_pagination;
	var $_total;


	public function __construct(){
		parent::__construct();
 	 	$mainframe = JFactory::getApplication();
		$jinput 	= $mainframe->input;
        $config = CFactory::getConfig();
 	 	// Get pagination request variables
 	 	$limit		= ($config->get('pagination') == 0) ? 5 : $config->get('pagination');
	    $limitstart = $jinput->request->get('limitstart', 0, 'INT');

	    if(empty($limitstart))
 	 	{
 	 		$limitstart = $jinput->get('limitstart', 0, 'uint');
 	 	}

 	 	// In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);
	}

	public function &getFiltered($wheres = array())
	{
		$db			= $this->getDBO();

		$wheres[] = $db->quoteName('block').' = '.$db->Quote('0');

		$query = "SELECT *"
			. ' FROM '.$db->quoteName('#__users')
			. ' WHERE ' . implode( ' AND ', $wheres )
			. ' ORDER BY '.$db->quoteName('id').' DESC ';

		$db->setQuery( $query );

		try {
			$result = $db->loadObjectList();
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
		return $result;
	}


	/**
	 * get pagination data
	 */
	public function getPagination()
	{
		return $this->_pagination;
	}

	/**
	 * get total data
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Search for people
	 * @param query	string	people's name to seach for
	 */
	public function searchPeople($query , $avatarOnly = '', $friendId = 0 , $radius = 0)
	{
		$db			= $this->getDBO();
		$config		= CFactory::getConfig();
		$filter		= array();
		$data		= array();
		$isEmail    = false;

		//select only non empty field
		foreach($query as $key => $value)
		{
			if(!empty($query[$key]))
			{
				$data[$key]=$value;
			}
		}

		// build where condition
		$filterField	= array();
		if(isset($data['q']))
		{
			$value			= $data['q'];

			//CFactory::load( 'helpers' , 'validate' );
			if( CValidateHelper::email( CStringHelper::trim( $value ) ) )
			{
			    $isEmail    = true;
				if($config->get( 'privacy_search_email') != 2 )
				{
					$filter[]	= $db->quoteName('email').'=' . $db->Quote( $value );
				}
			}
			else
			{
				$nameType	= $db->quoteName( $config->get( 'displayname' ) );
				$filter[]	= 'UCASE(' . $nameType . ') LIKE UCASE(' . $db->Quote( '%' . $value . '%' ) . ')';
			}
		}

        //if there is radius involved, we should only get the users within the id
        if($radius){
            $userArr = $this->searchByRadius($radius);
            foreach($userArr
                    as $key=>$user){
                $userArr[$key] = $db->quote($user) ;
            }

            $usersInRadius = implode(',', $userArr);
			if(!count($userArr)){
				$usersInRadius = 0;
			}
            $filter[] .= $db->quoteName('id').' IN ('.$usersInRadius.')';
        }

        // if using AJAX, the result not respected page limit
		$limit			= (isset($query['option']) && !empty($query['option'])) ? $this->getState('limit') : false;
		$limitstart		= $this->getState('limitstart');

		$finalResult	= array();
		$total			= 0;
		if(count($filter)> 0 || count($filterField > 0))
		{
			// Perform the simple search
			$basicResult = null;

			if(!empty($filter) && count($filter)>0)
			{
				if($friendId!=0){

				    $query = 'SELECT b.'.$db->quoteName('friends')
						    .' FROM '.$db->quoteName('#__community_users').' b';
				    $query .= ' WHERE b.'.$db->quoteName('userid').' = '.$db->Quote($friendId);
				    $db->setQuery( $query );
				    $friendListId = $db->loadResult();

				    $friendListQuery = ' AND '.$db->quoteName('id').' IN ('.$friendListId.')';

				}

				$filterquery = '';
				if( !$config->get( 'privacy_show_admins') )
				{
					$userModel		= CFactory::getModel( 'User' );
					$tmpAdmins		= $userModel->getSuperAdmins();

					$admins         = array();

					$filterquery  .= ' AND b.'.$db->quoteName('id').' NOT IN(';
					for( $i = 0; $i < count($tmpAdmins);$i++ )
					{
						$admin  = $tmpAdmins[ $i ];
						$filterquery  .= $db->Quote( $admin->id );
						$filterquery  .= $i < count($tmpAdmins) - 1 ? ',' : '';
					}
					$filterquery  .= ')';
				}

				$query = 'SELECT distinct b.'.$db->quoteName('id')
						.' FROM '.$db->quoteName('#__users').' b';
				$query	.= ' INNER JOIN '.$db->quoteName('#__community_users').' AS c ON b.'.$db->quoteName('id').'=c.'.$db->quoteName('userid');

				if(!empty($friendListQuery)){
                    $query.= $friendListQuery;
				}

				// @rule: Only fetch users that is configured to be searched via email.
				if( $isEmail && $config->get( 'privacy_search_email') == 1 )
				{
					$query  .= ' AND c.'.$db->quoteName('search_email').'=' . $db->Quote( 0 );
				}

				if( $avatarOnly )
				{
					$query	.= ' AND c.'.$db->quoteName('thumb').' != ' . $db->Quote( '' );
					$query	.= ' AND c.'.$db->quoteName('thumb').' != ' . $db->Quote( 'components/com_community/assets/default_thumb.jpg' );
				}

				$query .= ' WHERE b.'.$db->quoteName('block').' = '.$db->Quote('0').' AND '.implode(' AND ',$filter). $filterquery;

				$queryCnt	= 'SELECT COUNT(1) FROM ('.$query.') AS z';
				$db->setQuery($queryCnt);
				$total	= $db->loadResult();

				if ($limit) $query .=  " LIMIT " . $limitstart . "," . $limit;

				$db->setQuery( $query );
				try {
					$finalResult = $db->loadColumn();
				} catch (Exception $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}

			// Appy pagination
			if (empty($this->_pagination) && $limit)
			{
		 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
		 	}
		}

		if(empty($finalResult)) {
            $finalResult = array(0);
        }

		$id = implode(",",$finalResult);
		$where = array($db->quoteName('id')." IN (".$id.")");
		$result = $this->getFiltered($where);

		return $result;
	}

	// @params $field, array with key[fieldcode] = value
	// just use 1 field for now
	public function searchByFieldCode($field)
	{
		CError::assert($field , '', '!empty', __FILE__ , __LINE__ );

		$db			= $this->getDBO();

		foreach($field as $key=>$val){
			if($key == 'FIELD_COUNTRY'){
				$val = str_replace(' ','',$val);
                if(strpos($val,'COM_COMMUNITY_LANG_NAME_') === false){
                    $field[$key]='COM_COMMUNITY_LANG_NAME_'.strtoupper ($val);
                }
			}
		}
        
		$keys = array_keys($field);
		$vals = array_values($field);

        $vals[0] = urldecode($vals[0]);
		
        $fieldId = $this->_getFieldIdFromFieldCode($keys[0]);
        $fieldType = $this->_getFieldTypeFromFieldCode($keys[0]);

		$sql     = 'SELECT '.$db->quoteName('user_id').' FROM '.$db->quoteName('#__community_fields_values').' AS a'
		          .' INNER JOIN '.$db->quoteName('#__community_users').' AS b'
		          .' ON a.'.$db->quoteName('user_id').' = b.'.$db->quoteName('userid');

        // to make sure if the value has HTML entity at WHERE conditions
        if ($fieldType == 'location') $sql .= ' WHERE a.'.$db->quoteName('value').' LIKE '.$db->Quote('%'.$vals[0].'%');
		else $sql .= ' WHERE (a.'.$db->quoteName('value').'='. $db->Quote(htmlspecialchars($vals[0])).' OR '.$db->quoteName('value').'='. $db->Quote($vals[0]).')';

		$sql	.= ' AND a.'.$db->quoteName('field_id').'='. $db->Quote($fieldId);

        // remove profile type restriction, so user can search other users profile type field as log they can read it that field
        //$sql    .= ' AND ((b.'.$db->quoteName('profile_id').' = '.$db->Quote(0).')'
				// .' OR (b.'.$db->quoteName('userid'). ' IN (
				// 		SELECT d.'.$db->quoteName('userid').' FROM '.$db->quoteName('#__community_profiles_fields') . ' as c'
				// 		.' INNER JOIN '.$db->quoteName('#__community_users').' AS d'
				// 		.' ON c.'.$db->quoteName('parent').'=d.' . $db->quoteName( 'profile_id' )
				// 		.' AND c.'.$db->quoteName('field_id').'=' . $db->Quote( $fieldId ) .')))';

		// Privacy
		$my		 = CFactory::getUser();
		$sql	.= ' AND( ';

		// If privacy for this field is 0, then we just display it.
		$sql	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('0').' OR a.'.$db->quoteName('access').' = '.$db->Quote('10').')';
		$sql	.= ' OR';

		// If privacy for this field is set to site members only, ensure that the user id is not empty.
		$sql	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('20').' AND ' . $db->Quote( $my->id ) . '!='.$db->Quote('0').' )';
		$sql	.= ' OR';

		// If privacy for this field is set to friends only, ensure that the current user is a friend of the target.
		$sql	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('30').' AND a.'.$db->quoteName('user_id').' IN (
						SELECT c.'.$db->quoteName('connect_to').' FROM '.$db->quoteName('#__community_connection')
						.' AS c WHERE c.'.$db->quoteName('connect_from').'=' . $db->Quote( $my->id )
						.' AND c.'.$db->quoteName('status').'='.$db->Quote('1').'))';
		$sql	.= ' OR';

		// If privacy for this field is set to the owner only, ensure that the id matches.
		$sql	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('40').' AND a.'.$db->quoteName('user_id').'=' . $db->Quote( $my->id ) . ')';

		$sql	.= ')';

		$limit		= $this->getState('limit');
		$limitstart	= $this->getState('limitstart');
		$total		= 0;

		//getting result count.
		$queryCnt	= 'SELECT COUNT(1) FROM ('.$sql.') AS z';
		$db->setQuery($queryCnt);
		$total		= $db->loadResult();

		$sql .=  " LIMIT " . $limitstart . "," . $limit;

		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if (empty($this->_pagination)) {
			$this->_pagination = new JPagination($total, $limitstart, $limit);
		}

		// need to return user object
		// Pre-load multiple users at once
		$userids = array();
		foreach($result as $uid)
		{
			$userids[] = $uid->user_id;
		}

		CFactory::loadUsers($userids);

		$users = array();
		foreach($result as $row){
			$users [] = CFactory::getUser($row->user_id);
		}

		return $users;
	}


	public function _getFieldIdFromFieldCode($code)
	{
		CError::assert($code , '', '!empty', __FILE__ , __LINE__ );

		$db	= $this->getDBO();
		$query	= 'SELECT' . $db->quoteName( 'id' ) . ' '
				. 'FROM ' . $db->quoteName( '#__community_fields' ) . ' '
				. 'WHERE ' . $db->quoteName( 'fieldcode' ) . '=' . $db->Quote( $code );
		$db->setQuery( $query );
		$id		= $db->loadResult();

		CError::assert($id , '', '!empty', __FILE__ , __LINE__ );
		return $id;
	}

    public function _getFieldTypeFromFieldCode($code)
    {
        CError::assert($code , '', '!empty', __FILE__ , __LINE__ );

        $db = $this->getDBO();
        $query  = 'SELECT' . $db->quoteName( 'type' ) . ' '
                . 'FROM ' . $db->quoteName( '#__community_fields' ) . ' '
                . 'WHERE ' . $db->quoteName( 'fieldcode' ) . '=' . $db->Quote( $code );
        $db->setQuery( $query );
        $id     = $db->loadResult();

        CError::assert($id , '', '!empty', __FILE__ , __LINE__ );
        return $id;
    }

     /**
     * Method to get users list on this site
     * @param type $sorted
     * @param type $filter
     * @return type
     */
    public function getPeople($sorted = 'latest', $filter = 'all', $profileId = 0, $radius = 0) {
        $db = $this->getDBO();
        $limit = $this->getState('limit');
        $limitstart = $this->getState('limitstart');
        $config = CFactory::getConfig();
        $userModel = CFactory::getModel('User');
        $my = CFactory::getUser();

        $query = 'SELECT distinct(a.' . $db->quoteName('id') . ') '
                . ' FROM ' . $db->quoteName('#__users') . ' AS a '
                . ' LEFT JOIN ' . $db->quoteName('#__session') . ' AS b '
                . ' ON a.' . $db->quoteName('id') . ' = b.' . $db->quoteName('userid');

        // if matchmaking mode was enabled, to see the list of members of opposite gender
        if ($config->get('memberlist_matchmaking_mode') && $config->get('memberlist_matchmaking_fieldcodegender') && ($my->id > 0)) {
            $modelProfile = CFactory::getModel('profile');
            $genderFieldId = $modelProfile->getFieldId($config->get('memberlist_matchmaking_fieldcodegender'));
            $genderFieldValue = $my->getInfo($config->get('memberlist_matchmaking_fieldcodegender'));

            // ignoring matchmaking if gender field not found
            if (!empty($genderFieldId)) {
                $query .= ' LEFT JOIN ' . $db->quoteName('#__community_fields_values') . ' AS c '
                    . ' ON (a.' . $db->quoteName('id') . ' = c.' . $db->quoteName('user_id')
                    . ' AND c.' . $db->quoteName('field_id') . ' = ' . $genderFieldId . ')';

                $query .= ' WHERE a.' . $db->quoteName('block') . '=' . $db->Quote(0);
                $query .= ' AND c.' . $db->quoteName('value') . '!=' . $db->Quote($genderFieldValue);
            } else {
                $query .= ' WHERE a.' . $db->quoteName('block') . '=' . $db->Quote(0);
            }
        } else {
            $query .= ' WHERE a.' . $db->quoteName('block') . '=' . $db->Quote(0);
        }

        if (!$config->get('privacy_show_admins')) {
            $tmpAdmins = $userModel->getSuperAdmins();
            $admins = array();

            $query .= ' AND a.' . $db->quoteName('id') . ' NOT IN(';
            for ($i = 0; $i < count($tmpAdmins); $i++) {
                $admin = $tmpAdmins[$i];
                $query .= $db->Quote($admin->id);
                $query .= $i < count($tmpAdmins) - 1 ? ',' : '';
            }
            $query .= ')';
        }

//		$db->setQuery($query);
//		$total		= $db->loadResult();

        $filterQuery = '';

        switch ($filter) {
            case 'others':
                $filterQuery .= ' AND ltrim(a.' . $db->quoteName('name') . ') REGEXP "^[^[:alpha:]]+"';
                break;
            case 'all':
                $field = $config->get('displayname');
                $filterQuery .= ' AND a.' . $db->quoteName($field) . ' != ""';
                break;
            default:
                $filterCount = CStringHelper::strlen($filter);
                $allowedFilters = array(JText::_('COM_COMMUNITY_JUMP_ABC_VALUE'), JText::_('COM_COMMUNITY_JUMP_DEF_VALUE'), JText::_('COM_COMMUNITY_JUMP_GHI_VALUE'), JText::_('COM_COMMUNITY_JUMP_JKL_VALUE'), JText::_('COM_COMMUNITY_JUMP_MNO_VALUE'), JText::_('COM_COMMUNITY_JUMP_PQR_VALUE'), JText::_('COM_COMMUNITY_JUMP_STU_VALUE'), JText::_('COM_COMMUNITY_JUMP_VWX_VALUE'), JText::_('COM_COMMUNITY_JUMP_YZ_VALUE'));

                if (in_array($filter, $allowedFilters)) {
                    $filterQuery .= ' AND(';
                    for ($i = 0; $i < $filterCount; $i++) {
                        $char = mb_substr($filter, $i, 1);
                        
                        $filterQuery .= $i != 0 ? ' OR ' : ' ';
                        $field = $config->get('displayname');
                        $filterQuery .= 'a.' . $db->quoteName($field) . ' LIKE ' . $db->Quote(mb_strtoupper($char) . '%') . ' OR a.' . $db->quoteName($field) . ' LIKE ' . $db->Quote(mb_strtolower($char) . '%');
                        $filterQuery .=' AND a.' . $db->quoteName($field) . ' != ""';
                    }
                    $filterQuery .= ')';
                }
                break;
        }


        // this will filter the user based on the profile id if applied.
        if($profileId){
            $profileQuery = "SELECT userid FROM ".$db->quoteName('#__community_users')." WHERE profile_id=".$db->quote($profileId);
            $db->setQuery($profileQuery);
            $profileUsers = $db->loadColumn();
            if(count($profileUsers) == 0){return array();}
            $profileUsers = implode(',' , $profileUsers);
            $filterQuery .= ' AND a.id IN('.$profileUsers.') ';
        }

        $query .= $filterQuery;
        
        if($radius) {
            $unit = ($config->get('advanced_search_units') == 'metric') ? 'km' : 'miles';
            $uid = $this->searchByRadius($radius, 0, $unit);

            if ($uid) $uid = implode(',' , $uid);
            $uid = (empty($uid)) ? '0' : $uid;

            $query .= ' AND a.'.$db->quoteName('id').' IN ( '.$uid.' ) ';
        }

        switch ($sorted) {
            case 'online':
                //online is consider as a filter now.
                $userModel = CFactory::getModel('User');
                $usersOnline = $userModel->getOnlineUsers(10000);
                $onlineUserId = array();
                foreach($usersOnline as $user){
                    $onlineUserId[] = $user->id;
                }
                $onlineUserId = implode(',',$onlineUserId);
                $onlineUserId = (empty($onlineUserId)) ? 0 : $onlineUserId;

                $query .= ' AND a.'.$db->quoteName('id').' IN ( '.$onlineUserId.' ) ';

                $config = CFactory::getConfig();
                $query .= ' ORDER BY b.'.$db->quoteName('time').' DESC, a.' . $db->quoteName($config->get('displayname')) . ' ASC';
                break;
             case 'avatar':
                $avatarUsers = $userModel->getUsersWithAvatar();
                $avatarUsers = implode(',',$avatarUsers);
                $avatarUsers = (empty($avatarUsers)) ? 0 : $avatarUsers;
                $query .= ' AND a.'.$db->quoteName('id').' IN ( '.$avatarUsers.' ) ';
                break;
            case 'alphabetical':
                $config = CFactory::getConfig();

                $query .= ' ORDER BY a.' . $db->quoteName($config->get('displayname')) . ' ASC';
                break;
            case 'featured':
                //get all the featured members
                $featured = new CFeatured(FEATURED_USERS);
                $featuredProfiles = implode(',',$featured->getItemIds());

                if($featuredProfiles){
                    $query .= ' AND a.'.$db->quoteName('id').' IN ( '.$featuredProfiles.' ) ';
                   // $query .= " ORDER BY ('.$db->quoteName('id').' IN (".$featuredProfiles.")) DESC, id "; //disabled because featured supposed to be a filter in 4.1
                }else{
					$query .= ' AND 1=0';
				}
                break;
            default:
                $query .= ' ORDER BY a.' . $db->quoteName('registerDate') . ' DESC';
                break;
        }

        if (!$this->_pagination) {
            $pagingQuery = CString::str_ireplace('distinct(a.' . $db->quoteName('id') . ')', 'COUNT(DISTINCT(a.' . $db->quoteName('id') . '))', $query);
            $db->setQuery($pagingQuery);
            $total = $db->loadResult();
            $this->_pagination = new JPagination($total, $limitstart, $limit);
        }

        $query .= ' LIMIT ' . $limitstart . ',' . $limit;
        $db->setQuery($query);
        $result = $db->loadObjectList();

        $cusers = array();

        // Pre-load multiple users at once
        $userids = array();

        // This should not happen at all since every Joomla installation has a single user added by default.
        // However, it would be nice if we don't throw any errors at all when we try to loop the results.
        if (!$result) {
            return;
        }

        foreach ($result as $uid) {
            $userids[] = $uid->id;
        }
        CFactory::loadUsers($userids);

        for ($i = 0; $i < count($result); $i++) {
            $usr = CFactory::getUser($result[$i]->id);
            $cusers[] = $usr;
        }
        return $cusers;
    }

	/**
	 * method to get the custom field options list.
	 * param - field id - int
	 * returm - array
	 */

	public function getFieldList($fieldId)
	{
		$db	= $this->getDBO();

		$query	= 'SELECT '.$db->quoteName('options').' FROM '.$db->quoteName('#__community_fields');
		$query	.= ' WHERE '.$db->quoteName('id').' = ' . $db->Quote($fieldId);

		$db->setQuery($query);
		$result = $db->loadObject();
		$listOptions	= null;


		if(isset($result->options) && $result->options != '')
		{
			$listOptions	= $result->options;
			$listOptions	= explode("\n", $listOptions);
			array_walk($listOptions, array('CStringHelper' , 'trim') );
		}//end if

		return $listOptions;
	}



	/**
	* Advance search with temporary table
	*
	*/
	public function getAdvanceSearch($filter = array(), $join='and' , $avatarOnly = '' , $sorting = '', $profileType = 0 )
	{
		$limit 		= $this->getState('limit');
		$limitstart = $this->getState('limitstart');

		$db	= $this->getDBO();

		$query	= $this->_buildCustomQuery($filter, $join , $avatarOnly, $profileType );

        // this should be profile
        if(is_array($query) && count($query) == 0){
            return $query;
        }

		//lets try temporary table here
		$tmptablename = 'tmpadv';
		$drop = 'DROP TEMPORARY TABLE IF EXISTS '.$tmptablename;
		$db->setQuery($drop);
		$db->execute();

		$query = 'CREATE TEMPORARY TABLE '.$tmptablename.' '.$query;
		$db->setQuery($query);
		$db->execute();
		$total = $db->getAffectedRows();

		//setting pagination object.
		$this->_pagination = new JPagination($total, $limitstart, $limit);

		$query = 'SELECT * FROM '.$tmptablename;

		// @rule: Sorting if required.
		if( !empty( $sorting ) )
		{
			$query  .= $this->_getSort($sorting);
		}


		// execution of master query
		$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		$db->setQuery($query);

		try {
			$result = $db->loadColumn();
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		// Preload CUser objects
		if(! empty($result))
		{
			CFactory::loadUsers($result);
		}
		$cusers = array();
		for($i = 0; $i < count($result); $i++)
		{
			$usr = CFactory::getUser( $result[$i] );
			$cusers[] = $usr;
		}

		return $cusers;
	}


	public function _buildCustomQuery($filter = array(), $join='and' , $avatarOnly = '', $profileType = 0)
	{
		$db	= $this->getDBO();
		$query		= '';
		$itemCnt	= 0;
		$config		= CFactory::getConfig();
		$join = empty($join) ? 'and' : $join;

        $profileFilter = array(); // special profile filter
        $usertable = 'u';

		/**
		 * For the 'ALL' case, we use 'IN' whereas for 'ANY' case, we use UNION.
		 *
		 */
		if(! empty($filter))
		{
			$filterCnt	= count($filter);

			foreach($filter as $obj)
			{
               if($obj->field == 'username' || $obj->field == 'useremail')
				{
                    $usertable = 'a'; // since in this condition, u = user table
					$useArray	= array('username' => $config->get('displayname') , 'useremail' => 'email');

					if($itemCnt > 0 && $join == 'or')
					{
						$query	.= ' UNION ';
					}

					$query	.= ($join == 'or') ? ' (' : '';
					$query	.= ' SELECT DISTINCT( b.'.$db->quoteName('userid').' ) as '.$db->quoteName('user_id');

					if( $itemCnt == 0 || $join == 'or')
					{
					    $query  .= ', a.'.$db->quoteName('username').' AS '.$db->quoteName('username');
					    $query  .= ', a.'.$db->quoteName('name').' AS '.$db->quoteName('name');
						$query  .= ', a.'.$db->quoteName('registerDate').' AS '.$db->quoteName('registerDate');
						$query	.= ', CASE WHEN s.'.$db->quoteName('userid').' IS NULL THEN 0 ELSE 1 END AS online';
					}

					$query  .= ' FROM '.$db->quoteName('#__users').' AS a';

					if( $itemCnt == 0 || $join == 'or')
					{
						$query  .= ' LEFT JOIN '.$db->quoteName('#__session').' AS s';
						$query  .= ' ON a.'.$db->quoteName('id').'=s.'.$db->quoteName('userid');
					}

					$query	.= ' INNER JOIN '.$db->quoteName('#__community_users').' AS b';
					$query	.= ' ON a.'.$db->quoteName('id').' = b.'.$db->quoteName('userid');
					$query	.= ' AND a.'.$db->quoteName('block').' = '.$db->Quote('0');

					// @rule: Only fetch users that is configured to be searched via email.
					if( $obj->field == 'useremail' && $config->get( 'privacy_search_email') == 1 )
					{
						$query  .= ' AND b.'.$db->quoteName('search_email').'=' . $db->Quote( 1 );
					}

					// @rule: Fetch records with proper avatar only.
					if( !empty($avatarOnly) )
					{
						$query .= ' AND b.' . $db->quoteName( 'thumb' ) . ' != ' . $db->Quote( 'components/com_community/assets/default_thumb.jpg' );
						$query .= ' AND b.' . $db->quoteName( 'thumb' ) . ' != ' . $db->Quote( '' );
					}

					$query	.= ' WHERE ' . $this->_mapConditionKey($obj->condition, $obj->fieldType, $obj->value, $useArray[$obj->field]);

					$query	.= ($join == 'or') ? ' )' : '';

					if($itemCnt < ($filterCnt - 1) && $join == 'and')
					{
						$query	.= ' AND b.'.$db->quoteName('userid').' IN (';
					}

				}
				else
				{
					if($itemCnt > 0 && $join == 'or')
					{
						$query	.= ' UNION ';
					}

					$query	.= ($join == 'or') ? ' (' : '';
					$query	.= ' SELECT DISTINCT( a.'.$db->quoteName('user_id').' ) AS '.$db->quoteName('user_id');

					// We cannot select additional columns for the subquery otherwise it will result in operand errors,
					if( $itemCnt == 0 || $join == 'or' )
					{
					    $query  .= ', u.'.$db->quoteName('username').' AS '.$db->quoteName('username');
					    $query  .= ', u.'.$db->quoteName('name').' AS '.$db->quoteName('name');
						$query  .= ', u.'.$db->quoteName('registerDate').' AS '.$db->quoteName('registerDate');
						$query	.= ', CASE WHEN s.'.$db->quoteName('userid').' IS NULL THEN 0 ELSE 1 END AS online';
					}
					$query  .= ' FROM '.$db->quoteName('#__community_fields_values').' AS a';

					// We cannot select additional columns for the subquery otherwise it will result in operand errors,
					if( $itemCnt == 0 || $join == 'or')
					{
						$query  .= ' LEFT JOIN '.$db->quoteName('#__session').' AS s';
						$query  .= ' ON a.'.$db->quoteName('id').'=s.'.$db->quoteName('userid');
					}


     				$query	.= ' INNER JOIN '.$db->quoteName('#__community_fields').' AS b';
					$query	.= ' ON a.'.$db->quoteName('field_id').' = b.'.$db->quoteName('id');
					$query	.= ' INNER JOIN '.$db->quoteName('#__users').' AS u ON a.'.$db->quoteName('user_id').' = u.'.$db->quoteName('id');
					$query	.= ' AND u.'.$db->quoteName('block').' ='.$db->Quote('0');

					// @rule: Fetch records with proper avatar only.
					if( !empty($avatarOnly) )
					{
						$query	.= ' INNER JOIN '.$db->quoteName('#__community_users').' AS c ON a.'.$db->quoteName('user_id').'=c.'.$db->quoteName('userid');
						$query	.= ' AND c.'.$db->quoteName('thumb').' != ' . $db->Quote( '' );
						$query  .= ' AND c.'.$db->quoteName('thumb').' != ' . $db->Quote( 'components/com_community/assets/default_thumb.jpg' );

					}

					if($obj->fieldType == 'birthdate')
					{
						$this->_birthdateFieldHelper($obj);
					}

					if($obj->fieldType == 'country' && strpos($obj->value,'COM_COMMUNITY_LANG_NAME_') === false){
						$obj->value ='COM_COMMUNITY_LANG_NAME_'.strtoupper(str_replace(' ','',$obj->value));
					}

                    if($obj->field == 'FIELD_PROFILE_ID_SPECIAL'){
                        $profileFilter[] = $obj;
                    }elseif($obj->field == 'FIELD_RADIUS_SEARCH'){
                        //this is a special field to search for user radius, we will leave this filter to be done later
                        $radiusFilter = $obj;
                    }else{
                        $query	.= ' WHERE b.'.$db->quoteName('fieldcode').' = ' . $db->Quote($obj->field);

                        // to make sure if the value has HTML entity
                        $obj->value = (is_array($obj->value)) ? $obj->value : htmlspecialchars($obj->value);
                        $query	.= ' AND (' . $this->_mapConditionKey($obj->condition, $obj->fieldType, $obj->value) . ' OR ' . $this->_mapConditionKey($obj->condition, $obj->fieldType, $obj->value) . ')';
                    }

					// Privacy, ignoring if as site admin
					if (!COwnerHelper::isCommunityAdmin()) {
                        $my		= CFactory::getUser();
    					$query	.= ' AND ( ';

    					// If privacy for this field is 0, then we just display it.
    					$query	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('0').' OR a.'.$db->quoteName('access').' = '.$db->Quote('10').')';
    					$query	.= ' OR';

    					// If privacy for this field is set to site members only, ensure that the user id is not empty.
    					$query	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('20').' AND ' . $db->Quote( $my->id ) . '!=0 )';
    					$query	.= ' OR';

    					// If privacy for this field is set to friends only, ensure that the current user is a friend of the target.
    					$query	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('30').' AND a.'.$db->quoteName('user_id').' IN (
    									SELECT c.'.$db->quoteName('connect_to').' FROM '.$db->quoteName('#__community_connection') .' AS c'
    									.' WHERE c.'.$db->quoteName('connect_from').'=' . $db->Quote( $my->id ) . ' AND c.'.$db->quoteName('status').'='.$db->Quote('1').')	)';
    					$query	.= ' OR';

    					// If privacy for this field is set to the owner only, ensure that the id matches.
    					$query	.= ' (a.'.$db->quoteName('access').' = '.$db->Quote('40').' AND a.'.$db->quoteName('user_id').'=' . $db->Quote( $my->id ) . ')';

    					$query	.= ')';
                    }

					$query	.= ($join == 'or') ? ' )' : '';

					if($itemCnt < ($filterCnt - 1) && $join == 'and')
					{
						$query	.= ' AND '.$db->quoteName('user_id').' IN (';
					}

				}
				$itemCnt++;
			}

			$closeTag	= '';
			if($itemCnt > 1)
			{
				for($i = 0; $i < ($itemCnt - 1); $i++)
				{
					$closeTag .= ' )';
				}
			}
			$query	= ($join == 'and') ? $query . $closeTag : $query;

		}

        // this will filter the user based on the profile id if applied.
        if($profileType || count($profileFilter)){
            //this is profile filter specific case
            $extraQuery = '';
            foreach($profileFilter as $filter){
                $condition = ($filter->condition == 'equal') ? '=' : '<>';
                $extraQuery.= ' AND profile_id'.$condition.$db->quote($filter->value);
            }

            if($profileType){
                $profileQuery = "SELECT userid FROM ".$db->quoteName('#__community_users')." WHERE profile_id=".$db->quote($profileType);
            }else{
                $profileQuery = "SELECT userid FROM ".$db->quoteName('#__community_users')." WHERE 1 ".$extraQuery;
            }

            $db->setQuery($profileQuery);
            $profileUsers = $db->loadColumn();
            if(count($profileUsers) == 0){return array();}
            $profileUsers = implode(',' , $profileUsers);

			//this is added to remove the last ) that is added in the previous query to prevent sql error
			if($join == 'or'){
				$query = substr($query, 0, -1);
			}

            $query .= ' AND '.$usertable.'.id IN('.$profileUsers.') ';

			// this is added to add back the ) that is removed previously
			if($join == 'or'){
				$query  .= ')';
			}
        }

        if(isset($radiusFilter) && $radiusFilter) {
             //this is profile filter specific case
            $extraQuery = '';
            $radius = explode(' ',$radiusFilter->value); // to separate the 10 _ km
            $unit = ($config->get('advanced_search_units') == 'metric') ? 'km' : 'miles';
            $uid = $this->searchByRadius($radius[0], 0, $unit);

            if ($uid) $uid = implode(',' , $uid);

			//this is added to remove the last ) that is added in the previous query to prevent sql error
			if($join == 'or'){
				$query = substr($query, 0, -1);
			}

			$uid = (empty($uid)) ? '0' : $uid;

            $query .= ' AND '.$usertable.'.id IN('.$uid.') ';

			// this is added to add back the ) that is removed previously
			if($join == 'or'){
				$query  .= ')';
			}
        }

        //do not show admin if the search is set to no
        if( !CFactory::getConfig()->get( 'privacy_show_admins') )
        {
            $userModel		= CFactory::getModel( 'User' );
            $tmpAdmins		= $userModel->getSuperAdmins();

            $admins         = array();

			//this is added to remove the last ) that is added in the previous query to prevent sql error
			if($join == 'or'){
				$query = substr($query, 0, -1);
			}

            $query  .= ' AND '.$db->quoteName($usertable).'.'.$db->quoteName('id').' NOT IN(';
            for( $i = 0; $i < count($tmpAdmins);$i++ )
            {
                $admin  = $tmpAdmins[ $i ];
                $query  .= $db->Quote( $admin->id );
                $query  .= $i < count($tmpAdmins) - 1 ? ',' : '';
            }

            $query  .= ')';

			// this is added to add back the ) that is removed previously
			if($join == 'or'){
				$query  .= ')';
			}
        }

		return $query;
	}

	public function _mapConditionKey($condition, $fieldType='text', $value, $fieldname = '')
	{   
		$db	= $this->getDBO();
		//the date time format for birthdate field is stored incorrectly, force to format
		if($fieldType=='birthdate' || $fieldType=='date'){
			$condString	= (empty($fieldname)) ? ' DATE_FORMAT(a.'.$db->quoteName('value') .",'%Y-%m-%d %H:%i:%s')" : ' a.'.$db->quoteName($fieldname) ;
		} else {
			$condString	= (empty($fieldname)) ? ' a.'.$db->quoteName('value') : ' a.'.$db->quoteName($fieldname) ;
		}

		switch($condition)
		{
			case 'between':
				//for now assume the value is date.
				$startVal	= '';
				$endVal		= '';
				if(is_array($value))
				{
					$startVal	= $value[0];
					$endVal		= $value[1];
				}
				else
				{
					$startVal	= $value;
					$endVal		= $value;
				}
				$condString	.= ' BETWEEN ' . $db->Quote($startVal) . ' AND ' . $db->Quote($endVal);
				break;

			case 'equal':
				if($fieldType != 'text' && $fieldType != 'select' && $fieldType != 'singleselect' && $fieldType != 'email' && $fieldType != 'radio') //this might be the list, select and etc. so we use like.
				{

                    if(is_array($value)){
                        $value = implode(',', $value);
                    }

                    $chkOptionValue	= explode(',', $value);

					if($fieldType == 'checkbox' && count($chkOptionValue) > 1)
					{
						$chkValue	= array_shift($chkOptionValue);
						$condString = '(' . $condString;
						$condString	.= ' LIKE ' . $db->Quote('%'.$chkValue.'%');
						foreach($chkOptionValue as $chkValue)
						{
							$condString	.= (empty($fieldname)) ? ' OR a.'.$db->quoteName('value') : ' OR a.'.$db->quoteName($fieldname);
							$condString	.= ' LIKE ' . $db->Quote('%'.$chkValue.'%');
						}
						$condString	.= ')';
					}
					elseif($fieldType == 'gender'){

						if(strpos(strtoupper($value),'FEMALE') === false){
							$val ='male';
						} else {
							$val = 'female';
						}

						if(strpos($value,'COM_COMMUNITY') === false){
							$value = 'COM_COMMUNITY_'.strtoupper($value);
						}

						$condString	.= (!empty($value))? ' = ' . $db->Quote($value) : ' LIKE ' . $db->Quote('%'.$value.'%');
						$condString .= ' OR a.'.$db->quoteName('value').' =' . $db->Quote($val);

					}elseif($fieldType == 'list'){

						$multipleValue = explode(',',$value);
						$firstIteration = true;

						foreach($multipleValue as $val){
							if($val){
								if($firstIteration){
									$condString .= ' LIKE ' . $db->Quote('%'.$val.'%').' AND ';
									$firstIteration = false;
								}else{
									$condString .= ' a.'.$db->quoteName('value').' LIKE ' . $db->Quote('%'.$val.'%').' AND ';
								}

							}
						}

						//this is used to remove the last AND from the condition string
						if(!$firstIteration){
							$condString = substr($condString, 0, -4);
						}

					}else
					{
                        if($fieldType == 'time'){
                            $value = str_replace(',', ':', $value);
                        }

						$condString	.= (empty($value))? ' = ' . $db->Quote($value) : ' LIKE ' . $db->Quote('%'.$value.'%');
					}
				}
				else
				{
					$condString	.= ' = ' . $db->Quote($value);
				}
				break;

			case 'notequal':

                if(is_array($value)){
                    $value = implode(',', $value);
                }

                if($fieldType != 'text' && $fieldType != 'select' && $fieldType != 'singleselect' && $fieldType != 'radio') //this might be the list, select and etc. so we use like.
				{
					$chkOptionValue	= explode(',', $value);

					if($fieldType == 'checkbox' && count($chkOptionValue) > 1)
					{
						$chkValue	= array_shift($chkOptionValue);
						$condString = '(' . $condString;
						$condString	.= ' NOT LIKE ' . $db->Quote('%'.$chkValue.'%');
						foreach($chkOptionValue as $chkValue)
						{
							$condString	.= (empty($fieldname)) ? ' AND a.'.$db->quoteName('value') : ' AND a.'.$db->quoteName($fieldname);
							$condString	.= ' NOT LIKE ' . $db->Quote('%'.$chkValue.'%');
						}
						$condString	.= ')';
					} elseif($fieldType == 'gender'){
						if(strpos(strtoupper($value),'FEMALE') === false){
							$val ='male';
						} else {
							$val = 'female';
						}

						if(strpos($value,'COM_COMMUNITY') === false){
							$value = 'COM_COMMUNITY_'.strtoupper($value);
						}

						$condString	.= (!empty($value))? ' != ' . $db->Quote($value) : ' NOT LIKE ' . $db->Quote('%'.$value.'%');
						$condString .= ' AND a.'.$db->quoteName('value').' !=' . $db->Quote($val);

					}
					else
					{
                        if($fieldType == 'time'){
                            $value = str_replace(',', ':', $value);
                        }

						$condString	.= ' NOT LIKE ' . $db->Quote('%'.$value.'%');
						//$condString	.= (empty($value))? ' != ' . $db->Quote($value) : ' NOT LIKE ' . $db->Quote('%'.$value.'%');
					}
				}
				else
				{
					$condString	.= ' != ' . $db->Quote($value);
				}
				break;

			case 'lessthanorequal':
				$condString	.= ' <= ' . $db->Quote($value);
				break;

			case 'greaterthanorequal':
				$condString	.= ' >= ' . $db->Quote($value);
				break;

			case 'contain':
			default :
                    if ($fieldType == 'location') {
                        $value = str_replace('"', '', json_encode($value));

                        // json encode search
                        $condString .= ' LIKE ' . '"%' . str_replace('\\', '\\\\\\\\', $value) . '%"';
                    } else {
                        $condString .= ' LIKE ' . $db->Quote('%'.$value.'%');
                    }
				break;
		}
		$condString	.= (empty($join)) ? '' : ')';

        if($fieldType=='gender') $condString = "( $condString )";

		return $condString;
	}

	/**
	 * Simple video search to search the title and description
	 **/
	public function searchVideo( $searchText )
	{
		$db		= $this->getDBO();

		$limit			= $this->getState('limit');
		$limitstart		= $this->getState('limitstart');

		$query	= 'SELECT *, ' . $db->quoteName('created') . ' AS lastupdated '
				. 'FROM ' . $db->quoteName( '#__community_videos' ) . ' '
				. 'WHERE ' . $db->quoteName( 'status' ) . '=' . $db->Quote( 'ready' ) . ' '
				. 'AND ' . $db->quoteName('published') . '=' . $db->Quote( 1 ) . ' '
				. 'AND (' . $db->quoteName( 'title' ) . ' LIKE ' . $db->Quote( '%' . $searchText . '%' ) . ' '
				. 'OR ' . $db->quoteName( 'description' ) . ' LIKE ' . $db->Quote( '%' . $searchText . '%' ) . ') '
                . 'ORDER BY '.$db->quoteName('created').' DESC ';

		$queryCnt	= 'SELECT COUNT(1) FROM ('.$query.') AS z';
		$db->setQuery($queryCnt);
		$this->_total= $db->loadResult();

		$query	.= 'LIMIT ' . $limitstart . ',' . $limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		// Appy pagination
		if (empty($this->_pagination))
		{
	 	    $this->_pagination = new JPagination($this->_total, $limitstart, $limit);
	 	}

		return $result;
	}

	/**
	 * auto user suggest search
	 * @param query	string	people's name to seach for
	 * param - fieldName	: string - name of the input box
	 *       - fieldId		: string - id of the input box
	 */
	public function getAutoUserSuggest($searchName, $displayName)
	{
		$db	= $this->getDBO();
		$filter = array();

		// build where condition
		$filterField = array();
		if(isset($searchName))
		{
	    	switch($displayName)
	    	{
	    		case 'name':
	    			$filter[] = 'UCASE('.$db->quoteName('name').') like UCASE(' . $db->Quote('%'.$searchName.'%') . ')';
	    			break;
	    		case 'username':
	    		default :
					$filter[] = 'UCASE('.$db->quoteName('username').') like UCASE(' . $db->Quote('%'.$searchName.'%') . ')';
	    			break;
			}
	    }

		$finalResult	= array();
		if(count($filter)> 0 || count($filterField > 0))
		{
			// Perform the simple search
			$basicResult = null;
			if(!empty($filter) && count($filter)>0)
			{
				$query = 'SELECT distinct b.'.$db->quoteName('id').' FROM '.$db->quoteName('#__users').' b';
				$query .= ' WHERE b.'.$db->quoteName('block').' = '.$db->Quote(0).' AND '.implode(' AND ',$filter);
				//$query .=  " LIMIT " . $limitstart . "," . $limit;

				$db->setQuery( $query );
				try {
					$finalResult = $db->loadColumn();
				} catch (Exception $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}
		}

		if(empty($finalResult))
			$finalResult = array(0);

		$id = implode(",",$finalResult);
		$where = array($db->quoteName('id')." IN (".$id.")");
		$result = $this->getFiltered($where);

		return $result;
	}

	public function searchByRadius($withinRange = 200, $userid = 0, $unit = 'miles'){
		$user = ($userid) ? CFactory::getUser($userid) : CFactory::getUser();

		//user is not logged in or invalid user
		if(!$user->id){
			return false;
		}

		$currentLongitude = $user->longitude;
		$currentLatitude = $user->latitude;

		$db = JFactory::getDbo();

        //we use 3 because its equivalent to 333 km
		$query = "SELECT userid, latitude, longitude FROM ".$db->quoteName('#__community_users')." WHERE "
				.$db->quoteName('latitude')."!=".$db->quote(255)." AND "
				.$db->quoteName('longitude')." BETWEEN ".$db->quote(($currentLongitude-3))." AND ".$db->quote(($currentLongitude+3))." AND "
				.$db->quoteName('latitude')." BETWEEN ".$db->quote($currentLatitude-3)." AND ".$db->quote($currentLatitude+3)." AND "
                .$db->quoteName('userid')."!=".$db->quote($user->id);

        $db->setQuery($query);
        $results = $db->loadObjectList();

        switch($unit){
            case 'miles' :
                $earthRadius = 6371/1.609344;
                break;
            default: //km
                $earthRadius = 6371;
        }

        $userList = array();
        $latFrom = deg2rad($currentLatitude);
        $lonFrom = deg2rad($currentLongitude);
        foreach($results as $result){

            $latTo = deg2rad($result->latitude);
            $lonTo = deg2rad($result->longitude);

            $lonDelta = $lonTo - $lonFrom;
            $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
            $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

            $angle = atan2(sqrt($a), $b);
            $distance = $angle * $earthRadius;


            if($distance <= $withinRange){
                $userList[] = $result->userid;
            }
        }

		return $userList;

	}

	// since the user input value is age which is interger,
	// we need to convert it into datetime
	private function _birthdateFieldHelper(&$obj)
	{
		$is_age = true;
		$obj->fieldType = 'birthdate';

		//If value is not array, pass it back as array
		//if(!is_array($obj->value)){
        //            $obj->value = explode(',',$obj->value);
        //        }


                //detecting search by age or date
		if((is_array($obj->value) && strtotime($obj->value[0]) !== false && strtotime($obj->value[1]) !== false)
			|| (!is_array($obj->value) && strtotime($obj->value))) {
			$is_age = false;
		} else {
			//the input value must be unsign number, else return
			if(is_array($obj->value)){
				if (!is_numeric($obj->value[0]) || !is_numeric($obj->value[1]) || intval($obj->value[0]) < 0 || intval($obj->value[1]) < 0){
					//invalid range, reset to 0
					$obj->value[0] = 0;
					$obj->value[1] = 0;
					return ;
				}
				$obj->value[0]	= intval($obj->value[0]);
				$obj->value[1]	= intval($obj->value[1]);
			} else {
				if(!is_numeric($obj->value) || intval($obj->value) < 0){
					//invalid range, reset to 0
					$obj->value = 0;
					return;
				}
				$obj->value = intval($obj->value);
			}
		}

		// correct the age order
		if (is_array($obj->value) && ($obj->value[1] > $obj->value[0]))
		{
			$obj->value = array_reverse($obj->value);
		}

		// TODO: something is wrong with comparing the datetime value
		// in text type instead of datetime type,
		// e.g. BETWEEN '1955-09-07 00:00:00' AND '1992-09-07 23:59:59'
		// we can't find '1992-02-26 23:59:59' in the result.

		if ($obj->condition == 'between')
		{
			if($is_age){
				$year0 = $obj->value[0]+1;
				$year1 = $obj->value[1];

				$datetime0 = new Datetime();
				$datetime0->modify('-'.$year0 . ' year');
				$obj->value[0] = $datetime0->format('Y-m-d 00:00:00');

				$datetime1 = new Datetime();
				$datetime1->modify('-'.$year1 . ' year');
				$obj->value[1] = $datetime1->format('Y-m-d 23:59:59');

			} else {
				$value0 = new JDate($obj->value[0]);
				$obj->value[0] = $value0->format('Y-m-d 00:00:00');
				$value1 = new JDate($obj->value[1]);
				$obj->value[1] = $value1->format('Y-m-d 23:59:59');
			}
		}

		if ($obj->condition == 'equal')
		{
			// equal to an age means the birthyear range is 1 year
			// so we make it become a range
			$obj->condition = 'between';

			if($is_age){
				$age	= $obj->value;
				unset($obj->value);
				$year0 = $age + 1;
				$year1 = $age;

				$datetime0 = new Datetime();
				$datetime0->modify('-'.$year0 . ' year');
				$obj->value[0] = $datetime0->format('Y-m-d 00:00:00');

				$datetime1 = new Datetime();
				$datetime1->modify('-'.$year1 . ' year');
				$obj->value[1] = $datetime1->format('Y-m-d 23:59:59');


			} else {
				$value0 = new JDate($obj->value);
				$value1 = new JDate($obj->value);
				unset($obj->value);
				$obj->value[0] = $value0->format('Y-m-d 00:00:00');
				$obj->value[1] = $value1->format('Y-m-d 23:59:59');
			}

		}

		if ($obj->condition == 'lessthanorequal')
		{
			if($is_age){
				$obj->condition = 'between';

				$year0 = $obj->value+1;
				unset($obj->value);
				$datetime0 = new Datetime();
				$datetime0->modify('-'.$year0 . ' year');
				$obj->value[0] = $datetime0->format('Y-m-d 00:00:00');

				$datetime1 = new Datetime();
				$obj->value[1] = $datetime1->format('Y-m-d 23:59:59');

			} else {
				$obj->condition = 'lessthanorequal';
				$value0 = new JDate($obj->value);
				$obj->value = $value0->format('Y-m-d 23:59:59');;
			}
		}

		if ($obj->condition == 'greaterthanorequal')
		{
			if($is_age){
				$obj->condition = 'lessthanorequal'; //the datetime logic is inversed
				$age	= $obj->value;
				unset($obj->value);

				$year0 = $age;

				$datetime0 = new Datetime();
				$datetime0->modify('-'.$year0 . ' year');
				$obj->value = $datetime0->format('Y-m-d 00:00:00');

			} else {
				$obj->condition = 'between';
				$value0 = new JDate($obj->value);
				unset($obj->value);

				$obj->value[0] = $value0->format('Y-m-d 00:00:00');
				$value1 = new JDate();
				$obj->value[1] = $value1->format('Y-m-d 23:59:59');
			}
		}

		// correct the date order
		if (is_array($obj->value) && ($obj->value[1] < $obj->value[0]))
		{
			$obj->value = array_reverse($obj->value);
		}

	}

        private function _getSort( $sorting )
        {
                $db	= $this->getDBO();
                $query = '';
                switch( $sorting )
                {
                        case 'online':
                                $query	= 'ORDER BY '.$db->quoteName('online').' DESC';
                                break;
                        case 'alphabetical':
                                $config	= CFactory::getConfig();
                                $query	= ' ORDER BY ' . $db->quoteName($config->get('displayname')) . ' ASC';
                                break;
                        default:
                                $query	= ' ORDER BY '.$db->quoteName('registerDate').' DESC';
                                break;
                }

                return $query;
        }
}