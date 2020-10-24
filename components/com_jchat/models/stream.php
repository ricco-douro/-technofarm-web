<?php
//namespace components\com_jchat\models;
/**  
 * @package JCHAT::STREAM::components::com_jchat 
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */ 
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Stream model public responsibilities interface
 * The entity to perform CRUD operation on is the Stream
 * It supports special get/store/delete responsibilities to be a 
 * more generic stream resource for chat service
 *
 * @package JCHAT::STREAM::components::com_jchat
 * @subpackage models
 * @since 2.1
 */
interface IStreamModel {
	/**
	 * Get the Data on read from Stream
	 * @access public
	 * @return array
	 */
	public function getChatData();
	
	/**
	 * Write user status on Stream
	 * 
	 * @access public
	 * @param string $status
	 * @return array
	 */
	public function storeUserStatus($fieldName, $fieldValue);
	
	/**
	 * Write user Skype ID on Stream
	 * 
	 * @access public
	 * @param int $skypeID
	 * @return array
	*/
	public function storeUserStateFromRequest($statusVarName, $statusVarValue);
	
	/**
	 * Store banning state for current session id user
	 *
	 * @access public
	 * @return array
	 */
	public function storeBannedUsersState($bannedUserInfo);
	
	/**
	 * Write private message on Stream
	 * 
	 * @access public
	 * @param string $to
	 * @param int $tologged
	 * @param string $message
	 * @param Object $mailer
	 * @return array
	 */
	public function storePrivateMessage($to, $tologged, $message, $mailer);
	
	/**
	 * Write group message on Stream
	 * 
	 * @access public
	 * @param int $to
	 * @param string $message
	 * @return array
	 */
	public function storeGroupMessage($to, $message);
	
	/**
	 * Delete conversation from session
	 * 
	 * @access public
	 * @param int $from
	 * @return array
	 */
	public function deleteConversation($from);
	
	/**
	 * Retrieve guest info user informations, usually stored by guest activation form
	 *
	 * @access public
	 * @param string $session_id
	 * @return array
	 */
	public function getInfoGuest($session_id);
	
	/**
	 * Load chatrooms from DB using caching system
	 *
	 * @access private
	 * @return array
	 */
	public function loadChatRooms();
	
	/**
	 * Retrieve old messages based on time period and private conversation
	 * between to logged in and registered users
	 *
	 * @access public
	 * @param int $fromLoggedID
	 * @param string $fromUserID
	 * @param string $timePeriod
	 * @param int $minMessageId
	 * @return array
	 */
	public function fetchHistoryMessages($fromLoggedID, $fromUserID, $timePeriod, $minMessageId);
}

/**
 * Main stream class concrete implementation
 * 
 * @package JCHAT::STREAM::components::com_jchat
 * @subpackage models 
 * @since 2.1
 */
class JChatModelStream extends JChatModel implements IStreamModel {
	/**
	 * Response aray
	 * @access private
	 * @var array
	 */
	private $response;
	
	/**
	 * Main private messages
	 * @access private
	 * @var array
	 */
	private $messages;
	
	/**
	 * Public group messages
	 * @access private
	 * @var array
	 */
	private $wallMessages;
	
	/**
	 * User Object
	 * @access private
	 * @var Object &
	 */
	private $myUser;
	
	/**
	 * User chatroom ID if any
	 * @access private
	 * @var Object &
	 */
	private $myChatRoom;
	
	/**
	 * User session table Object
	 * @access private
	 * @var Object &
	 */
	private $userSessionTable;
	
	/**
	 * Type of social extension integrated if any from main config params
	 * @access private
	 * @var string
	 */
	private $integratedExtensions;
	
	/**
	 * Monitor typing status changes from users interaction with chatbox
	 * @access private
	 * @var string
	 */
	private $typingStatusChanged;
	
	/**
	 * Keep track of the target typing user to that user is writing
	 * @access private
	 * @var string
	 */
	private $typingTo;
	
	/**
	 * Discard operations if typing is not enabled
	 * @access private
	 * @var string
	 */
	private $typingEnabled;
	
	/**
	 * Params Object
	 * @access protected
	 * @var Object &
	 */
	protected $componentParams;
	
	/**
	 * Convert time to day/hours/minutes
	 * @access private
	 * @param int $time
	 * @return string
	 */
	private function convertToDaysHoursMins($time) {
	    settype($time, 'integer');
	    $time2Display = null;
	    if ($time < 0) {
	        return;
	    }
	
	    // case: show years
	    $years = floor(((($time/60)/60)/24)/365);
	    if($years > 0) {
	    	$time2Display = $years . JText::_('COM_JCHAT_YEARS');
	    }
	    
	    // case: show days
	    $days = floor((($time/60)/60)/24);
	    if($days > 0 && $days < 365) {
	    	$time2Display = $days . JText::_('COM_JCHAT_DAYS');
	    }
	    
	    // case: show hours
	    $hours = floor(($time/60)/60);
	    if($hours > 0 && $hours < 24) {
	    	$time2Display = $hours . JText::_('COM_JCHAT_HOURS');
	    }
	    
	    // case: show minutes
	    $minutes = floor($time/60);
	    if($minutes > 0 && $minutes < 60) {
	        $time2Display = $minutes . JText::_('COM_JCHAT_MINUTES');
	    }
	    
		// case: show seconds
	    $seconds = $time;
	    if($seconds > 0 && $seconds < 60) {
	        $time2Display = $seconds . JText::_('COM_JCHAT_SECONDS');
	    }
	    
	    return $time2Display;
	}
	
	/**
	 * Si occupa di controllare se esistono nuovi MSGFILE presenti nel database con status = 1
	 * che non sono stati refreshati in sessione e li pone nella response['downloads'] memorizzandoli
	 * separatamente in sessione per evitare doppie notifiche e aggiornamenti
	 * 
	 * @access private
	 * @return void
	 */
	private function refreshMsgFileSessionStatus() {
		$query = "SELECT id, " . $this->_db->quoteName('to') . " FROM #__jchat" .
				 "\n WHERE type=" . $this->_db->quote('file') .
				 "\n AND status = 1" .
				 "\n AND " . $this->_db->quoteName('from') . " = " . $this->_db->quote($this->userSessionTable->session_id);
		$this->_db->setQuery($query);
		$msgFiles = $this->_db->loadObjectList();
		
		// Gestiamo il session array downloads già notificati
		if(!isset($_SESSION['jchat_notified_downloads'])) {
			$_SESSION['jchat_notified_downloads'] = array();
		} 
		
		if(is_array($msgFiles) && count($msgFiles)) {
			foreach ($msgFiles as $msgFile) {
				if(!array_key_exists($msgFile->id, $_SESSION['jchat_notified_downloads'])) {
					$conversation2Refresh = &$_SESSION['jchat_user_' . $msgFile->to];
					if(is_array($conversation2Refresh) && count($conversation2Refresh)) {
						$conversation2Refresh[$msgFile->id]['status'] = $_SESSION['jchat_user_' . $msgFile->to][$msgFile->id]['status'] = 1;
						$_SESSION['jchat_notified_downloads'][$msgFile->id] = true;
						$this->response['downloads'][] = array($msgFile->to, $msgFile->id);
					}
				}
			} 
		}
	}
	
	/**
	 * Get user profile based on integration type
	 *
	 * @access private
	 * @static
	 * @param int $id
	 * @param string $name
	 * @param Object $cParams
	 * @return string
	 */
	private function getUserProfileLink($id, $name, $cParams) {
		// User id required
		if(!$id) {
			return null;
		}
		$profileLink = null;
		$integrationType = $cParams->get('3pdintegration', null);
		// Evaluate if integration type is activated
		if($integrationType === 'jomsocial') {
			// Format fo JomSocial
			$profileLink = JRoute::_('index.php?option=com_community&view=profile&userid=' . $id);
		}  elseif($integrationType === 'easysocial') {
			// Format for EasySocial users
			$formattedName = strtolower($name);
			$formattedName = str_replace(' ', '-', $formattedName);
			$profileLink = JRoute::_('index.php?option=com_easysocial&view=profile&id=' . $id . '-' . $formattedName);
		} elseif($integrationType === 'cbuilder') {
			// Format for CB users
			$profileLink = JRoute::_('index.php?option=com_comprofiler&task=userprofile&user=' . $id);
		} elseif($integrationType === 'kunena') {
			// Format for Kunena users
			$profileLink = JRoute::_('index.php?option=com_kunena&view=user&userid=' . $id);
		}
		
		return $profileLink;
	}
	
	/**
	 * Detect if live support mode is active and returns query chunks to
	 * filter users/messages if users is not a chat admins
	 *
	 * @access private
	 * @return array
	 */
	private function getQueryLiveSupport() {
		$arrayQueries = array(0=>null, 1=>null);
		// Check for live support mode active
		$chatAdminsGids = $this->componentParams->get('chatadmins_gids', array('0'));
		// Live support active!
		if(is_array($chatAdminsGids) && !in_array(0, $chatAdminsGids, false)) {
			// Check for user groups current user belong to
			$userGroups = $this->myUser->getAuthorisedGroups();
			// Intersect to recognize chat admins
			$intersectResult = array_intersect($userGroups, $chatAdminsGids);
			$isChatAdmin = (bool)(count($intersectResult));
				
			// Eventually limit query to users that belong to chat admins
			if(!$isChatAdmin) {
				$arrayQueries[0] = "\n INNER JOIN #__user_usergroup_map AS map ON map.user_id = sess.userid";
				$arrayQueries[1] = "\n AND map.group_id IN (" . implode(',', $chatAdminsGids) . ")";
			}
		}
		return $arrayQueries;
	}
	
