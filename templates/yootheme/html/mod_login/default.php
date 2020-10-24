<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

?>

<form action="<?= JRoute::_('index.php', true, $params->get('usesecure')) ?>" method="post">

    <?php if ($params->get('pretext')) : ?>
    <div class="uk-margin">
        <?= $params->get('pretext') ?>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <input class="uk-input" type="text" name="username" size="18" placeholder="<?= JText::_('MOD_LOGIN_VALUE_USERNAME') ?>">
    </div>

    <div class="uk-margin">
        <input class="uk-input" type="password" name="password" size="18" placeholder="<?= JText::_('JGLOBAL_PASSWORD') ?>">
    </div>

    <?php if (count($twofactormethods) > 1) : ?>
    <div class="uk-margin">
        <input class="uk-input" type="text" name="secretkey" tabindex="0" size="18" placeholder="<?= JText::_('JGLOBAL_SECRETKEY') ?>" />
    </div>
    <?php endif ?>

    <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
    <div class="uk-margin">
        <label>
            <input type="checkbox" name="remember" value="yes" checked>
            <?= JText::_('MOD_LOGIN_REMEMBER_ME') ?>
        </label>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <button class="uk-button uk-button-primary" value="<?= JText::_('JLOGIN') ?>" name="Submit" type="submit"><?= JText::_('JLOGIN') ?></button>
    </div>

    <ul class="uk-list uk-margin-remove-bottom">
        <li><a href="<?= JRoute::_('index.php?option=com_users&view=reset') ?>"><?= JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD') ?></a></li>
        <li><a href="<?= JRoute::_('index.php?option=com_users&view=remind') ?>"><?= JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME') ?></a></li>
        <?php $usersConfig = JComponentHelper::getParams('com_users') ?>
        <?php if ($usersConfig->get('allowUserRegistration')) : ?>
        <li><a href="<?= JRoute::_('index.php?option=com_users&view=registration') ?>"><?= JText::_('MOD_LOGIN_REGISTER') ?></a></li>
        <?php endif ?>
    </ul>

    <?php if($params->get('posttext')) : ?>
    <div class="uk-margin">
        <?= $params->get('posttext') ?>
    </div>
    <?php endif ?>

    <input type="hidden" name="option" value="com_users">
    <input type="hidden" name="task" value="user.login">
    <input type="hidden" name="return" value="<?= $return ?>">
    <?= JHtml::_('form.token') ?>

</form>
