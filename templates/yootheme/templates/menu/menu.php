<?php

// Menu ID
if ($id = $params->get('tag_id')) {
    $attrs['id'] = $id;
}

// determine layout
if (strpos($position, 'navbar') === 0) {

    $layout = $theme->get('header.layout');

    if (strpos($layout, 'offcanvas') === 0 || strpos($layout, 'modal') === 0) {

        $type = 'nav';
        $attrs['class'][] = "uk-nav uk-nav-{$theme->get('navbar.toggle_menu_style')}";
        $attrs['class'][] = $theme->get('navbar.toggle_menu_center') ? 'uk-nav-center' : '';

    } else {

        $type = 'navbar';
        $attrs['class'][] = 'uk-navbar-nav';

    }

    if ($layout == 'stacked-center-split' && $params->get('split')) {

        $length = ceil(count($items) / 2);

        if ($position == 'navbar-split') {
            $items = array_slice($items, 0, $length);
        } else {
            $items = array_slice($items, $length);
        }
    }

} else if ($params->get('menu_style') == 'subnav' || in_array($position, ['toolbar-left', 'toolbar-right'])) {

    $type = 'subnav';
    $attrs['class'][] = 'uk-subnav uk-subnav-line';

} else {

    $type = 'nav';
    $attrs['class'][] = 'uk-nav';

    if ($position == 'mobile') {

        $attrs['class'][] = "uk-nav-{$theme->get('mobile.menu_style')}";
        $attrs['class'][] = $theme->get('mobile.menu_center') ? 'uk-nav-center' : '';


    } else if ($position != 'mobile' && !array_filter($items, function($item) { return $item->type !== 'header' && isset($item->children, $item->url); })) {

        $params->set('accordion', true);
        $attrs['class'][] = 'uk-nav-default uk-nav-parent-icon uk-nav-accordion';
        $attrs['uk-nav'] = true;

    } else {

        $attrs['class'][] = 'uk-nav-default';

    }

}

?>

<ul<?= $this->attrs($attrs) ?>>
<?= $this->render("menu/{$type}", ['items' => $items]) ?>
</ul>
