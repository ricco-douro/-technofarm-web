<?php
    /**
     * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
     * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
     * @author iJoomla.com <webmaster@ijoomla.com>
     * @url https://www.jomsocial.com/license-agreement
     * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
     * More info at https://www.jomsocial.com/license-agreement
     */
    defined('_JEXEC') or die();

    $currentTask = JFactory::getApplication()->input->getCmd('task');
    $task = JFactory::getApplication()->input->getCmd('task');

    if ($task == 'online') {
        $title = JText::_('COM_COMMUNITY_FRIENDS_ONLINE');
    } else {
        $title = JText::_('COM_COMMUNITY_FRIENDS');
    }
?>

<?php // echo $sortings; ?>

<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title">
            <h3 class="joms-page__title"><?php echo $title; ?></h3>
        </div>

        <div class="joms-list__utilities">
            <form method="get" class="joms-inline--desktop">
                <span>
                    <input type="text" name="q" class="joms-input--search" value="<?php echo ($searchQuery) ? $searchQuery : ''; ?>"
                       placeholder="<?php echo JText::_('COM_COMMUNITY_SEARCH_FRIENDS'); ?>">
                </span>
                <span>
                    <button class="joms-button--neutral"><?php echo JText::_('COM_COMMUNITY_SEARCH_GO'); ?></button>
                </span>
                <input type="hidden" name="search" value="friends">
                <input type="hidden" name="option" value="com_community" />
                <input type="hidden" name="view" value="friends" />
                <input type="hidden" name="Itemid" value="<?php echo CRoute::_getDefaultItemid();?>">
            </form>
            <div onclick="window.location='<?php echo CRoute::_('index.php?option=com_community&view=friends&task=invite'); ?>';" class="joms-button--add">
                <?php echo JText::_('COM_COMMUNITY_INVITE_FRIENDS'); ?>
            </div>
        </div>
    </div>

    <?php echo $submenu;?>

    <?php if ( $sortings ) { ?>
        <?php echo $sortings; ?>
        <div class="joms-gap"></div>
    <?php } ?>

    <?php if(!$search) { ?>
    <div class="joms-tab__bar">
        <a href="<?php echo ($userid) ? CRoute::_('index.php?option=com_community&view=friends&userid='.$userid) : CRoute::_('index.php?option=com_community&view=friends'); ?>" class="<?php echo ($currentTask == '') ? 'active' : ''; ?>"><?php echo JText::_('COM_COMMUNITY_ALL'); ?></a>
        <a href="<?php echo ($userid) ? CRoute::_('index.php?option=com_community&view=friends&task=online&userid='.$userid) : CRoute::_('index.php?option=com_community&view=friends&task=online'); ?>" class="<?php echo ($currentTask == 'online') ? 'active' : ''; ?>"><?php echo JText::_('COM_COMMUNITY_ONLINE'); ?></a>
        <?php if($userid && $userid != $my->id){ ?>
        <a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid='.$userid.'&filter=mutual&task=mutualfriends'); ?>" class="<?php echo ($currentTask == 'mutualfriends') ? 'active' : ''; ?>"><?php echo JText::_('COM_COMMUNITY_MUTUAL'); ?></a>
        <?php } ?>
    </div>
    <?php } ?>

    <div class="joms-tab__content">

        <?php if (!empty($friends)) { ?>
            <ul class="joms-list--friend">
                <?php foreach ($friends as $user) { ?>
                    <li id="friend-<?php echo $user->id; ?>" class="joms-list__item">
                        <?php  if (in_array($user->id, $featuredList)) { ?>
                        <div class="joms-ribbon__wrapper">
                            <span class="joms-ribbon"><?php echo JText::_('COM_COMMUNITY_FEATURED'); ?></span>
                        </div>
                        <?php } ?>
                        <div class="joms-list__avatar <?php echo CUserHelper::onlineIndicator($user); ?>">
                            <a href="<?php echo $user->profileLink; ?>" class="joms-avatar">
                                <img data-author="<?php echo $user->id; ?>" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
                            </a>
                        </div>
                        <div class="joms-list__body">
                            <?php echo CFriendsHelper::getUserCog($user->id,null,null,true); ?>
                            <?php echo CFriendsHelper::getUserFriendDropdown($user->id); ?>
                            <a href="<?php echo $user->profileLink; ?>"><h4
                                    class="joms-text--username"><?php echo $user->getDisplayName(false, true); ?></h4></a>

                            <!-- friends count -->
                            <div class="joms-list__details">
                                <?php if($config->get('memberlist_show_profile_info')) { ?>
                                <p>
                                    <span class="joms-text--title">
                                        <?php  echo nl2br(JHTML::_('string.truncate', str_replace("&quot;",'"',CUserHelper::showProfileInfo($user->id)), 140));?>
                                    </span>
                                </p>
                                <?php } ?>

                                <?php if($config->get('memberlist_show_friends_count')) { ?>
                                <span class="joms-text--title"><?php echo JText::sprintf((CFriendsHelper::getTotalMutualFriends($user->id) == 1) ? 'COM_COMMUNITY_TOTAL_MUTUAL_FRIEND' : 'COM_COMMUNITY_TOTAL_MUTUAL_FRIENDS',
                                        CFriendsHelper::getTotalMutualFriends($user->id)); ?></span>
                                <?php } ?>

                                <!-- distances -->
                                <?php
                                $units = ($config->get('advanced_search_units') == 'metric') ? JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_METRIC') : JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_IMPERIAL') ;
                                $distance = CMapsHelper::getRadiusDistance($my->latitude, $my->longitude, $user->latitude,
                                            $user->longitude, $units, $user->id);
                                if($config->get('memberlist_show_distance') && ($distance !== false) && ($my->id !== $user->id) && ($my->id !== 0)) {
                                ?>
                                <span class="joms-list__distance">
                                    <svg class="joms-icon" viewBox="0 0 16 16">
                                        <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-location"></use>
                                    </svg>
                                    <?php
                                        if ($distance === 0) {
                                            //0 indicates very near, false otherwise means some people doesnt have a proper longitude or latitude
                                            echo JText::_(($config->get('advanced_search_units') == 'metric') ? 'COM_COMMUNITY_LESS_THAN_A_KM' : 'COM_COMMUNITY_LESS_THAN_A_MILE') ;
                                        } elseif ($distance) {
                                            echo JText::sprintf('COM_COMMUNITY_MILES_AWAY', $distance, $units);
                                        }
                                    ?>
                                </span>
                                <?php } ?>

                                <?php if ($config->get('memberlist_show_last_visit') == 1) { ?>
                                <p>
                                    <span class="joms-text--title">
                                        <?php
                                            $lastLogin = JText::_('COM_COMMUNITY_PROFILE_NEVER_LOGGED_IN');
                                            if ($user->lastvisitDate != '0000-00-00 00:00:00') {
                                                $userLastLogin = new JDate($user->lastvisitDate);
                                                $lastLogin = CActivityStream::_createdLapse($userLastLogin);
                                            }
                                        ?>
                                        <?php echo JText::_('COM_COMMUNITY_LAST_LOGIN') . $lastLogin; ?>
                                    </span>
                                </p>
                                <?php } ?>
                            </div>

                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <?php if($currentTask != 'mutualfriends') { ?>
                <div class="joms-alert"><?php echo JText::_('COM_COMMUNITY_NO_FRIENDS_YET'); ?></div>
            <?php }else{ ?>
                <div class="joms-alert"><?php echo JText::_('COM_COMMUNITY_NO_MUTUAL_FRIENDS'); ?></div>
            <?php } ?>
        <?php } ?>
    </div>
    <?php if (isset($pagination) &&  $pagination->getPagesLinks() && ($pagination->pagesTotal > 1 || $pagination->total > 1) ) { ?>
    <div class="joms-pagination">
        <?php echo $pagination->getPagesLinks(); ?>
    </div>
    <?php } ?>
</div>
