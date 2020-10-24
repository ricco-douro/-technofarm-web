<?php
/*
 * The template for displaying categorized articles.
 */

$attrs_container = [];

// Image
if ($image) {
    $attrs_image['class'][] = $image->align == 'none' ? 'uk-margin-large-bottom' : 'uk-margin-medium-bottom';
}

// Header
$attrs_header['class'][] = 'uk-margin-medium-bottom';
$attrs_header['class'][] = $params['content_width'] ? 'uk-container uk-container-small' : '';
$attrs_header['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Container
if ($params['content_width']) {
    $attrs_container['class'][] = 'uk-container uk-container-small';
}

// Content
$attrs_content['class'][] = $params['content_align'] ? 'uk-text-center' : '';
$attrs_content['class'][] = $params['content_dropcap'] ? 'uk-dropcap' : '';

// Tags
$attrs_tags['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Button
$attrs_button['class'][] = "uk-button uk-button-{$params['button_style']}";
$attrs_button_container['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_button_container['class'][] = 'uk-margin-medium';

/*
 * Image template
 */
$imagetpl = function ($attr) use ($image) {
?>

<div<?= $this->attrs($attr) ?> property="image" typeof="ImageObject">
    <?php if ($image->link) : ?>
    <a href="<?= $image->link ?>"><img<?= $this->attrs($image->attrs) ?> property="url"></a>
    <?php else : ?>
    <img<?= $this->attrs($image->attrs) ?> property="url">
    <?php endif ?>
</div>

<?php
};

/*
 * Meta template
 */
$metatpl = function () use ($author, $published, $category, $params) {

    if ($published || $author || $category) {

        switch ($params['meta_style']) {

            case 'list':

                $parts = array_filter([
                    $published ? $published : '',
                    $author ? "<span>{$author}</span>" : '',
                    $category ? $category : '',
                ]);

                $attrs_header['class'][] = $params['header_align'] ? 'uk-text-center' : '';

                ?>
                <ul class="uk-subnav uk-subnav-divider<?= $params['header_align'] ? ' uk-flex-center' : '' ?>">
                    <?php foreach ($parts as $part) : ?>
                    <li><?= $part ?></li>
                    <?php endforeach ?>
                </ul>
                <?php
                break;

            default: // sentence

                ?>
                <p class="uk-article-meta">
                <?php

                    if ($author && $published) {
                        JText::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
                    } elseif ($author) {
                        JText::printf('TPL_YOOTHEME_META_AUTHOR', $author);
                    } elseif ($published) {
                        JText::printf('TPL_YOOTHEME_META_DATE', $published);
                    }

                ?>
                <?= $category ? JText::sprintf('TPL_YOOTHEME_META_CATEGORY', $category) : '' ?>
                </p>
                <?php
        }

    }

};

?>

<article id="article-<?= $article->id ?>" class="uk-article"<?= $this->attrs(['data-permalink' => $permalink]) ?> typeof="Article">

    <meta property="name" content="<?= $this->e($title) ?>">
    <meta property="author" typeof="Person" content="<?= $this->e($article->author) ?>">
    <meta property="dateModified" content="<?= $this->date($article->modified, 'c') ?>">
    <meta property="datePublished" content="<?= $this->date($article->publish_up, 'c') ?>">
    <meta class="uk-margin-remove-adjacent" property="articleSection" content="<?= $this->e($article->category_title) ?>">

    <?php if ($image && $image->align == 'none') {
        $imagetpl($attrs_image);
    } ?>

    <?php if ($title) : ?>
    <div<?= $this->attrs($attrs_header) ?>>

        <?php if (!$params['info_block_position']) : ?>
            <?= $metatpl() ?>
        <?php endif ?>

        <h1 class="uk-article-title uk-margin-remove-top"><?= $title ?></h1>

        <?php if ($params['info_block_position']) : ?>
            <?= $metatpl() ?>
        <?php endif ?>

    </div>
    <?php endif ?>

    <?php if ($event) echo $event->afterDisplayTitle ?>

    <?php if ($image && $image->align != 'none') {
        $imagetpl($attrs_image);
    } ?>

    <?php if ($event) echo $event->beforeDisplayContent ?>

    <?php if ($attrs_container) : ?>
    <div<?= $this->attrs($attrs_container) ?>>
    <?php endif ?>

    <div <?= $this->attrs($attrs_content) ?> property="text"><?= $content ?></div>

    <?php if ($tags) : ?>
    <p<?= $this->attrs($attrs_tags) ?>><?= JText::sprintf('TPL_YOOTHEME_TAGS', $tags) ?></p>
    <?php endif ?>

    <?php if ($readmore) : ?>
    <p<?= $this->attrs($attrs_button_container) ?>>
        <a <?= $this->attrs($attrs_button) ?> href="<?= $readmore->link ?>"><?= $readmore->text ?></a>
    </p>
    <?php endif ?>

    <?php if ($created || $modified || $hits) : ?>
    <ul class="uk-list">

        <?php if ($created) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_CREATED', $created) ?></li>
        <?php endif ?>

        <?php if ($modified) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_MODIFIED', $modified) ?></li>
        <?php endif ?>

        <?php if ($hits) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_HITS', $hits) ?></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

    <?php if ($icons) : ?>
    <ul class="uk-subnav">
        <?php foreach ($icons as $icon) : ?>
        <li><?= $icon ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>

    <?php if ($pagination) : ?>
    <ul class="uk-pagination uk-margin-medium">

        <?php if ($pagination->prev) : ?>
        <li><a href="<?= $pagination->prev ?>"><span uk-pagination-previous></span> <?= JText::_('JPREVIOUS') ?></a></li>
        <?php endif ?>

        <?php if ($pagination->next) : ?>
        <li class="uk-margin-auto-left"><a href="<?= $pagination->next ?>"><?= JText::_('JNEXT') ?> <span uk-pagination-next></span></a></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

    <?php if ($event) echo $event->afterDisplayContent ?>

    <?php if ($attrs_container) : ?>
    </div>
    <?php endif ?>

</article>
