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

/**
 * @since 3.2 we'll use CActivity for each activity object
 * @todo in sprint 3 we must move everything into CActivity while passing into template layout
 */
/* Temporary fix for sprint 2 */
if ($this->act instanceof CTableActivity) {
    /* If this's CTableActivity then we use getProperties() */
    $activity = new CActivity($this->act->getProperties());
} else {
    /* If it's standard object than we just passing it */
    $activity = new CActivity($this->act);
}

$stream = new stdClass();

$mainframe	= JFactory::getApplication();
$jinput 	= $mainframe->input;
$isSingleAct= ($jinput->get->get('actid',0) > 0) ? true : false;

$address = $activity->getLocation();
$user = $activity->getActor();
$target = $activity->getTarget();

if (!empty($act->params)) {
    if (!is_object($act->params)) {
        $act->params = new JRegistry($act->params);
    }
    $mood = $act->params->get('mood', null);
} else {
    $mood = null;
}

$poll = JTable::getInstance('Poll', 'CTable');
$poll->load($act->cid);
$poll->expired = $poll->isExpired();
$expired_class = $poll->expired ? 'joms-poll__expired' : '';
$title = $poll->title;
?>

<div class="joms-stream__header">
    <div class= "joms-avatar--stream <?php echo CUserHelper::onlineIndicator($user); ?>">
        <?php if($user->id > 0) :?>
            <a href="<?php echo CUrlHelper::userLink($user->id); ?>">
                <img data-author="<?php echo $user->id; ?>" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>">
            </a>
        <?php endif; ?>
    </div>
    <div class="joms-stream__meta">
        <?php if($user->id > 0) :?>
            <a href="<?php echo CUrlHelper::userLink($user->id); ?>" data-joms-username class="joms-stream__user active"><?php echo $user->getDisplayName(false, true); ?></a>
        <?php else :
            echo $user->getDisplayName(false, true);
        endif; ?>
        <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$activity->actor.'&actid='.$activity->id); ?>" style="display: inherit;">
            <span class="joms-stream__time">
                <small><?php echo $activity->getCreateTimeFormatted(); ?></small>
                <?php if ( strpos($activity->get('app'), 'events') === false  && strpos($activity->get('app'), 'groups') === false ) { ?>
                    <?php ( ($activity->get('app') == 'poll') && $activity->get('target') != $activity->get('actor') ) ? '' : $this->load('/privacy/show'); ?>
                <?php } ?>
            </span>
        </a>
        <?php
            if ($poll->isExpired()) {
                echo JText::_('COM_COMMUNITY_POLL_IS_ENDED') .' <svg viewBox="0 0 16 18" class="joms-icon" style="width: 14px; height: 14px; "><use xlink:href="'.CRoute::getURI().'#joms-icon-calendar"></use></svg> '.$poll->getEndDateHTML();
            } else {
                echo JText::_('COM_COMMUNITY_POLL_WILL_BE_ENDED_ON') .' <svg viewBox="0 0 16 18" class="joms-icon" style="width: 14px; height: 14px; "><use xlink:href="'.CRoute::getURI().'#joms-icon-calendar"></use></svg> '.$poll->getEndDateHTML();
            }
        ?>
    </div>

    <?php
        $my = CFactory::getUser();
        $canEdit = false;
        $canDelete = false;
        $isMine = $my->id == $poll->creator;

        if ($isMine || $my->authorise('community.edit', 'polls.' . $poll->id, $poll)) {
            $canEdit = true;
        }

        if ($isMine || $my->authorise('community.delete', 'polls.' . $poll->id, $poll)) {
            $canDelete = true;
        }

        if ($canEdit || $canDelete || CFactory::getConfig()->get('enableguestreporting')) $this->set('poll', $poll)->load('activities.stream.options.poll');
    ?>

</div>
<div class="joms-stream__body">
    <p data-type="stream-content">
        <?php echo $title; ?>
    </p>
    <div class="joms-attachment-list joms-poll__container joms-poll__container-<?php echo $poll->id ?> <?php echo $expired_class ?>" >

        <?php $this->set('poll', $poll)->load('stream/poll-container'); ?>

    </div>    
</div>

<?php $this->load('stream/footer'); ?>
