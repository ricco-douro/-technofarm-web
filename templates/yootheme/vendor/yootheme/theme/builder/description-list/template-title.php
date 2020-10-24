<?php

$attrs_title = [];

$attrs_title['class'][] = 'el-title';

// Style
switch ($element['title_style']) {
    case '':
    case 'strong':
        $attrs_title['class'][] = 'uk-display-block';
        break;
    case 'muted':
        $attrs_title['class'][] = 'uk-display-block uk-text-muted';
        break;
    default:
        $attrs_title['class'][] = "uk-{$element['title_style']} uk-margin-remove";
}

// Color
$attrs_title['class'][] = $element['title_color'] && $element['title_color'] != 'background' ? "uk-text-{$element['title_color']}" : '';
$item['title'] = $element['title_color'] == 'background' ? "<span class=\"uk-text-background\">{$item['title']}</span>" : $item['title'];

// Leader
if ($element['leader'] && $element['layout'] == 'grid-2-m' && $element['width'] == 'expand') {

    $attrs_title['uk-leader'] = $element['breakpoint'] ? "media: @{$element['breakpoint']}" : true;

}

// Colon
$item['title'] .= $item['title'] && $element['title_colon'] ? ':' : '';

?>

<?php if ($item['title']) : ?>

    <?php if ($element['title_style'] == 'strong') : ?>
        <strong<?= $this->attrs($attrs_title) ?>><?= $item['title'] ?></strong>
    <?php elseif (in_array($element['title_style'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) : ?>
        <h3<?= $this->attrs($attrs_title) ?>><?= $item['title'] ?></h3>
    <?php else : ?>
        <span<?= $this->attrs($attrs_title) ?>><?= $item['title'] ?></span>
    <?php endif ?>

<?php endif ?>
