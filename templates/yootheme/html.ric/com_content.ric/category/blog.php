<?php

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');

// App
$app = JFactory::getApplication();
$app->input->set('layout', 'blog');

// Theme
$theme = JHtml::_('theme');

// Parameter shortcuts
$params  = $this->params;
$lead    = $this->lead_items ?: [];
$intro   = $this->intro_items ?: [];
$count   = max(1, $params->get('num_columns'));
$columns = [];

// Article columns
foreach ($intro as $i => $item) {
    $columns[$i % $count][] = $item;
}

// Article template
$article = JHtml::_('render', 'article:blog', function ($item) {
    return [
        'article' => $item,
        'content' => $item->introtext,
        'image' => 'intro',
    ];
});

?>

<?php if ($params->get('show_page_heading')
        || $params->get('page_subheading')
        || $params->get('show_category_title', 1)
        || ($params->def('show_description_image', 1) && $this->category->getParams()->get('image'))
        || ($params->get('show_description', 1) && $this->category->description)
        || ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags))
    ) : ?>

<div class="uk-panel">

    <?php if ($params->get('show_page_heading')) : ?>
    <h1><?= $this->escape($params->get('page_heading')) ?></h1>
    <?php endif ?>

    <?php if ($params->get('page_subheading')) : ?>
    <h2><?= $this->escape($params->get('page_subheading')) ?></h2>
    <?php endif ?>

    <?php if ($params->get('show_category_title')) : ?>
    <h3><?= $this->category->title ?></h3>
    <?php endif ?>

    <?php if ($params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
    <img src="<?= $this->category->getParams()->get('image') ?>" alt="<?= htmlspecialchars($this->category->getParams()->get('image_alt'))?>">
    <?php endif ?>

    <?php if ($params->get('show_description') && $this->category->description) : ?>
    <div class="uk-margin"><?= JHtml::_('content.prepare', $this->category->description, '', 'com_content.category') ?></div>
    <?php endif ?>

    <?php if ($params->get('show_cat_tags') && !empty($this->category->tags->itemTags)) : ?>
        <?= JLayoutHelper::render('joomla.content.tags', $this->category->tags->itemTags) ?>
    <?php endif ?>

</div>
<?php endif ?>

<?php if (empty($this->lead_items) && empty($this->intro_items) && empty($this->link_items)) : ?>
    <?php if ($params->get('show_no_articles', 1)) : ?>
    <p><?= JText::_('COM_CONTENT_NO_ARTICLES') ?></p>
    <?php endif ?>
<?php endif ?>

<?php if ($lead) : ?>
<div class="uk-child-width-1-1" uk-grid>
    <div>
        <?php foreach ($lead as $item) : ?>
        <?= $article($item) ?>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<?php

if ($columns) :

    $attrs = [];
    $attrs['class'][] = 'uk-child-width-1-' . count($columns) . '@m';
    $attrs['class'][] = $theme->get('blog.column_gutter') ? 'uk-grid-large' : '';
    $attrs['class'][] = $theme->get('blog.column_divider') ? 'uk-grid-divider' : '';
    $attrs['uk-grid'] = true;

?>
<div <?= $theme['view']->attrs($attrs) ?>>
    <?php foreach ($columns as $column) : ?>
    <div><?php foreach ($column as $item) echo $article($item) ?></div>
    <?php endforeach ?>
</div>
<?php endif ?>

<?php if (!empty($this->link_items)) : ?>
<div class="uk-margin-large<?= $theme->get('post.header_align') ? ' uk-text-center' : '' ?>">

    <h3><?= JText::_('COM_CONTENT_MORE_ARTICLES') ?></h3>

    <ul class="uk-list">
        <?php foreach ($this->link_items as $item) : ?>
        <li><a href="<?= JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)) ?>"><?= $item->title ?></a></li>
        <?php endforeach ?>
    </ul>

</div>
<?php endif ?>

<?php if (($params->def('show_pagination', 1) == 1  || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>

    <?php if ($theme->get('blog.navigation') == 'pagination') : ?>
        <?= $this->pagination->getPagesLinks() ?>
    <?php endif ?>

    <?php if ($theme->get('blog.navigation') == 'previous/next') : ?>
    <ul class="uk-pagination uk-margin-large">

        <?php if ($prevlink = $this->pagination->getData()->previous->link) : ?>
        <li><a href="<?= $prevlink ?>"><span uk-pagination-previous></span> <?= JText::_('JPREV') ?></a></li>
        <?php endif ?>

        <?php if ($nextlink = $this->pagination->getData()->next->link) : ?>
        <li class="uk-margin-auto-left"><a href="<?= $nextlink ?>"><?= JText::_('JNEXT') ?> <span uk-pagination-next></span></a></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

<?php endif ?>
