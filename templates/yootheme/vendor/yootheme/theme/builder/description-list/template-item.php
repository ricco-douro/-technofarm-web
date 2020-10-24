<?php

$attrs_grid = [];
$attrs_cell = [];

// Display
if (!$element['show_title']) { $item['title'] = ''; }
if (!$element['show_meta']) { $item['meta'] = ''; }
if (!$element['show_content']) { $item['content'] = ''; }
if (!$element['show_link']) { $item['link'] = ''; }

// Layout
if ($element['layout'] != 'stacked') {

    $attrs_grid['uk-grid'] = true;

    $attrs_cell['class'][] = in_array($element['width'], ['small', 'medium']) ? 'uk-text-break' : '';

    if ($element['width'] == 'expand') {
        $attrs_grid['class'][] = $element['breakpoint'] ? "uk-child-width-auto@{$element['breakpoint']}" : 'uk-child-width-auto';
    } else {
        $attrs_grid['class'][] = $element['breakpoint'] ? "uk-child-width-expand@{$element['breakpoint']}" : 'uk-child-width-expand';
    }

    $attrs_cell['class'][] = $element['breakpoint'] ? "uk-width-{$element['width']}@{$element['breakpoint']}" : "uk-width-{$element['width']}";

    if ($element['layout'] == 'grid-2-m') {

        if ($element['width'] == 'expand' && $element['leader']) {
            $attrs_grid['class'][] = 'uk-grid-small uk-flex-bottom';
        } else {
            $attrs_grid['class'][] = $element['gutter'] ? "uk-grid-{$element['gutter']}" : '';
            $attrs_grid['class'][] = 'uk-flex-middle';
        }

    } else {
        $attrs_grid['class'][] = $element['gutter'] ? "uk-grid-{$element['gutter']}" : '';
    }

}

?>

<?php if ($element['layout'] == 'stacked') : ?>

    <?php if ($element['meta_align'] == 'top-title') : ?>
    <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
    <?php endif ?>

    <?= $this->render('@builder/description-list/template-title', compact('item')) ?>

    <?php if (in_array($element['meta_align'], ['bottom-title', 'top-content'])) : ?>
    <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
    <?php endif ?>

    <?= $this->render('@builder/description-list/template-content', compact('item')) ?>

    <?php if ($element['meta_align'] == 'bottom-content') : ?>
    <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
    <?php endif ?>

<?php elseif ($element['layout'] == 'grid-2') : ?>

    <div<?= $this->attrs($attrs_grid) ?>>
        <div<?= $this->attrs($attrs_cell) ?>>

            <?php if ($element['meta_align'] == 'top-title') : ?>
            <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
            <?php endif ?>

            <?= $this->render('@builder/description-list/template-title', compact('item')) ?>

            <?php if ($element['meta_align'] == 'bottom-title') : ?>
            <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
            <?php endif ?>

        </div>
        <div>

            <?php if ($element['meta_align'] == 'top-content') : ?>
            <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
            <?php endif ?>

            <?= $this->render('@builder/description-list/template-content', compact('item')) ?>

            <?php if ($element['meta_align'] == 'bottom-content') : ?>
            <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>
            <?php endif ?>

        </div>
    </div>

<?php else : ?>

    <div<?= $this->attrs($attrs_grid) ?>>
        <div<?= $this->attrs($attrs_cell) ?>>

            <?= $this->render('@builder/description-list/template-title', compact('item')) ?>

        </div>
        <div>

            <?= $this->render('@builder/description-list/template-meta', compact('item')) ?>

        </div>
    </div>

    <?= $this->render('@builder/description-list/template-content', compact('item')) ?>

<?php endif ?>