	/**
	 * Translate chat access levels into user groups and generates filtering query
	 * accordingly for the users taken into account by the buddylist, so avoiding
	 * show disabled users into chat of enabled users refreshing session lifetime during navigation
	 *
	 * @access private
	 * @return array
	 */
	private function getQueryAccessLevels() {
		$arrayQueries = array(0=>null, 1=>null);
		// Check for live support mode active
		$chatAccessGids = array();
		$chatAccessLevels = $this->componentParams->get('chat_accesslevels', array('0'));
		// Live support active!
		if(is_array($chatAccessLevels) && !in_array(0, $chatAccessLevels, false)) {
			// Translate the chat access levels to Joomla users groups sum
			$query = $this->_db->getQuery(true)
							   ->select('rules')
							   ->from($this->_db->quoteName('#__viewlevels'))
							   ->where('id IN (' . implode(',', $chatAccessLevels) . ')');
			// Set the query for execution.
			$this->_db->setQuery($query);
			// Build the view levels array.
			foreach ($this->_db->loadColumn() as $levels) {
				$chatAccessGids = array_merge($chatAccessGids, (array) json_decode($levels));
			}

			// Limit query to users that belong to groups for the chosen chat access levels
			$arrayQueries[0] = "\n INNER JOIN #__user_usergroup_map AS accessmap ON accessmap.user_id = sess.userid";
			$arrayQueries[1] = "\n AND accessmap.group_id IN (" . implode(',', $chatAccessGids) . ")";
		}
		return $arrayQueries;
	}
	
	/**
	 * Get query parts needed for SELECT & JOIN
	 * tables for database integration component
	 * 
	 * @access private
	 * @param string $queryType
	 * @return array
	 */
	private function getQueryParts($queryType) {
		// Restituisce le query parts in accordo al tipo di integration richiesta
		$queryParts = array();
	
		switch ($queryType) {
			case 'buddylist' :
				switch ($this->integratedExtensions) {
					case 'jomsocial':
						$queryParts['JOIN'] = "\n INNER JOIN #__community_connection AS cc ON cc.connect_to = u.id";
						$queryParts['WHERE'] = 	"\n AND cc.connect_from = " . (int)$this->myUser->id .
												"\n AND cc.status = 1";
						break;
	
					case 'cbuilder':
						$queryParts['JOIN'] = "\n INNER JOIN #__comprofiler_members AS cm ON cm.memberid = u.id";
						$queryParts['WHERE'] = 	"\n AND cm.referenceid = " . (int)$this->myUser->id .
												"\n AND cm.accepted = 1 AND cm.pending = 0";
						break;
						
					case 'easysocial':
						$queryParts['JOIN'] = "\n LEFT JOIN #__social_friends AS sf ON (sf.actor_id = u.id OR sf.target_id = u.id)" .
						$queryParts['WHERE'] = 	"\n AND (sf.actor_id = " . (int)$this->myUser->id . " OR sf.target_id = " . (int)$this->myUser->id . ")" .
												"\n AND sf.state = 1";
						break;
				}
				break;
	
	
			case 'groupmessages' :
				switch ($this->integratedExtensions) {
					case 'jomsocial':
						$queryParts['AND'] = " AND u.id IN (SELECT connect_to FROM #__community_connection" .
											 "\n WHERE connect_from = " . (int)$this->myUser->id . " AND status = 1)";
						break;
							
					case 'cbuilder':
						$queryParts['AND'] = " AND u.id IN (SELECT memberid FROM #__comprofiler_members" .
											 "\n WHERE referenceid = " . (int)$this->myUser->id . " AND accepted = 1 and pending = 0)";
						break;
						
					case 'easysocial':
						$queryParts['AND'] = " AND (u.id IN (SELECT actor_id FROM #__social_friends" .
											 "\n WHERE target_id = " . (int)$this->myUser->id . " AND state = 1)" .
											 "\n OR u.id IN (SELECT target_id FROM #__social_friends" .
											 "\n WHERE actor_id = " . (int)$this->myUser->id . " AND state = 1))";
						break;
				}
				break;
		}
	
		return $queryParts;
	}
	
	/**
	 * Get users status
	 * 
	 * @access protected
	 * @return void
	 */
	protected function getUserStateFromDB() { 
		// If not guest
		if($this->myUser->id) {
			$sql = 	"SELECT " .
					$this->_db->quoteName('skypeid') . "," . 
					$this->_db->quoteName('roomid') .
					"\n FROM " .
					$this->_db->quoteName('#__jchat_userstatus') .
					"\n WHERE " . $this->_db->quoteName('userid') ." = " . $this->_db->quote($this->myUser->id);
			$this->_db->setQuery($sql);
			$userStatus = $this->_db->loadAssoc();
		}
		
		$sql = 	"SELECT " . 
				$this->_db->quoteName('status') . "," .
				$this->_db->quoteName('override_name') . "," .
			   	$this->_db->quoteName('skypeid') . "," .
			   	$this->_db->quoteName('roomid') .
				"\n FROM " .
				$this->_db->quoteName('#__jchat_sessionstatus') .
				"\n WHERE " . $this->_db->quoteName('sessionid') ." = " . $this->_db->quote($this->userSessionTable->session_id); 
		$this->_db->setQuery($sql); 
		$chat = $this->_db->loadAssoc();  
		
		if (empty($chat['status'])) {
			$chat['status'] = 'available';
		} else {
			if ($chat['status'] == 'offline') {
				$_SESSION['jchat_sessionvars']['buddylist'] = 0;
			}
		}
		
		$overrideName = null;
		if(!empty($chat['override_name'])) {
			$overrideName = $chat['override_name'];
		}
		
		$skypeId = null;
		if(!empty($userStatus['skypeid'])) {
			$skypeId = $userStatus['skypeid'];
		} elseif(!empty($chat['skypeid'])) {
			$skypeId = $chat['skypeid'];
		}
		
		$roomid = null;
		if(!empty($userStatus['roomid'])) {
			$roomid = $userStatus['roomid'];
		} elseif(!empty($chat['roomid'])) {
			$roomid = $chat['roomid'];
		}
		
		$status = array('status' => $chat['status'], 'override_name' => $overrideName, 'skype_id' => $skypeId, 'room_id' => $roomid);
		$this->response['userstatus'] = $status;
	}
  
	/**
	 * Inject chatrooms list on first initialize and subsequent buddylist dispatching
	 *
	 * @access protected
	 * @param array $buddylist
	 * @return void
	 */
	protected function getChatRoomsList($buddylist) {
		// Get list of current chatrooms available
		$cachable = $this->componentParams->get('caching', false);
		if($cachable) {
			// By default callback handler
			$cache = $this->getExtensionCache();
			$rooms = $cache->call(array($this, 'loadChatRooms'));	
		} else {
			$rooms = $this->loadChatRooms();
		}
		
		if($buddylist) {
			$showChatroomsUsersDetails = $this->componentParams->get('chatrooms_users_details', 1);
			$currentlyValidSessions = array_keys($buddylist);
			$currentlyValidSessions = "'" . implode("','", $currentlyValidSessions) . "'"; 
			// Count rooms from userstate
			$query = "SELECT COUNT(rooms.roomid) AS numusers, rooms.roomid" .
					 "\n FROM #__jchat_userstatus AS rooms" .
					 "\n INNER JOIN #__session AS sess" .
					 "\n ON sess.userid = rooms.userid" .
					 "\n WHERE sess.client_id = 0 AND sess.session_id IN(" . $currentlyValidSessions . ")" .
					 "\n GROUP BY rooms.roomid";
			$this->_db->setQuery($query);
			$roomsUserstateCount = $this->_db->loadAssocList('roomid');
			
			// Count rooms from sessionstate
			$query = "SELECT COUNT(rooms.roomid) AS numusers, rooms.roomid" .
					"\n FROM #__jchat_sessionstatus AS rooms" .
					"\n WHERE rooms.sessionid IN(" . $currentlyValidSessions . ")" .
					"\n GROUP BY rooms.roomid";
			$this->_db->setQuery($query);
			$roomsSessionstateCount = $this->_db->loadAssocList('roomid');
			
			// Needs activating users details for joined rooms
			// Select all single users that are in a chatroom then merge with room data
			if($showChatroomsUsersDetails) {
				$query = "SELECT rooms.roomid, sess.session_id AS sessionid" .
						 "\n FROM #__jchat_userstatus AS rooms" .
						 "\n INNER JOIN #__session AS sess" .
						 "\n ON sess.userid = rooms.userid" .
						 "\n WHERE sess.client_id = 0 AND sess.session_id IN(" . $currentlyValidSessions . ")" .
						 "\n ORDER BY rooms.roomid";
				$this->_db->setQuery($query);
				$roomsUserstateUsers = $this->_db->loadObjectList();
				
				$query = "SELECT rooms.roomid, rooms.sessionid" .
						 "\n FROM #__jchat_sessionstatus AS rooms" .
						 "\n WHERE rooms.sessionid IN(" . $currentlyValidSessions . ")" .
						 "\n ORDER BY rooms.roomid";
				$this->_db->setQuery($query);
				$roomsSessionstateUsers = $this->_db->loadObjectList();
			}
			
			
			// Manage total users per each chat rooms
			if(count($rooms)) {
				foreach ($rooms as &$room) {
					if(array_key_exists($room['id'], $roomsUserstateCount)) {
						$currentUsers = $roomsUserstateCount[$room['id']]['numusers'];
						$room['numusers'] += $currentUsers;
					}
					if(array_key_exists($room['id'], $roomsSessionstateCount)) {
						$currentSessions = $roomsSessionstateCount[$room['id']]['numusers'];
						$room['numusers'] += $currentSessions;
					}
					
					// Needs activating users details for joined rooms
					if($showChatroomsUsersDetails) {
						if(count($roomsUserstateUsers)) {
							foreach ($roomsUserstateUsers as $userStateUser) {
								if($userStateUser->roomid == $room['id']) {
									$room['users'][] = $buddylist[$userStateUser->sessionid]['name'];
								}
							}
						}
						if(count($roomsSessionstateUsers)) {
							foreach ($roomsSessionstateUsers as $sessionStateUser) {
								if($sessionStateUser->roomid == $room['id']) {
									$room['users'][] = $buddylist[$sessionStateUser->sessionid]['name'];
								}
							}
						}
					}
				}
			}
		}
	
		// Inject into client response
		$this->response['chatrooms'] = $rooms;
	}
	
