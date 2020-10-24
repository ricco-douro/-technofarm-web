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

$title = '';

if ($type == COMMUNITY_EVENT_STATUS_ATTEND) {
    $title = JText::_('COM_COMMUNITY_EVENTS_CONFIRMED_GUESTS');
} else if ($type == COMMUNITY_EVENT_STATUS_WONTATTEND) {
    $title = JText::_('COM_COMMUNITY_EVENTS_WONT_ATTEND');
} else if ($type == COMMUNITY_EVENT_STATUS_MAYBE) {
    $title = JText::_('COM_COMMUNITY_EVENTS_MAYBE_ATTEND');
} else if($type == COMMUNITY_EVENT_STATUS_BANNED) {
    $title = JText::_('COM_COMMUNITY_EVENTS_BANNED_MEMBERS');
}else{
    $title = JText::_('COM_COMMUNITY_REQUESTED_INVITATION');
}

?>
<div class="joms-page">
    <div class="joms-list__search">
        <div class="joms-list__search-title"><h3 class="joms-page__title"><?php echo $title; ?></h3></div>

        <?php if($isMine || $isCommunityAdmin || $isAdmin || $handler->manageable()) { ?>
        <div class="joms-list__utilities">
            <a href="<?php echo $handler->getFormattedLink('index.php?option=com_community&view=events&task=sendmail&eventid=' . $event->id .'&type='.$type );?>" class="joms-button--add"><?php echo JText::_('COM_COMMUNITY_EVENTS_EMAIL_SEND') ?></a>
        </div>
        <?php } ?>
    </div>

    <?php if( $guests ) { ?>
    <div id="notice" class="alert alert-notice" style="display:none;">
        <a class="close" data-dismiss="alert">×</a>
        <div id="notice-message"></div>
    </div>

    <ul class="joms-list--friend">
    <?php foreach( $guests as $guest ){ ?>
        <li id="member_<?php echo $guest->id;?>" class="joms-list__item">
            <?php  if (in_array($guest->id, $featuredList)) { ?>
            <div class="joms-ribbon__wrapper">
                <span class="joms-ribbon"><?php echo JText::_('COM_COMMUNITY_FEATURED'); ?></span>
            </div>
            <?php } ?>
            <div class="joms-list__avatar <?php echo CUserHelper::onlineIndicator($guest); ?>">
                <a class="joms-avatar" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $guest->id); ?>">
                    <img src="<?php echo $guest->getThumbAvatar(); ?>" alt="<?php echo $guest->getDisplayName(); ?>"  data-author="<?php echo $guest->id; ?>" />
                </a>
            </div>

            <div class="joms-list__body">
                <?php echo CFriendsHelper::getUserCog($guest->id,null,$eventid,true); ?>
                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $guest->id); ?>">
                    <h4 class="joms-text--username"><?php echo $guest->getDisplayName(false, true); ?></h4>
                </a>

                <!-- friends count -->
                <div class="joms-list__details">
                    <?php if($config->get('memberlist_show_profile_info')) { ?>
                    <p>
                        <span class="joms-text--title">
                            <?php  echo nl2br(JHTML::_('string.truncate', str_replace("&quot;",'"',CUserHelper::showProfileInfo($guest->id)), 140));?>
                        </span>
                    </p>
                    <?php } ?>

                    <?php if($config->get('memberlist_show_friends_count')) { ?>
                    <span class="joms-text--title">
                        <?php echo $guest->friendsCount;?> <?php  echo JText::sprintf( (CStringHelper::isPlural($guest->friendsCount)) ? 'COM_COMMUNITY_FRIENDS_COUNT_MANY' : 'COM_COMMUNITY_FRIENDS_COUNT', $guest->friendsCount);?>
                    </span>
                    <?php } ?>

                    <!-- distances -->
                    <?php
                    $units = ($config->get('advanced_search_units') == 'metric') ? JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_METRIC') : JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_IMPERIAL') ;
                    $distance = CMapsHelper::getRadiusDistance($my->latitude, $my->longitude, $guest->latitude,
                                $guest->longitude, $units, $guest->id);
                    if($config->get('memberlist_show_distance') && ($distance !== false) && ($my->id !== $guest->id) && ($my->id !== 0)) {
                    ?>
                    <span class="joms-list__distance">
                        <svg class="joms-icon" viewBox="0 0 16 16">
                            <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-location"></use>
                        </svg>
                        <?php
                            if ($distance === 0) {
                                //0 indicates very near, false otherwise means some people doesnt have a proper longitude or latitude
                                echo JText::_(($config->get('advanced_search_units') == 'metric') ? JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_METRIC') : JText::_('COM_COMMUNITY_SORT_BY_DISTANCE_IMPERIAL')) ;
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
                                if ($guest->lastvisitDate != '0000-00-00 00:00:00') {
                                    $userLastLogin = new JDate($guest->lastvisitDate);
                                    $lastLogin = CActivityStream::_createdLapse($userLastLogin);
                                }
                            ?>
                            <?php echo JText::_('COM_COMMUNITY_LAST_LOGIN') . $lastLogin; ?>
                        </span>
                    </p>
                    <?php } ?>
                </div>

                <div class="joms-list__actions">
                    <?php echo CFriendsHelper::getUserCog($guest->id,null,$eventid,true); ?>
                    <?php // echo CFriendsHelper::getUserFriendDropdown($guest->id); ?>
                </div>
            </div>

            <?php if($type == 6) { ?>
            <div class="joms-list__button--dropdown">
                <button class="joms-button--neutral joms-button--smallest" onclick="joms.api.eventRejectGuest('<?php echo $eventid; ?>', '<?php echo $guest->id ?>');"><?php echo JText::_('COM_COMMUNITY_FRIENDS_PENDING_ACTION_REJECT'); ?></button>
                <button class="joms-button--primary joms-button--smallest" onclick="jax.call('community','events,ajaxApproveInvite', '<?php echo $guest->id ?>' , '<?php echo $eventid; ?>');"><?php echo JText::_('COM_COMMUNITY_PENDING_ACTION_APPROVE'); ?></button>
            </div>
            <?php } ?>
        </li>
    <?php } ?>
    </ul>

    <?php } else { ?>
    <div class="joms-alert"><?php echo JText::_('COM_COMMUNITY_EVENTS_NO_USERS'); ?></div>
    <?php } ?>

    <?php if ($pagination->getPagesLinks() && ($pagination->pagesTotal > 1 || $pagination->total > 1) ) { ?>
        <div class="joms-pagination">
            <?php echo $pagination->getPagesLinks(); ?>
        </div>
    <?php } ?>
</div>
