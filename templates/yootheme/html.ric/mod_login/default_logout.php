<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

?>

<?php if ($type == 'logout') : ?>
<form action="<?= JRoute::_('index.php', true, $params->get('usesecure')) ?>" method="post">

    <?php if ($params->get('greeting')) : ?>
    <div class="uk-margin">
        <?php if ($params->get('name') == 0) : {
            echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
        } else : {
            echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
        } endif ?>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <button class="uk-button uk-button-primary" value="<?= JText::_('JLOGOUT') ?>" name="Submit" type="submit"><?= JText::_('JLOGOUT') ?></button>
    </div>

    <input type="hidden" name="option" value="com_users">
    <input type="hidden" name="task" value="user.logout">
    <input type="hidden" name="return" value="<?= $return ?>">
    <?= JHtml::_('form.token') ?>

</form>
<?php endif ?>
