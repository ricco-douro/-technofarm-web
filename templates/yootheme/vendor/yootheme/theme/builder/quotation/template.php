<?php

$id    = $element['id'];
$class = $element['class'];
$attrs = $element['attrs'];

$attrs_link = [
    'href' => $element['link'],
    'target' => $element['link_target'] ? '_blank' : '',
    'class' => $element['link_style'] ? "uk-link-{$element['link_style']}" : '',
];

?>

<blockquote<?= $this->attrs(compact('id', 'class'), $attrs) ?>>

    <?= $element ?>

    <?php if ($element['footer'] || $element['author']) : ?>
    <footer class="el-footer">

        <?= $element['footer'] ?>

        <?php if ($element['author']) : ?>

            <?php if ($element['link']) : ?>
            <cite class="el-author"><a<?= $this->attrs($attrs_link) ?>><?= $element['author'] ?></a></cite>
            <?php else : ?>
            <cite class="el-author"><?= $element['author'] ?></cite>
            <?php endif ?>

        <?php endif ?>

    </footer>
    <?php endif ?>

</blockquote>
