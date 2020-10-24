<?php

defined('JPATH_BASE') or die;

extract($displayData);

// Empty?
if (empty($msgList)) {
    return;
}

$alert = [
    'message' => 'uk-alert-success',
    'warning' => 'uk-alert-warning',
    'error' => 'uk-alert-danger',
    'notice' => ''
];

?>

<?php foreach ($msgList as $type => $msgs) : ?>
<div class="uk-alert <?= $alert[$type] ?>" uk-alert>

    <a href="#" class="uk-alert-close uk-close" uk-close></a>

    <?php if (!empty($msgs)) : ?>

        <h3><?= JText::_($type) ?></h3>

        <?php foreach ($msgs as $msg) : ?>
        <p><?= $msg ?></p>
        <?php endforeach ?>

    <?php endif ?>

</div>
<?php endforeach ?>