	/**
	 * Inject my chatroom users on first initialize and subsequent buddylist dispatching
	 *
	 * @access protected
	 * @access array $buddylist the current buddylist to filter
	 * @return void
	 */
	protected function getMyChatRoomUsers($buddylist) {
		$usersInMyRoom = array();
		
		if($this->myChatRoom && $buddylist) {
			// Session users in my room from valid Joomla sessions JOIN userstate
			$query = "SELECT sess.session_id" .
					 "\n FROM #__session AS sess" .
					 "\n INNER JOIN #__jchat_sessionstatus AS status" .
					 "\n ON sess.session_id = status.sessionid" .
					 "\n WHERE sess.client_id = 0 AND status.roomid = " . (int)$this->myChatRoom;
			$this->_db->setQuery($query);
			$sessionsUserState = $this->_db->loadColumn();
			
			// Session users in my room from valid Joomla sessions JOIN sessionstate
			$query = "SELECT sess.session_id" .
					 "\n FROM #__session AS sess" .
					 "\n INNER JOIN #__jchat_userstatus AS status" .
					 "\n ON sess.userid = status.userid" .
					 "\n WHERE sess.client_id = 0 AND status.roomid = " . (int)$this->myChatRoom;
			$this->_db->setQuery($query);
			$usersUserState = $this->_db->loadColumn();
			
			// Merge both arrays and ensure unique values
			$totalSessions = array_merge($sessionsUserState, $usersUserState);
			$totalSessions = array_unique($totalSessions);
			
			if(count($totalSessions)) {
				foreach ($totalSessions as $userInMyRoom) {
					if(array_key_exists($userInMyRoom, $buddylist)) {
						$userData = $buddylist[$userInMyRoom];
						$usersInMyRoom[] = $userData['name'];
					}
				}
			}
		} else {
			$usersInMyRoom = false;
		}
		
		// Inject into client response
		$this->response['users_inmyroom'] = $usersInMyRoom;
	}
	
	/**
	 * Initialize my chat room id if chatroom mode is enabled
	 * 
	 * @access protected
	 * @return int chatroom ID if any
	 */
	protected function getMyChatRoom() {
		// Assume user is not joined in any chatroom by default
		$this->myChatRoom = false;
		
		// If chatroom mode is enabled, try to check if current user has joined to a chatroom, both as session guest or logged user
		if($this->componentParams->get('groupchatmode', 'chatroom') == 'chatroom') {
			// Limit received messages to only users that belongs to same my chatroom
			$queryMyChatRoom = 	"SELECT (SELECT userstate.roomid" .
								"\n FROM #__jchat_userstatus AS userstate" .
								"\n WHERE userstate.userid = " . (int)$this->myUser->id . ") AS user_roomid," .
								"\n (SELECT sessionstate.roomid" .
								"\n FROM #__jchat_sessionstatus AS sessionstate" .
								"\n WHERE sessionstate.sessionid = " . $this->_db->quote($this->userSessionTable->session_id) . ") AS session_roomid";
			$myChatRoomInfo = $this->_db->setQuery($queryMyChatRoom)->loadObject();
			$this->myChatRoom = $myChatRoomInfo->user_roomid ? $myChatRoomInfo->user_roomid : $myChatRoomInfo->session_roomid;
		}
		
		return $this->myChatRoom;
	}
	
	/**
	 * Retrieve and inject into response the last read message ID based on users stream
	 *
	 * @access protected
	 * @return void
	 */
	protected function getLatestReadMessage() {
		$openChatBoxesString = isset($_SESSION ['jchat_sessionvars']['activeChatboxes']) ? $_SESSION ['jchat_sessionvars']['activeChatboxes'] : null ;
		if($openChatBoxesString) {
			// Initialize response array
			$this->response['lastreadmessages'] = array();

			// Parse the currently opened chatboxes
			$chatBoxesIDs = preg_split('/(\|\d+,*)/i', $openChatBoxesString);
			array_pop($chatBoxesIDs);

			// Cycle and retrieving of the last read message id
			foreach ($chatBoxesIDs as $chatBoxID) {
				// Select query for the latest read message for the currently opened chatboxes
				$query = "SELECT " .
						 $this->_db->quoteName('id') .
						 "\n FROM " . $this->_db->quoteName('#__jchat') .
						 "\n WHERE " . $this->_db->quoteName('from') . " = " . $this->_db->quote($this->userSessionTable->session_id) .
						 "\n AND " . $this->_db->quoteName('to') . " = " . $this->_db->quote($chatBoxID) .
						 "\n AND " . $this->_db->quoteName('read') . " = 1" .
						 "\n ORDER BY " .
						 $this->_db->quoteName('sent') . " DESC" . "," .
						 $this->_db->quoteName('id') . " DESC" .
						 "\n LIMIT 1";
				$this->_db->setQuery($query);
				$lastMessageID = $this->_db->loadResult();

				if($lastMessageID) {
					$this->response['lastreadmessages'][$chatBoxID] = $lastMessageID;
				}
			}
		}
	}
	
