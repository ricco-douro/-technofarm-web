<?php

$attrs_content = [];

$attrs_content['class'][] = 'el-content';
$attrs_content['class'][] = $element['content_style'] ? "uk-text-{$element['content_style']}" : '';

// Link
$attrs['class'][] = $element['link_style'] ? "uk-link-{$element['link_style']}" : '';
$attrs['target'] = $item['link_target'] ? '_blank' : '';
$attrs['uk-scroll'] = (strpos($item['link'], '#') === 0) ? true : false;

?>

<?php if ($item['content']) : ?>
<div<?= $this->attrs($attrs_content) ?>>

    <?php if ($item['link']) : ?>
        <?= $this->link($item, $item['link'], $attrs) ?>
    <?php else : ?>
        <?= $item['content'] ?>
    <?php endif ?>

</div>
<?php endif ?>