<?php

defined('_JEXEC') or die;

?>

<?php if (count($list) > 0) : ?>
<ul class="uk-grid" uk-grid>
    <?php for ($i = 0, $count = count($list); $i < $count; $i ++) : ?>
    <?php $item = $list[$i] ?>
    <li class="uk-width-1-<?= $count ?>@m">
        <?php include JModuleHelper::getLayoutPath('mod_articles_news', '_item') ?>
    </li>
    <?php endfor ?>
</ul>
<?php endif ?>