	/**
	 * Manage users buddylist 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function getBuddyList() { 
		$filter = JFilterInput::getInstance();
		$buddyList = false;
		$userFieldName = $filter->clean($this->componentParams->get('usefullname', 'username'), 'word');
		$searchFilter = $this->getState('searchfilter');
		$forceRefresh = $this->getState('force_refresh') ? true : false;

		//Prendiamo il time per eventuale aggiornamento lista utenti buddylist
		$time = time();
	
		// A valid buddylist refresh timeout is detected, so go on
		if ((empty($_SESSION['jchat_buddytime'])) || ($_POST['initialize'] == 1 || ($forceRefresh)) ||
	       (!empty($_SESSION['jchat_buddytime']) && ($time-$_SESSION['jchat_buddytime'] > $this->componentParams->get('chatrefresh', 2) * 2.5))) {
			
       		$queryParts = array();
       		$queryParts['SELECT'] = '';
       		$queryParts['JOIN'] = '';
       		$accessLevels['JOIN'] = '';
       		$additionalAND = null;
       		$accessLevelsAND = null;
       		$this->response['my_avatar'] = JChatHelpersUsers::getAvatar($this->userSessionTable->session_id);
       		
	       	// LEFT JOIN per group chat status: validcontact = utente è un mio contatto, validowner = utente è owner del mio contatto, stabilisce lo stato/colore delle icone nella buddylist
       		$queryPartsContacts['SELECT'] = "\n, fbc.contactid AS validcontact, fbch.ownerid AS validowner";
       		$queryPartsContacts['JOIN'] = "\n LEFT JOIN #__jchat_public_sessionrelations AS fbc ON fbc.contactid = sess.session_id AND fbc.ownerid = " . $this->_db->quote($this->userSessionTable->session_id) .
	       								  "\n LEFT JOIN #__jchat_public_sessionrelations AS fbch ON fbch.ownerid = sess.session_id AND fbch.contactid = " . $this->_db->quote($this->userSessionTable->session_id);
	       	
       		// Logic for Guest users
	       	$guestMode = $this->componentParams->get('guestenabled', false);
	       	if($guestMode) {
	       		$logicJOIN = 'LEFT';
	       		$joinAND = 'AND u.block = 0';
	       		$logicAND = 'AND sess.client_id = 0';
	       	} else {
	       		$logicJOIN = 'INNER';
	       		$joinAND = 'AND u.block = 0';
	       		$logicAND = 'AND sess.guest = 0 AND sess.client_id = 0';
	       	}

	       	// Search filter
       		if($searchFilter && $searchFilter != JText::_('COM_JCHAT_SEARCH')) {
       			$logicAND .= " AND (u.$userFieldName LIKE '%" . $searchFilter . "%'OR ccs.override_name LIKE '%" . $searchFilter . "%')";
       		}
       		
			// Evaluate ONLY FRIENDS 3PD integration option
       		if($this->componentParams->get('3pdintegration', null) && $this->componentParams->get('filter_friendship', false)) {
       			$queryPartsFriends = $this->getQueryParts('buddylist');
       		} else {
       			$queryPartsFriends['JOIN'] = '';
       			$queryPartsFriends['WHERE'] = '';
       		}
       		
       		// Logic for banned users
       		if($this->componentParams->get('usersbanning', false)) {
       			$queryPartsBannedUsers['SELECT'] = "\n, bant.banned, ban.banning";
       			$queryPartsBannedUsers['JOIN'] = "\n LEFT JOIN #__jchat_banned_users AS bant ON bant.banned = sess.session_id" .
       											 "\n AND bant.banning = " . $this->_db->quote($this->userSessionTable->session_id) . // Users i'm banning to
       											 "\n LEFT JOIN #__jchat_banned_users AS ban ON ban.banning = sess.session_id" .
												 "\n AND ban.banned = " . $this->_db->quote($this->userSessionTable->session_id); // Users i'm banned from
       		} else {
       			$queryPartsBannedUsers['SELECT'] = '';
       			$queryPartsBannedUsers['JOIN'] = '';
       		}
       		
       		// Manage live support mode filtering 
       		list($queryParts['JOIN'], $additionalAND) = $this->getQueryLiveSupport ();

       		// Manage chat filtering by access levels
       		list($accessLevels['JOIN'], $accessLevelsAND) = $this->getQueryAccessLevels();

	  		$sql = 	"SELECT u.id, u.$userFieldName, sess.time AS lastactivity, sess.session_id AS loggedin, ccs.status," .
	  				"\n ccs.sessionid AS session_sessid, ccs.roomid AS session_roomid, ccs.override_name, su.roomid AS user_roomid," .
	  				"\n CASE WHEN su.skypeid IS NOT NULL THEN su.skypeid ELSE ccs.skypeid END AS skypeid," .
					"\n MAX( fb.sent) AS lastmessagetime" . 
					$queryPartsContacts['SELECT'] .
					$queryPartsBannedUsers['SELECT'] .
					"\n FROM #__session AS sess" .
	  				"\n $logicJOIN JOIN #__users AS u ON sess.userid = u.id $joinAND".
	  				$queryPartsFriends['JOIN'] .
					"\n LEFT JOIN #__jchat_sessionstatus AS ccs ON sess.session_id = ccs.sessionid".
					"\n LEFT JOIN #__jchat_userstatus AS su ON u.id = su.userid".
					"\n LEFT JOIN #__jchat AS fb ON sess.session_id = fb.from".
	  				$queryParts['JOIN'] .
	  				$accessLevels['JOIN'] .
	  				$queryPartsContacts['JOIN'] .
	  				$queryPartsBannedUsers['JOIN'] .
					"\n WHERE sess.session_id <> " . $this->_db->quote($this->userSessionTable->session_id) .
					" $logicAND " .
					$additionalAND .
					$accessLevelsAND .
					$queryPartsFriends['WHERE'] .
					"\n AND ($time - sess.time) < " . (int)$this->componentParams->get('maxinactivitytime', 30) . 
					"\n GROUP BY sess.session_id" .
					"\n ORDER BY u.$userFieldName ASC";
	
			$this->_db->setQuery($sql);
			$rows = $this->_db->loadAssocList();
			 
			if(is_array($rows) && count($rows)) {
				foreach ($rows as $chat) { 
					// Bypass skipping users not joined nor as members and nor as form if guest mode is based on form join
					if($guestMode == 2 && !$chat['id'] && !$chat['session_sessid']) {continue;}
					
					// LOGIC OVERRIDES dello status utente
					if(!is_null($chat['status']) && $chat['status'] == 'offline') {
						$chat['status'] = 'offline'; 
					} elseif (!$this->componentParams->get('forceavailable', 0) && (($time-$chat['lastmessagetime']) > $this->componentParams->get('lastmessagetime', 60)) && ($chat['status'] == 'available' || is_null($chat['status'])) && $chat['lastmessagetime']) {
						// lo consideriamo offline anche se è inattivo da un periodo di tempo e lo status sarebbe available o neutro
						$chat['status'] = 'away|' . $this->convertToDaysHoursMins($time-$chat['lastmessagetime']); 
					} else {
						// Se il forceavailable è on si imposta a available per default se non già presente
						if(is_null($chat['status'])) {
							$chat['status'] = 'available'; 
						}
					}
					
					// Get current cycled user avatar
					$chat['avatar'] = JChatHelpersUsers::getAvatar($chat['loggedin']);
					
					// Guest name override: user field name -> override name -> auto generated
					if(!$chat[$userFieldName]) {
						if(!$chat['override_name']) {
							$chat[$userFieldName] = JChatHelpersUsers::generateRandomGuestNameSuffix($chat['loggedin'], $this->componentParams);
						} else {
							$chat[$userFieldName] = $chat['override_name'];
						}
					}
					
					$buddyList[$chat['loggedin']] = array('id' => $chat['loggedin'],
														  'name' => $chat[$userFieldName],
														  'avatar' => $chat['avatar'],
														  'status' => $chat['status'],
														  'time' => $chat['lastactivity'], 
														  'iscontact' => $chat['validcontact'],
														  'isowner' => @$chat['validowner'],
														  'isbanned' => @(bool)$chat['banned'],
														  'imbanned' => @(bool)$chat['banning'],
														  'skypeid' => $chat['skypeid'],
														  'isguest' => @!$chat['id'],
														  'loggedid' => @$chat['id'],
														  'hasroomid' => @(bool)($chat['session_roomid'] || $chat['user_roomid']),
														  'lastmessagetime' => $chat['lastmessagetime'],
														  'profilelink' => $this->getUserProfileLink($chat['id'], $chat[$userFieldName], $this->componentParams)
					 );
			 	} 
			}
			
		 	//Riaggiorniamo il time in sessione dell'ultimo refresh lista utenti
			$_SESSION['jchat_buddytime'] = $time;
	
			if (!empty($buddyList)) {
				$this->response['buddylist'] = $buddyList;
					// Iniettiamo anche un array di ID crudo
				if(is_array($buddyList) && count($buddyList)) {
					foreach ($this->response['buddylist'] as $value) {
						$this->response['buddylist_ids'][] = $value['id'];
					} 
				}
			} else {
				$this->response['buddylist'] = false;
			}
		    // Top scope JS side - Evaluate if user is logged in and has a username from db
		    if(!$this->myUser->$userFieldName) {
				$this->response['my_username'] = JChatHelpersUsers::generateRandomGuestNameSuffix($this->userSessionTable->session_id, $this->componentParams);
		    } else {
				$this->response['my_username'] = $this->myUser->$userFieldName;  
		    }
		    
		    // Refresh chatrooms list with users number currently joined
		    $this->getChatRoomsList($buddyList);
		    
		    // Generate a chatroom users list to show under tooltip
		    $this->getMyChatRoomUsers($buddyList);

		    // Refresh latest read message for currently opened chatboxes
		    if($this->componentParams->get('lastreadmessage', true)) {
				$this->getLatestReadMessage();
		    }
		}
	}
	
	/**
	 * Fetch private messages
	 * 
	 * @access protected
	 * @return void
	 */
	protected function fetchMessages() { 
		$toOpenChatBoxes = null;
		$openChatBoxesString = isset($_SESSION ['jchat_sessionvars']['activeChatboxes']) ? $_SESSION ['jchat_sessionvars']['activeChatboxes'] : null ;
		if($openChatBoxesString) {
			$toOpenChatBoxes = array();
			$chunks = explode(',', $openChatBoxesString);
			foreach ($chunks as $chunk) {
				$toOpenChatBoxes[] = @$this->_db->quote(array_shift(explode('|', $chunk)));
			}
			if($toOpenChatBoxes) {
				$toOpenChatBoxes = implode (',', $toOpenChatBoxes);
			}
		}
		$initialize = $this->getState('initialize');
		$lastNewMessageID = null;
		$lastReceivedMsgID = $this->getState('last_received_msg_id');
		 
		$filter = JFilterInput::getInstance();
		$userFieldName = $filter->clean($this->componentParams->get('usefullname', 'username'), 'word');
		 
		$queryParts = array();
		$queryParts['SELECT'] = '';
		$queryParts['JOIN'] = '';
		$queryParts['WHERE'] = '';
		
		// Logic for banned users
		if($this->componentParams->get('usersbanning', false)) {
			$queryParts['WHERE'] = "\n AND cchat.from NOT IN(" .
								   "\n SELECT " . $this->_db->quoteName('banned') .
								   "\n FROM " . $this->_db->quoteName('#__jchat_banned_users') .
								   "\n WHERE " . $this->_db->quoteName('banning') . " = " . $this->_db->quote($this->userSessionTable->session_id). ")";
		}

		$sql = "SELECT cchat.id, cchat.from, cchat.to, cchat.message," .
				"\n cchat.sent, cchat.read, cchat.type, cchat.status, u.id AS userid, sess.session_id AS loggedin, u.$userFieldName AS fromuser" . $queryParts['SELECT'] .
				"\n FROM #__jchat AS cchat" .
				"\n INNER JOIN #__session AS sess ON cchat.from = sess.session_id" .
				"\n LEFT JOIN #__users AS u ON sess.userid = u.id" .
				$queryParts['JOIN'] .
				"\n WHERE (cchat.to = ". $this->_db->quote($this->userSessionTable->session_id) . " AND cchat.read != 1" . $queryParts['WHERE'] . ")";
				if (!$initialize && $toOpenChatBoxes && $lastReceivedMsgID > 0) {
					$sql .= "\n OR (cchat.from = ". $this->_db->quote($this->userSessionTable->session_id) . " AND cchat.to IN ( " . $toOpenChatBoxes . " ) " .
							"\n AND cchat.id > $lastReceivedMsgID AND cchat.type='file' AND cchat.clientdeleted = 0)";
				}
				$sql .="\n ORDER BY cchat.id";
				
				
		$myFile = "logbit.txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, UTF8_ENCODE($sql)."\n");
		fclose($fh);		
			
			
	 
		$this->_db->setQuery($sql);
	 	$rows = $this->_db->loadAssocList();

		// Cycle old messages in session and check if avatars still exist or have been deleted in the meanwhile
	 	if($this->componentParams->get('advanced_avatars_mgmt', false)) {
		 	$this->refreshSessionMessagesAvatars($this->messages);
	 	}

	 	if(is_array($rows) && count($rows)) {
		 	// Add new received messages on stream if any
		 	foreach ($rows as $chatmessage) {
		 		$self = 0;
				$old = 0;
				if ($chatmessage['from'] == $this->userSessionTable->session_id) {
					$chatmessage['from'] = $chatmessage['to'];
					$self = 1;
					$old = 1;
				}
					
				// Get user avatar on the fly for
				$chatmessage['avatar'] = JChatHelpersUsers::getAvatar($chatmessage['loggedin']);
				
				// Get profile link
				$chatmessage['profilelink'] = $this->getUserProfileLink($chatmessage['userid'], $chatmessage['fromuser'], $this->componentParams);
				
				// Guest name override: user field name -> override name -> auto generated
				if(!$chatmessage['fromuser']) {
					$chatmessage['fromuser'] = JChatHelpersUsers::generateRandomGuestNameSuffix($chatmessage['loggedin'], $this->componentParams);
				}
				
				$messageUserTime = JHtml::_('date', $chatmessage['sent'], JText::_('DATE_FORMAT_LC2'));
				$this->messages[] = array( 'id' => $chatmessage['id'],
										   'from' => $chatmessage['from'], 
										   'fromuser' => @$chatmessage['fromuser'],
										   'avatar' => $chatmessage['avatar'],
										   'profilelink' => @$chatmessage['profilelink'],
										   'message' => stripslashes($chatmessage['message']),
										   'type' => @$chatmessage['type'],
										   'status' => @$chatmessage['status'],
										   'time' => $messageUserTime,
										   'self' => $self,
										   'old' => $old);
				
				// Store new streamed messages into session if not own messages, old messages and already read
				if ($self == 0 && $old == 0 && $chatmessage['read'] != 1) {
					$_SESSION['jchat_user_'.$chatmessage['from']][$chatmessage['id']] = array('id' => $chatmessage['id'],
																							  'from' => $chatmessage['from'],
																							  'fromuser' => @$chatmessage['fromuser'],
																							  'avatar' => $chatmessage['avatar'],
																							  'userid' => @$chatmessage['loggedin'],
																							  'profilelink' => @$chatmessage['profilelink'],
																							  'message' => stripslashes($chatmessage['message']),
																							  'type' => @$chatmessage['type'],
																							  'status' => @$chatmessage['status'],
																							  'time' => $messageUserTime,
																							  'self' => 0,
																							  'old' => 1);
				}
				$lastNewMessageID = $chatmessage['id'];
		 	}
	 	}
	 
	 	// Now update status of all messages received till latest new message as read status
		if ($lastNewMessageID) {
			$sql = "UPDATE #__jchat SET `read` = '1' WHERE `to` = " .
				    $this->_db->quote($this->userSessionTable->session_id) . " and `id` <= " . $this->_db->quote($lastNewMessageID); 
				 
			$this->_db->setQuery($sql); 
			$this->_db->execute();
		}
		
		// Do autorefresh realtime for messages type=file status
		$this->refreshMsgFileSessionStatus($this->response);
	}
	
	
	/**
	 * Fetch public group messages
	 * 
	 * @access protected
	 * @return void
	 */
	protected function fetchWallMessages() {
		$filter = JFilterInput::getInstance();
		$userFieldName = $filter->clean($this->componentParams->get('usefullname', 'username'), 'word');
		
		$wallHistory = $this->getState('wallhistory', false);
		$chatRoomDelay = 0;
		$queryParts = array();
		$logicAND = null;
		$queryParts['SELECT'] = '';
		$queryParts['JOIN'] = '';
		$queryParts['WHERE'] = '';
		$joinSession = 'INNER';
		$excludeMyMessageAND = " AND cchat.from != " . $this->_db->quote($this->userSessionTable->session_id);
		$excludeReadMessageAND = "\n AND cchat.id NOT IN (SELECT messageid FROM #__jchat_public_readmessages WHERE sessionid = " . $this->_db->quote($this->userSessionTable->session_id) . ")";
		
		// Check additional timing for just joined to chatroom users
		if(isset($_SESSION['jchat_justjoined']) && $this->componentParams->get('chatrooms_latest', 1)) {
			$chatRoomDelay = $this->componentParams->get('chatrooms_latest_interval', 120);
			unset($_SESSION['jchat_justjoined']);
		}
		
		// Add special chat room delay if wall history is requested
		if($wallHistory) {
			$chatRoomDelay = $this->componentParams->get('wall_history_delay', 1) * 60 * 60 * 24;
			$joinSession = 'LEFT';
			$excludeMyMessageAND = null;
			$excludeReadMessageAND = null;
		}
		
		// Reserved to chatroom filtering
		$queryParts['CHATROOM_AND'] = '';
		
		// Group chat mode management
		if($this->componentParams->get('groupchatmode', 'chatroom') == 'invite') {
			$logicAND = " AND cchat.from IN (SELECT contactid FROM #__jchat_public_sessionrelations" .
						"\n WHERE ownerid = " . $this->_db->quote($this->userSessionTable->session_id) . ")" .
						" AND cchat.from IN (SELECT ownerid FROM #__jchat_public_sessionrelations" .
						"\n WHERE contactid = " . $this->_db->quote($this->userSessionTable->session_id) . ")";
		} elseif($this->componentParams->get('groupchatmode', 'chatroom') == 'chatroom') {
			// If detected a valid user chatroom that belongs to, proceed with filtering of incoming messages from other users in same chatroom
			if($this->myChatRoom) {
				$queryParts['CHATROOM_AND'] = "\n AND cchat.sentroomid = " . (int)$this->myChatRoom;
				// Filter also by current users in this chatroom, let download chatroom messages only from user still in that chatroom
				if($this->componentParams->get('chatrooms_messages_stillinroom', 0)) {
					$queryParts['CHATROOM_AND'] .=  "\n AND (cchat.from IN(SELECT DISTINCT sessionstate.sessionid" .
													"\n FROM #__jchat_sessionstatus AS sessionstate" .
													"\n WHERE sessionstate.roomid = " . (int)$this->myChatRoom . ")" .
													"\n OR cchat.from IN(SELECT DISTINCT session.session_id" .
													"\n FROM #__session AS session" .
													"\n INNER JOIN #__jchat_userstatus AS userstate" .
													"\n ON session.userid = userstate.userid" .
													"\n WHERE session.client_id = 0 AND userstate.roomid = " . (int)$this->myChatRoom . "))";
				}
			} else {
				// If user doesn't belong to any chatroom, proceed with filtering of incoming messages from other users in no one chatrooms
				$queryParts['CHATROOM_AND'] = "\n AND (cchat.sentroomid = 0 OR cchat.sentroomid IS NULL)";
				// Filter also by current users in no chatroom, let download messages only from user still in no chatrooms
				if($this->componentParams->get('chatrooms_messages_stillinroom', 0)) {
					$queryParts['CHATROOM_AND'] .= "\n AND (cchat.from NOT IN(SELECT DISTINCT sessionstate.sessionid" .
												   "\n FROM #__jchat_sessionstatus AS sessionstate" .
												   "\n WHERE sessionstate.roomid > 0)" .
												   "\n AND cchat.from NOT IN(SELECT DISTINCT session.session_id" .
												   "\n FROM #__session AS session" .
												   "\n INNER JOIN #__jchat_userstatus AS userstate" .
												   "\n ON session.userid = userstate.userid" .
												   "\n WHERE session.client_id = 0 AND userstate.roomid > 0))";
				}
			}
			
			// Always limit stream of messages to live support admin if active
			list($queryParts['JOIN'], $logicAND) = $this->getQueryLiveSupport ();
		} else {
			// Always limit stream of messages to live support admin if active
			list($queryParts['JOIN'], $logicAND) = $this->getQueryLiveSupport ();
		}
		
		// Evaluate ONLY FRIENDS 3PD integration option
		if($this->componentParams->get('3pdintegration', null) && $this->componentParams->get('filter_friendship', false)) {
			$queryPartFriends = $this->getQueryParts('groupmessages');
			$logicAND .= $queryPartFriends['AND'];
		}
		
		// Logic for banned users
		if($this->componentParams->get('usersbanning', false) && $this->componentParams->get('usersbanning_mode', 'private') == 'private_public') {
			$queryParts['WHERE'] =  "\n AND cchat.from NOT IN(" .
									"\n SELECT " . $this->_db->quoteName('banned') .
									"\n FROM " . $this->_db->quoteName('#__jchat_banned_users') .
									"\n WHERE " . $this->_db->quoteName('banning') . " = " . $this->_db->quote($this->userSessionTable->session_id). ")";
		}
		
		$sql = "SELECT DISTINCT cchat.id, cchat.from, cchat.from AS loggedin, cchat.to, cchat.message," .
				"\n cchat.sent, cchat.read, u.id AS userid, u.$userFieldName AS fromuser, cchat.actualfrom" . $queryParts['SELECT'] .
				"\n FROM #__jchat AS cchat" .
				"\n " . $joinSession .  " JOIN #__session AS sess ON cchat.from = sess.session_id" .
				"\n LEFT JOIN #__users AS u ON sess.userid = u.id" . $queryParts['JOIN'] .
				"\n WHERE cchat.to = " . $this->_db->quote(0) .
				$excludeMyMessageAND .
				$logicAND .
				$queryParts['CHATROOM_AND'] .
				"\n AND cchat.sent > " . (time() - $this->componentParams->get('maxtimeinterval_groupmessages', 12) - $chatRoomDelay) .
				$excludeReadMessageAND .
				$queryParts['WHERE'] .
				"\n ORDER BY cchat.id"; 
		$this->_db->setQuery($sql);
	 	$rows = $this->_db->loadAssocList();
	  
	 	// Cycle old messages in session and check if avatars still exist or have been deleted in the meanwhile
	 	if($this->componentParams->get('advanced_avatars_mgmt', false)) {
		 	$this->refreshSessionMessagesAvatars($this->wallMessages, 'wall');
	 	}
	 	
	 	if(is_array($rows) && count($rows)) {
		 	// Add new received messages on stream if any
		 	foreach ($rows as $chatmessage) {
		 		$self = 0;
				$old = 0; 

				if ($chatmessage['from'] == $this->userSessionTable->session_id) {
					$self = 1;
				}
				
				// Get user avatar
				$chatmessage['avatar'] = JChatHelpersUsers::getAvatar($chatmessage['loggedin']);
				
				// Get profile link
				$chatmessage['profilelink'] = $this->getUserProfileLink($chatmessage['userid'], $chatmessage['fromuser'], $this->componentParams);
				
				// Guest name override: user field name -> override name -> auto generated
				if(!$chatmessage['fromuser']) {
					if($chatmessage['actualfrom']) {
						$chatmessage['fromuser'] = $chatmessage['actualfrom'];
					} else {
						$chatmessage['fromuser'] = JChatHelpersUsers::generateRandomGuestNameSuffix($chatmessage['loggedin'], $this->componentParams);
					}
				}
				
				$messageUserTime = JHtml::_('date', $chatmessage['sent'], JText::_('DATE_FORMAT_LC2'));

				$this->wallMessages[] = array(  'id' => $chatmessage['id'],
										 		'from' => 'wall',
										 		'fromuserid' => @$chatmessage['from'], 
										 		'fromuser' => @$chatmessage['fromuser'],
												'avatar' => @$chatmessage['avatar'],
										 		'profilelink' => @$chatmessage['profilelink'],
										 		'message' => stripslashes($chatmessage['message']),
												'time' => $messageUserTime,
										 		'self' => $self,
										 		'old' => $old); 
				
				$_SESSION['jchat_user_wall'][$chatmessage['id']] = array ( 'id' => $chatmessage['id'],
																		   'from' => 'wall',
																		   'fromuserid' => @$chatmessage['from'],
																		   'fromuser' => @$chatmessage['fromuser'],
																		   'avatar' => @$chatmessage['avatar'],
																		   'userid' => @$chatmessage['loggedin'],
																		   'profilelink' => @$chatmessage['profilelink'],
																		   'message' => stripslashes($chatmessage['message']),
																		   'time' => $messageUserTime,
																		   'self' => $self,
																		   'old' => 1);
				// Store new public streamed messages into session
				$sql = "INSERT IGNORE INTO #__jchat_public_readmessages VALUES(" . (int)$chatmessage['id'] . "," . $this->_db->quote($this->userSessionTable->session_id) . ")"; 
				$this->_db->setQuery($sql); 
				$this->_db->execute();  
		 	} 
	 	}
	}
	
