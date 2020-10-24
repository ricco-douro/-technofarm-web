<?php
/**
 * @copyright (C) 2015 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/libraries/core.php');

// All the module logic should be placed here
if(!class_exists('modcommunityprofilecompletenessHelper'))
{
    class modcommunityprofilecompletenessHelper
    {   
        public static function prepareUpdate(&$update, &$table)
        {   
            $lang = JFactory::getLanguage();
            $extension = 'com_community';
            $base_dir = JPATH_ADMINISTRATOR;
            $language_tag = '';
            $lang->load($extension, $base_dir, $language_tag, true);

            JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_COMMUNITY_PACKAGE_DOWNLOAD_UPDATE', 'https://member.joomlart.com/'), "JomSocial Module Update");
        
            JFactory::getApplication()->redirect(CRoute::_("index.php?option=com_installer&view=update", false));
        }
        
        public function getStats( $params )
        {
            $my = CFactory::getUser();
            $config = CFactory::getConfig();
            //before anything else, check if the user is logged in
            if(!$my->id){
                return false;
            }

            $db = JFactory::getDbo();

            //get all the params
            $includeRequiredField = $params->get('include_req_field',1);
            $includeNonRequiredField = $params->get('include_non_req_field',1);
            $includeAvatar = $params->get('include_avatar',1);
            $includeCover = $params->get('include_cover',1);
            $friendsNumber = $params->get('friends_number',1);
            $groupsNumber = $params->get('groups_number',1);
            $eventsNumber = $params->get('events_number',1);
            $videosNumber = $params->get('videos_number',1);
            $photosNumber = $params->get('photos_number',1);
            $postsNumber = $params->get('posts_number',1);
            $hide = $params->get('hide_when_complete',0);

            $stats = new stdClass();
            $stats->hide = false;

            $totalFields = 0;
            $completeFields = 0;
            $completeFieldMessages = array(); // add a series of message to indicates how many percent do they get for completing the field

            //check if user belongs to certain profile
            $filterProfileFieldQuery = "";
            if($config->get('profile_multiprofile') && $my->_profile_id){
                //if exists we will only get all the fields from this profile only
                $query = "SELECT field_id FROM ".$db->quoteName('#__community_profiles_fields')." WHERE "
                    .$db->quoteName('parent') ."=".$db->quote($my->_profile_id);
                $db->setQuery($query);
                $fieldIds = $db->loadColumn();
                if(count($fieldIds)>0){
                    $filterProfileFieldQuery = " AND id IN (".implode($db->quote($fieldIds),",").")";
                }

            }

            //required field checking
            if($includeRequiredField){
                //lets get all the required field
                $query = "SELECT id FROM ".$db->quoteName('#__community_fields')." WHERE "
                    .$db->quoteName('type')."<>".$db->quote('group')
                    ." AND ".$db->quoteName('required')."=".$db->quote(1)
                    ." AND ".$db->quoteName('published')."=".$db->quote(1).$filterProfileFieldQuery;

                $db->setQuery($query);
                $requiredFields = $db->loadColumn();

                $totalFields += count($requiredFields); // this is all the required fields available

                if(count($requiredFields) > 0){
                    $query = "SELECT COUNT(id) FROM ".$db->quoteName('#__community_fields_values')." WHERE "
                        .$db->quoteName('field_id')." IN (".implode(',',$requiredFields).")"
                        ." AND ".$db->quoteName('user_id')."=".$db->quote($my->id)
                        ." AND ".$db->quoteName('value')." <> ".$db->quote('');

                    $db->setQuery($query);
                    $count = $db->loadResult();
                    $completeFields += $count;

                    if($count < count($requiredFields)){
                        $completeFieldMessages[] = array(
                            'msg' => JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_COMPLETE_REQUIRED_FIELD'),
                            'incomplete'=>count($requiredFields)-$count,
                            'link'=>CRoute::_('index.php?option=com_community&view=profile&task=edit')
                        );
                    }
                }
            }

            //non-required field checking
            if($includeNonRequiredField){
                //lets get all the non-required field
                $query = "SELECT id FROM ".$db->quoteName('#__community_fields')." WHERE "
                    .$db->quoteName('type')."<>".$db->quote('group')
                    ." AND ".$db->quoteName('required')."=".$db->quote(0)
                    ." AND ".$db->quoteName('published')."=".$db->quote(1).$filterProfileFieldQuery;

                $db->setQuery($query);
                $requiredFields = $db->loadColumn();

                $totalFields += count($requiredFields); // this is all the required fields available

                if(count($requiredFields) > 0){
                    $query = "SELECT COUNT(id) FROM ".$db->quoteName('#__community_fields_values')." WHERE "
                        .$db->quoteName('field_id')." IN (".implode(',',$requiredFields).")"
                        ." AND ".$db->quoteName('user_id')."=".$db->quote($my->id)
                        ." AND ".$db->quoteName('value')." <> ".$db->quote('');

                    $db->setQuery($query);
                    $count = $db->loadResult();
                    $completeFields += $count;

                    if($count < count($requiredFields)){
                        $completeFieldMessages[] = array(
                            'msg' => JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_COMPLETE_NONREQUIRED_FIELD'),
                            'incomplete'=>count($requiredFields)-$count,
                            'link'=>CRoute::_('index.php?option=com_community&view=profile&task=edit')
                        );
                    }

                }
            }

            //avatar
            if($includeAvatar){
                $totalFields++;

                //check if user is using default avatar
                if(!$my->isDefaultAvatar()){
                    //if not, increase the complete fields
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_PROFILE_AVATAR'),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=profile')
                    );
                }
            }

            //cover
            if($includeCover){
                $totalFields++;

                if(!$my->isDefaultCover()){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_PROFILE_COVER'),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=profile')
                    );
                }
            }

            //friends number
            if($friendsNumber){
                $totalFields++;

                if($friendsNumber <= $my->getFriendCount()){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($friendsNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_FRIENDS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_FRIEND'),$friendsNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=friends')
                    );
                }
            }

            // groups number
            if($groupsNumber){
                $totalFields++;

                $query = "SELECT count(groupid) FROM ".$db->quoteName('#__community_groups_members')." WHERE "
                    .$db->quoteName('memberid')."=".$db->quote($my->id)
                    ." AND ".$db->quoteName('permissions')."=".$db->quote(1);

                $db->setQuery($query);

                $totalGroups = $db->loadResult();

                if($totalGroups >= $groupsNumber){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($groupsNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_GROUPS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_GROUP'),$groupsNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=groups&task=mygroups')
                    );
                }
            }

            //events number
            if($eventsNumber){
                $totalFields++;

                $query = "SELECT count(id) FROM ".$db->quoteName('#__community_events_members')." WHERE "
                    .$db->quoteName('memberid')."=".$db->quote($my->id)
                    ." AND ".$db->quoteName('status')."=".$db->quote(1);

                $db->setQuery($query);

                $totalEvents = $db->loadResult();

                if($totalEvents >= $eventsNumber){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($eventsNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_EVENTS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_EVENT'),$eventsNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=events&task=myevents')
                    );
                }
            }

            //photos number
            if($photosNumber){
                $totalFields++;

                $query = "SELECT count(id) FROM ".$db->quoteName('#__community_photos')." WHERE "
                    .$db->quoteName('creator')."=".$db->quote($my->id)
                    ." AND ".$db->quoteName('published')."=".$db->quote(1);

                $db->setQuery($query);

                $totalPhotos = $db->loadResult();

                if($totalPhotos >= $photosNumber){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($photosNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_PHOTOS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_PHOTO'),$photosNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=photos&task=myphotos')
                    );
                }
            }

            //videos number
            if($videosNumber){
                $totalFields++;

                $query = "SELECT count(id) FROM ".$db->quoteName('#__community_videos')." WHERE "
                    .$db->quoteName('creator')."=".$db->quote($my->id)
                    ." AND ".$db->quoteName('published')."=".$db->quote(1);

                $db->setQuery($query);

                $totalVideos = $db->loadResult();

                if($totalVideos >= $videosNumber){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($videosNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_VIDEOS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_VIDEO'),$videosNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=videos&task=myvideos')
                    );
                }
            }

            //posts number
            if($postsNumber){
                $totalFields++;

                $query = "SELECT count(id) FROM ".$db->quoteName('#__community_activities')." WHERE "
                    .$db->quoteName('actor')."=".$db->quote($my->id)
                    ." AND ".$db->quoteName('verb')."=".$db->quote('post')
                    ." AND ".$db->quoteName('app')."=".$db->quote('profile');

                $db->setQuery($query);

                $totalPosts = $db->loadResult();

                if($totalPosts >= $postsNumber){
                    $completeFields++;
                }else{
                    $completeFieldMessages[] = array(
                        'msg' => JText::sprintf((CStringHelper::isPlural($postsNumber) ? 'MOD_COMMUNITY_PROFILECOMPLETENESS_POSTS' : 'MOD_COMMUNITY_PROFILECOMPLETENESS_POST'),$postsNumber),
                        'incomplete'=>1,
                        'link'=>CRoute::_('index.php?option=com_community&view=frontpage')
                    );
                }

            }

            /*
            if($completeFields == $totalFields && $hide){
                //this means everything is completed and hide when complete is set to true
                $stats->hide = true;
            }*/

            $totalCompletionPercent = 0;
            foreach($completeFieldMessages as &$message){
                $message['completePercentage'] = floor($message['incomplete']/$totalFields*100);
                $totalCompletionPercent += $message['completePercentage'];
            }

            //lets calculate the percentage of completion.
            $stats->completePercentage = 100 - $totalCompletionPercent;

            $stats->completionMessages = $completeFieldMessages; // all the messages that need to be displayed to the user to complete their profile
            $stats->total = $totalFields;

            return $stats;
        }
    }
}
