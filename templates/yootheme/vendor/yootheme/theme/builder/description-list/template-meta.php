<?php

$attrs_meta = [];

$attrs_meta['class'][] = 'el-meta';

switch ($element['meta_style']) {
    case '':
        break;
    case 'meta':
    case 'muted':
    case 'primary':
        $attrs_meta['class'][] = "uk-text-{$element['meta_style']}";
        break;
    default:
        $attrs_meta['class'][] = "uk-{$element['meta_style']} uk-margin-remove";
}

?>

<?php if ($item['meta']) : ?>
<div<?= $this->attrs($attrs_meta) ?>><?= $item['meta'] ?></div>
<?php endif ?>