	/**
	 * Get typing status for chatbox users that are writing to this me user
	 *
	 * @access protected
	 * @return void
	 */
	protected function fetchTypingStatus() {
		// Load all typing users to my session id, currently active, if none save bandwidth and avoid response
		$query = "SELECT " .
				 $this->_db->quoteName('sessionid') . "," .
				 $this->_db->quoteName('typing_to') .
				 "\n FROM " . $this->_db->quoteName('#__jchat_sessionstatus') .
				 "\n WHERE" .
				 "\n " . $this->_db->quoteName('typing') . " = 1" .
				 "\n AND " . $this->_db->quoteName('typing_to') . " = " . $this->_db->quote($this->userSessionTable->session_id);
		$this->_db->setQuery($query);
		$results = $this->_db->loadAssocList('sessionid');
	
		$this->response['typing_status'] = $results;
	}
	
	/**
	 * Refresh session messages avatars, based on avatar changes managed by frontend users,
	 * both avatar changes or avatar delete
	 *
	 * @access protected
	 * @param array& $messages
	 * @param string $messageType Switch to evaluate single user or wall messages in session
	 * @return void
	 */
	protected function refreshSessionMessagesAvatars(&$messages, $messageType = 'user') {
		$avatarCache = array();
		$destination = null;
		if(is_array($messages) && count($messages)) {
			// Cycle on all messages
			foreach ($messages as $msgIndex=>&$sessionMessage) {
				// Forcing e smistamento in base al message type
				if($messageType == 'wall') {
					// Messagetype uguale a private user
					if($sessionMessage['from'] != 'wall') {
						continue;
					}
					$destination = 'wall';
				} else {
					// Messagetype uguale a private user
					if($sessionMessage['from'] == 'wall') {
						continue;
					}
					if(isset($sessionMessage['userid'])) {
						$destination = $sessionMessage['userid'];
					}
				}

				// Ignoriamo self message o messaggi senza to
				if(!is_null($destination) && $sessionMessage['self'] != 1) {
					// Controllo esistenza immagine SOLO SE c'è un'immagine avatar
					if(isset($sessionMessage['avatar'])) {
						if(!isset($avatarCache[$sessionMessage['userid']])) {
							if(!@file_get_contents($sessionMessage['avatar'])) {
								$sessionMessage['avatar'] = JChatHelpersUsers::getAvatar($sessionMessage['userid']);
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $sessionMessage['avatar'];
							}
							// Cache storing
							$avatarCache[$sessionMessage['userid']] = $sessionMessage['avatar'];
						} else {
							if($sessionMessage['avatar'] != $avatarCache[$sessionMessage['userid']]) {
								$sessionMessage['avatar'] = $avatarCache[$sessionMessage['userid']];
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $avatarCache[$sessionMessage['userid']];
							}
						}
					} else {
						if(!isset($avatarCache[$sessionMessage['userid']])) {
							// Perform di un controllo dei messaggi in sessione inviati senza avatar, ma adesso con avatar aggiunto dall'utente
							$isNowNewAvatar = JChatHelpersUsers::getAvatar($sessionMessage['userid']);
							$sessionMessage['avatar'] = $isNowNewAvatar;
							// Update $_SESSION
							$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $sessionMessage['avatar'];
							// Cache storing
							$avatarCache[$sessionMessage['userid']] = $sessionMessage['avatar'];
						} else {
							if($sessionMessage['avatar'] != $avatarCache[$sessionMessage['userid']]) {
								$sessionMessage['avatar'] = $avatarCache[$sessionMessage['userid']];
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $avatarCache[$sessionMessage['userid']];
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Output response to be formtatted as application/json for JS client
	 * 
	 * @access protected
	 * @param array& $messages
	 * @param array& $wallMessages
	 * @return void
	 */
	protected function returnFormattedResponse(&$messages, &$wallMessages = array()) { 
		if (! empty ( $messages )) {
			$this->response ['messages'] = $messages;
		}
		
		if(! empty ( $wallMessages)) {
			$this->response ['wallmessages'] = $wallMessages;
		}
		
		return $this->response;
	}
	
	/**
	 * Updates the user status for typing
	 *
	 * @access protected
	 * @param boolean $typingStatus
	 * @param string $typingTo
	 * @return boolean
	 */
	protected function updateTypingStatus($typingStatus, $typingTo) {
		$query = "INSERT INTO #__jchat_sessionstatus (sessionid, " .
				$this->_db->quoteName('typing') . "," .
				$this->_db->quoteName('typing_to') . ") VALUES (" .
				$this->_db->quote($this->userSessionTable->session_id) . ", " .
				$this->_db->quote($typingStatus) . ", " .
				$this->_db->quote($typingTo) . ") " .
				"\n ON DUPLICATE KEY UPDATE " .
				$this->_db->quoteName('typing') . " = " . $this->_db->quote($typingStatus) . "," .
				$this->_db->quoteName('typing_to') . " = " . $this->_db->quote($typingTo);
		$this->_db->setQuery($query);
	
		try {
			$this->_db->execute ();
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_TYPINGSTATUS_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (JChatException $e) {
			return false;
		} catch (Exception $e) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * Main app dispatch public responsibility
	 * Execute stream logic and return data to be formatted and sent to JS client
	 * 
	 * @access public
	 * @return Object
	 */
	public function getChatData() {
		// Local initialization
		$fbchat_user_session_messages = array();
		$fbchat_wall_session_messages = array();
		
		// Initialize model state
		$forceParams = $this->getState('getparams');
		$chatbox = $this->getState('chatbox');
		$wallbox = $this->getState('wall');
		$wallHistory = $this->getState('wallhistory');
		$buddylist = $this->getState('buddylist');
		$initialize = $this->getState('initialize');
		$update_session = $this->getState('updatesession');
		$post_sessionvars = $this->getState('sessionvars');
		
		// Send params back only on initialize AKA first ajax call
		if(!empty($this->componentParams) && $initialize) {
			$this->componentParams->set('isguest',  strval((int)!$this->myUser->id));
			$this->response['paramslist'] = $this->componentParams->toObject();
		}
		
		if (isset($_SESSION['jchat_user_' . $chatbox])) {
			$fbchat_user_session_messages = $_SESSION['jchat_user_' . $chatbox];
		}
		if (isset($_SESSION['jchat_user_wall'])) {
			$fbchat_wall_session_messages = $_SESSION['jchat_user_wall'];
		}
		
		// Assign existant session vars
		if(isset($_SESSION['jchat_sessionvars'])) {
			$fbchat_sessionvars = $_SESSION['jchat_sessionvars'];
		}
		
		if ($this->myUser->id || $this->componentParams->get('guestenabled', false)) {
			// Request for a specific chatbox message list
			if (!empty($chatbox)) {
				if (!empty($fbchat_user_session_messages)) {
					if($this->componentParams->get('advanced_avatars_mgmt', false)) {
						$this->refreshSessionMessagesAvatars($fbchat_user_session_messages);
					}
					$this->messages = $fbchat_user_session_messages;
				}
				
				// Return JS client formatted response
				return $this->returnFormattedResponse($this->messages);
			} elseif (!empty($wallbox) && !$wallHistory) {
				// Request for public chat messages
				if (!empty($fbchat_wall_session_messages)) {
					if($this->componentParams->get('advanced_avatars_mgmt', false)) {
						$this->refreshSessionMessagesAvatars($fbchat_wall_session_messages, 'wall');
					}
					$this->wallMessages = $fbchat_wall_session_messages;
				}
				
				// Return JS client formatted response
				return $this->returnFormattedResponse($this->messages, $this->wallMessages);
			} else {
				// All other regular requests
				if (!empty($buddylist) && $buddylist == 1) {
					$this->getBuddyList($initialize);
				}
		
				if (!empty($initialize) && $initialize == 1) {
					$this->getUserStateFromDB();
					// Force start opening mode
					if (empty($fbchat_sessionvars)) {
						$fbchat_sessionvars = array();
						$startOpenMode = $this->componentParams->get('start_open_mode', 1);
						$fbchat_sessionvars['buddylist'] = $startOpenMode;
					}
		
					if (!empty($fbchat_sessionvars)) {
						$this->response['initialize'] = $fbchat_sessionvars;
						$this->wallMessages = $fbchat_wall_session_messages;
					}
				} else {
					if (empty($fbchat_sessionvars)) {
						$fbchat_sessionvars = array();
					}
		
					if (!empty($post_sessionvars)) {
						ksort($post_sessionvars);
					} else {
						$post_sessionvars = array();
					}
		
					// Check if typing enabled and status has changed
					if($this->typingEnabled) {
						$sessionTyping = isset($fbchat_sessionvars['typing']) ? $fbchat_sessionvars['typing'] : null;
						$postTyping = isset($post_sessionvars['typing']) ? $post_sessionvars['typing'] : null;
						$postTypingTo = isset($post_sessionvars['typing_to']) ? $post_sessionvars['typing_to'] : null;
						if($sessionTyping != $postTyping) {
							$this->updateTypingStatus($postTyping, $postTypingTo);
						}
					}
					
					if (!empty($update_session) && $update_session == 1) {
						$_SESSION['jchat_sessionvars'] = array_merge($fbchat_sessionvars, $post_sessionvars);
					}
		
					if ($forceParams) {
						$this->response['paramslist'] = $this->componentParams->toObject();
					}
				}
		
				$this->fetchMessages();
				$this->fetchWallMessages();
				
				if($this->typingEnabled) {
					$this->fetchTypingStatus();
				}
				
				// Return JS client formatted response
				return $this->returnFormattedResponse($this->messages, $this->wallMessages);
			}
		} else {
			$this->response['loggedout'] = '1';
			if ($forceParams) {
				$this->response['paramslist'] = $this->componentParams->toObject();
			}
			
			// Return JS client formatted response
			return $this->returnFormattedResponse($this->messages);
		}
	}
	
	/**
	 * Write user status on Stream
	 * 
	 * @access public
	 * @param string $status
	 * @return array
	 */
	public function storeUserStatus($fieldName, $fieldValue) {
		$query = "INSERT INTO #__jchat_sessionstatus (sessionid, " . $this->_db->quoteName($fieldName) . ") VALUES (" .
				$this->_db->quote($this->userSessionTable->session_id) . ", " .
				$this->_db->quote($fieldValue) . ") " .
				"\n ON DUPLICATE KEY UPDATE " . $this->_db->quoteName($fieldName) . " = " . $this->_db->quote($fieldValue);
		$this->_db->setQuery($query);
		
		try {
			$this->_db->execute ();
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_USERSTATUS_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (JChatException $e) {
			$this->response['storing'] = array('status'=>false, 'details'=>$e->getMessage());
			return $this->response;
			
		} catch (Exception $e) {
			$jchatException = new JChatException($e->getMessage(), 'error');
			$this->response['storing'] = array('status'=>false, 'details'=>$jchatException->getMessage());
			return $this->response;
		}
		
		if ($fieldName == 'status' && $fieldValue == 'offline') {
			$_SESSION['jchat_sessionvars']['buddylist'] = 0;
		}
		
		$this->response['storing'] = array('status'=>true);
		
		return $this->response;
	}
	
	/**
	 * Write user status on Stream, based on user logging state
	 * 
	 * @access public
	 * @param string $statusVarName
	 * @param string $statusVarValue
	 * @return array
	*/
	public function storeUserStateFromRequest($statusVarName, $statusVarValue) {
		if(!$this->myUser->id) {
			$query = "INSERT INTO #__jchat_sessionstatus (sessionid, " . $this->_db->quoteName($statusVarName) . ") VALUES (" .
					 $this->_db->quote($this->userSessionTable->session_id) . ", " .
					 $this->_db->quote($statusVarValue) . ") " .
					 "ON DUPLICATE KEY UPDATE " . $this->_db->quoteName($statusVarName) . " = " . $this->_db->quote($statusVarValue);
		} else {
			$query = "INSERT INTO #__jchat_userstatus (userid, " . $this->_db->quoteName($statusVarName) . ") VALUES (" .
					 $this->_db->quote($this->myUser->id) . ", " .
					 $this->_db->quote($statusVarValue) . ") " .
					 "ON DUPLICATE KEY UPDATE " . $this->_db->quoteName($statusVarName) . " = " . $this->_db->quote($statusVarValue);
		}
		$this->_db->setQuery($query);
		
		try {
			$this->_db->execute ();
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_STATUS_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
			}
			
			// If user is now logged in, delete/clean/discard all data in session state
			if($this->myUser->id) {
				$cleanQuery = "DELETE FROM #__jchat_sessionstatus" . 
							  "\n WHERE " . $this->_db->quoteName('sessionid') . " = " . $this->_db->quote($this->userSessionTable->session_id);
				$this->_db->setQuery($cleanQuery)->execute ();
				if($this->_db->getErrorNum()) {
					throw new JChatException(JText::_('COM_JCHAT_ERROR_CLEANING_SESSION_STATUS_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
				}
			}
			
			// Auto insert a message 'User xxx has joined/left chat room yyy' that will be showed to right users based on receiving logic
			if($statusVarName == 'roomid' && $statusVarValue > 0) {
				$this->myChatRoom = $statusVarValue;
				$this->storeGroupMessage('wall', JText::sprintf('COM_JCHAT_JOINED', '<img class="jchat_roomenter" src="' . JUri::base() . 'components/com_jchat/images/default/room_enter.png"/>'), true);
				
				// Store just joined room state to recover latest xxx time messages of current conversation
				if($this->componentParams->get('chatrooms_latest', 1)) {
					$_SESSION['jchat_justjoined'] = true;
				}
			}
			
			// Ensure to clear the current public chat session messages
			if($statusVarName == 'roomid' && $this->componentParams->get('autoclear_conversation', 1)) {
				$_SESSION['jchat_user_wall'] = array();
			}
		} catch (JChatException $e) {
			$this->response['storing'] = array('status'=>false,  'details'=>$e->getMessage());
			return $this->response;
				
		} catch (Exception $e) {
			$jchatException = new JChatException($e->getMessage(), 'error');
			$this->response['storing'] = array('status'=>false,  'details'=>$jchatException->getMessage());
			return $this->response;
		}
				
		$this->response['storing'] = array('status'=>true);
		
		return $this->response;
	}
	
	/**
	 * Store banning state for current session id user
	 *
	 * @access public
	 * @return array
	 */
	public function storeBannedUsersState($bannedUserInfo) {
		if(!$bannedUserInfo->currentState) {
			$query = "DELETE FROM #__jchat_banned_users" .
					 "\n WHERE " .
					 $this->_db->quoteName('banning') . " = " . $this->_db->quote($this->userSessionTable->session_id) .
					 "\n AND " .
					 $this->_db->quoteName('banned') . " = " . $this->_db->quote($bannedUserInfo->userSessionId);
		} else {
			$query = "INSERT IGNORE INTO #__jchat_banned_users (" .
					 $this->_db->quoteName('banning') . ", " .
					 $this->_db->quoteName('banned') .
					 ") VALUES (" .
					 $this->_db->quote($this->userSessionTable->session_id) . ", " .
					 $this->_db->quote($bannedUserInfo->userSessionId) .
					 ")";
		}
		$this->_db->setQuery($query);

		try {
			$this->_db->execute ();
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_BANNEDUSER_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (JChatException $e) {
			$this->response['storing'] = array('status'=>false,  'details'=>$e->getMessage());
			return $this->response;

		} catch (Exception $e) {
			$jchatException = new JChatException($e->getMessage(), 'error');
			$this->response['storing'] = array('status'=>false,  'details'=>$jchatException->getMessage());
			return $this->response;
		}
			
		$this->response['storing'] = array('status'=>true);

		return $this->response;
	}
	
	/**
	 * Write private message on Stream
	 * 
	 * @access public
	 * @param string $to
	 * @param int $tologged
	 * @param string $message
	 * @param Object $mailer
	 * @return array
	*/
	public function storePrivateMessage($to, $tologged, $message, $mailer) {
		if ($this->userSessionTable->session_id) {
			// Get users actual names
			$actualNames = JChatHelpersUsers::getActualNames ( $this->userSessionTable->session_id, $to, $this->componentParams );
			
			$unixTimeStamp = time();
			$sql =  "INSERT INTO #__jchat" .
					"\n (#__jchat.from, #__jchat.to, #__jchat.fromuser, #__jchat.touser, #__jchat.message, #__jchat.sent, #__jchat.read, #__jchat.actualfrom, #__jchat.actualto) VALUES (".
					$this->_db->quote($this->userSessionTable->session_id) . ", " .
					$this->_db->quote($to) . "," .
					$this->_db->quote($this->myUser->id) . "," .
					$this->_db->quote($tologged) . "," .
					$this->_db->quote($message) . "," . 
					$this->_db->quote($unixTimeStamp) . "," .
					"0" . "," . 
					$this->_db->quote($actualNames['fromActualName']) . "," .
					$this->_db->quote($actualNames['toActualName']) . ")";
		    $this->_db->setQuery($sql);
			try {
				$this->_db->execute ();
				if($this->_db->getErrorNum()) {
					throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_PRIVATEMESSAGE_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
				}
			} catch (JChatException $e) {
				$this->response['storing'] = array('status'=>false, 'details'=>$e->getMessage());
				return $this->response;
					
			} catch (Exception $e) {
				$jchatException = new JChatException($e->getMessage(), 'error');
				$this->response['storing'] = array('status'=>false, 'details'=>$jchatException->getMessage());
				return $this->response;
			}
			
			// Send email notification if needed, AKA new conversation started and settings are required
			if(!isset($_SESSION['jchat_user_'.$to]) && $this->componentParams->get('notification_email_switcher', false)) {
				// Format and send email
				$notificationsAddresses = $this->componentParams->get('notification_email', null);
				if($notificationsAddresses) {
					$mailer->IsHTML(true);
					
					// Get single email addresses
					$exploded = explode(',', $notificationsAddresses);
					foreach ($exploded as $recipient) {
						$mailer->addRecipient(trim($recipient));
					}
					
					// Set subject
					$mailer->setSubject($this->componentParams->get('email_subject', 'JChatSocial - New conversation started'));
					
					// Format body and message
					$body = JText::sprintf('COM_JCHAT_NEWUSER_CONVERSATION', $actualNames['fromActualName'], $actualNames['toActualName']);
					$customText =JText::sprintf('COM_JCHAT_STARTING_CUSTOMTEXT_FORMATTED', $this->componentParams->get('email_start_text', ''));
					$messageText = JText::sprintf('COM_JCHAT_STARTING_MESSAGE', $actualNames['fromActualName'], $message, $customText, JURI::root());
					$mailer->setBody($body . $messageText);
					$result = $mailer->Send();
				}
			}
	
			if (empty($_SESSION['jchat_user_'.$to])) {
				$_SESSION['jchat_user_'.$to] = array();
			}
			// Store local session message
			$lastInsertId = $this->_db->insertid();
			$insertTime = JHtml::_('date', $unixTimeStamp, JText::_('DATE_FORMAT_LC2'));
			$_SESSION['jchat_user_'.$to][$lastInsertId] = array("id" => $lastInsertId, 
																"from" => $to, 
																"message" => $message, 
																"time" => $insertTime, 
																"self" => 1, 
																"old" => 1);
			$this->response['storing'] = array('status'=>true, 'details'=>array('id'=>$lastInsertId, 'time'=>$insertTime));
			
			// Ensure no typing status are more active
			$this->updateTypingStatus(null, null);
			
			return $this->response;
		} 
	}
	
	/**
	 * Write group message on Stream
	 * 
	 * @access public
	 * @param int $to
	 * @param string $message
	 * @param boolean $skipSession
	 * @return array
	*/
	public function storeGroupMessage($to, $message, $skipSession = false) {
		if ($this->userSessionTable->session_id) {
			// Get users actual names
			$actualNames = JChatHelpersUsers::getActualNames ( $this->userSessionTable->session_id, $to, $this->componentParams );

			$unixTimeStamp = time();
			$query =  "INSERT INTO #__jchat" .
					  "\n (#__jchat.from, #__jchat.to, #__jchat.message, #__jchat.sent, #__jchat.read, #__jchat.actualfrom, #__jchat.sentroomid) VALUES (".
					  $this->_db->quote($this->userSessionTable->session_id) . ", " .
					  "0" . "," .
					  $this->_db->quote($message) . "," .
					  $this->_db->quote($unixTimeStamp) . "," .
					  "0" . "," .
					  $this->_db->quote($actualNames['fromActualName']) . "," .
					  (int)$this->myChatRoom . ")";
			$this->_db->setQuery($query);
		
			try {
				$this->_db->execute ();
				if($this->_db->getErrorNum()) {
					throw new JChatException(JText::_('COM_JCHAT_ERROR_WRITING_GROUPMESSAGE_ONSTREAM') . $this->_db->getErrorMsg(), 'error');
				}
			} catch (JChatException $e) {
				$this->response['storing'] = array('status'=>false, 'details'=>$e->getMessage());
				return $this->response;
			
			} catch (Exception $e) {
				$jchatException = new JChatException($e->getMessage(), 'error');
				$this->response['storing'] = array('status'=>false, 'details'=>$jchatException->getMessage());
				return $this->response;
			}
			
			if (empty($_SESSION['jchat_user_'.$to])) {
				$_SESSION['jchat_user_'.$to] = array();
			}
			
			// Store local session message
			$lastInsertId = $this->_db->insertid();
			$insertTime = JHtml::_('date', $unixTimeStamp, JText::_('DATE_FORMAT_LC2'));
			if(!$skipSession) {
				$_SESSION['jchat_user_'.$to][$lastInsertId] = array("id" => $lastInsertId, 
																    "fromuser" => $this->myUser->username, 
																    "from" => $to, 
																    "message" => $message,
																    "time" => $insertTime,
																    "self" => 1, 
																    "old" => 1);
			}
			$this->response['storing'] = array('status'=>true,  'details'=>array('id'=>$lastInsertId, 'time'=>$insertTime));
			
			return $this->response;
		}
	}
	
	/**
	 * Delete conversation from session
	 * 
	 * @access public
	 * @param int $from
	 * @return array
	 */
	public function deleteConversation($from) {
		if (isset($_SESSION['jchat_user_'.$from])) {
			// 1) Get file messages
			if(is_array($_SESSION['jchat_user_'.$from]) && $from != 'wall') {
				$idsToUpdate = array();
				foreach ($_SESSION['jchat_user_'.$from] as $genericMsg) {
					// Select only file messages ids
					if(isset($genericMsg['type']) && $genericMsg['type'] === 'file') {
						$idsToUpdate[] = $genericMsg['id'];
					}
				}
			}
				
			// 2) Flag as clientdeleted on DB
			if(!empty($idsToUpdate)) {
				$query =  "UPDATE #__jchat SET " . $this->_db->quoteName('clientdeleted') . " = 1" .
						  "\n WHERE " . $this->_db->quoteName('id') . " IN (" . implode(',', $idsToUpdate) . ")";
				$this->_db->setQuery($query);
				try {
					$this->_db->execute ();
					if($this->_db->getErrorNum()) {
						throw new JChatException(JText::_('COM_JCHAT_ERROR_DELETE_CONVERSATION') . $this->_db->getErrorMsg(), 'error');
					}
				} catch (JChatException $e) {
					$this->response['storing'] = array('status'=>false,  'details'=>$e->getMessage());
					return $this->response;
				
				} catch (Exception $e) {
					$jchatException = new JChatException($e->getMessage(), 'error');
					$this->response['storing'] = array('status'=>false,  'details'=>$jchatException->getMessage());
					return $this->response;
				}
			}
				
			// 3) Empty session array
			$_SESSION['jchat_user_'.$from] = array();
		}
		
		$this->response['storing'] = array('status'=>true);
		
		return $this->response;
	}
	
	/**
	 * Retrieve guest info user informations, usually stored by guest activation form
	 *
	 * @access public
	 * @param string $session_id
	 * @return array
	 */
	public function getInfoGuest($session_id) {
		// Empty initialization
		$resultInfo = array();
		
		$query = "SELECT " . $this->_db->quoteName('email') . "," .
				 $this->_db->quoteName('description') .
			     "\n FROM " . $this->_db->quoteName('#__jchat_sessionstatus') .
				 "\n WHERE " . $this->_db->quoteName('sessionid') . " = " . $this->_db->quote($session_id);
		$this->_db->setQuery($query);
	
		try {
			$resultInfo = $this->_db->loadObject ();
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_RETRIEVING_INFOGUEST') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (JChatException $e) {
			$this->response = array('status'=>false, 'details'=>$e->getMessage());
			return $this->response;
				
		} catch (Exception $e) {
			$jchatException = new JChatException($e->getMessage(), 'error');
			$this->response = array('status'=>false, 'details'=>$jchatException->getMessage());
			return $this->response;
		}
	
		$this->response = array('status'=>true, 'details'=>$resultInfo);
	
		return $this->response;
	}
	
	/**
	 * Load chatrooms from DB using caching system
	 *
	 * @access private
	 * @return array
	 */
	public function loadChatRooms() {
		// Get user access levels to filter rooms access
		$viewLevels	= implode(',', $this->myUser->getAuthorisedViewLevels());
		
		$sql = "SELECT rooms.id, rooms.name, 0 AS numusers, NULL AS users," .
				"\n rooms.description, rooms.ordering" .
				"\n FROM #__jchat_rooms AS rooms" .
				"\n WHERE rooms.published = 1" .
				"\n AND rooms.access IN (" . $viewLevels . ")" .
				"\n ORDER BY rooms.ordering ASC";
	
		$this->_db->setQuery($sql);
		$rooms = $this->_db->loadAssocList('ordering');
	
		return $rooms;
	}
	
	/**
	 * Retrieve old messages based on time period and private conversation
	 * between to logged in and registered users
	 *
	 * @access public
	 * @param int $fromLoggedID
	 * @param string $fromUserID
	 * @param string $timePeriod
	 * @param int $minMessageId
	 * @return array
	 */
	public function fetchHistoryMessages($fromLoggedID, $fromUserID, $timePeriod, $minMessageId) {
		// Empty initialization
		$historyMessages = array();
		$limitMessages = null;
		
		// Calculate starting period to retrieve messages
		switch($timePeriod) {
			case '1d':
				$periodTimeSeconds = 60 * 60 * 24;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
			case '1w':
				$periodTimeSeconds = 60 * 60 * 24 * 7;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
			case '1m':
				$periodTimeSeconds = 60 * 60 * 24 * 30;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
			case '3m':
				$periodTimeSeconds = 60 * 60 * 24 * 90;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
			case '6m':
				$periodTimeSeconds = 60 * 60 * 24 * 180;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
			case '1y':
				$periodTimeSeconds = 60 * 60 * 24 * 365;
				$minMessagesTime = time() - $periodTimeSeconds;
				break;
		}
		
		try {
			$filter = JFilterInput::getInstance();
			$userFieldName = $filter->clean($this->componentParams->get('usefullname', 'username'), 'word');
			
			// Exclude already showed and exchanged messages if any
			if((bool)$minMessageId) {
				$limitMessages = "\n AND cchat.id < " . (int)$minMessageId;
			}
			
			$sql = 	"SELECT cchat.id, " .
					"\n " . $this->_db->quote($fromUserID) . " AS " . $this->_db->quoteName('from') . "," .
					"\n " . $this->_db->quote($this->userSessionTable->session_id) . " AS " . $this->_db->quoteName('to') . "," .
					"\n cchat.message, cchat.sent, cchat.read, cchat.type, cchat.status, u.id AS userid, " .
					"\n u.$userFieldName AS " . $this->_db->quoteName('fromusername') .
					"\n FROM #__jchat AS cchat" .
					"\n INNER JOIN #__users AS u ON cchat.fromuser = u.id" .
					"\n WHERE ((cchat.touser = ". $this->_db->quote($this->myUser->id) . 
					"\n AND cchat.fromuser = " . $this->_db->quote($fromLoggedID) . ")" .
					"\n OR (cchat.fromuser = ". $this->_db->quote($this->myUser->id) . 
					"\n AND cchat.touser = " . $this->_db->quote($fromLoggedID) . "))" .
					"\n AND cchat.sent > " . (int)$minMessagesTime .
					$limitMessages .
					"\n ORDER BY cchat.id ASC";
			
			$this->_db->setQuery($sql);
			$rows = $this->_db->loadAssocList();
			
			if($this->_db->getErrorNum()) {
				throw new JChatException(JText::_('COM_JCHAT_ERROR_RETRIEVING_HISTORY') . $this->_db->getErrorMsg(), 'error');
			}
			
			if(is_array($rows) && count($rows)) {
				// Add new received messages on stream if any
				foreach ($rows as $chatmessage) {
					$self = $chatmessage['userid'] == $this->myUser->id ? 1 : 0;
					
					// Get profile link
					$chatmessage['profilelink'] = $this->getUserProfileLink($chatmessage['userid'], $chatmessage['fromusername'], $this->componentParams);
			
					$messageUserTime = JHtml::_('date', $chatmessage['sent'], JText::_('DATE_FORMAT_LC2'));
					$historyMessages[] = array( 'id' => $chatmessage['id'],
												'from' => $chatmessage['from'],
												'fromusername' => @$chatmessage['fromusername'],
												'profilelink' => @$chatmessage['profilelink'],
												'message' => stripslashes($chatmessage['message']),
												'type' => @$chatmessage['type'],
												'status' => @$chatmessage['status'],
												'time' => $messageUserTime,
												'self' => $self,
												'old' => 0);
			
					// Store new streamed messages into session if not own messages, old messages and already read
					$_SESSION['jchat_user_'.$chatmessage['from']][$chatmessage['id']] = array('id' => $chatmessage['id'],
																							  'from' => $chatmessage['from'],
																							  'fromusername' => @$chatmessage['fromusername'],
																							  'profilelink' => @$chatmessage['profilelink'],
																							  'message' => stripslashes($chatmessage['message']),
																							  'type' => @$chatmessage['type'],
																							  'status' => @$chatmessage['status'],
																							  'time' => $messageUserTime,
																							  'self' => $self,
																							  'old' => 1);
				}
			}
		} catch (JChatException $e) {
			$this->response = array('status'=>false, 'details'=>$e->getMessage());
			return $this->response;
		
		} catch (Exception $e) {
			$jchatException = new JChatException($e->getMessage(), 'error');
			$this->response = array('status'=>false, 'details'=>$jchatException->getMessage());
			return $this->response;
		}
		
		$this->response = array('status'=>true, 'messages'=>$historyMessages);
		
		return $this->response;
	}

	/** 
	 * Class contructor
	 * 
	 * @access public
	 * @param $config array
	 * @return Object
	 */
	public function __construct($config = array()) {
		$this->getComponentParams();
		$this->response = array();
		$this->messages = array();
		$this->wallMessages = array();
		$this->myUser = JFactory::getUser();
		$this->integratedExtensions = $this->componentParams->get('3pdintegration', null);
		$this->typingStatusChanged = false;
		$this->typingTo = null;
		$this->typingEnabled = $this->componentParams->get('typing_enabled', true);
		
		// User session table instance
		$this->userSessionTable = $config['sessiontable'];
		
		parent::__construct($config);
		
		// Set my chatroom if chatrrom mode is enabled, for buddylist my chatroom users filtering and stream messages
		$this->myChatRoom = $this->getMyChatRoom();
	}
}