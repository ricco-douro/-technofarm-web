<?php

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

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
$article = JHtml::_('render', 'article:featured', function ($item) {
    return [
        'article' => $item,
        'content' => $item->introtext,
        'image' => 'intro',
    ];
});

?>

<?php if ($params->get('show_page_heading')) : ?>
<h1><?= $this->escape($params->get('page_heading')) ?></h1>
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

<?php if ($columns) : ?>
<div class="uk-child-width-1-<?= count($columns) ?>@m" uk-grid>
    <?php foreach ($columns as $column) : ?>
    <div><?php foreach ($column as $item) echo $article($item) ?></div>
    <?php endforeach ?>
</div>
<?php endif ?>

<?php if (!empty($this->link_items)) : ?>
<div class="uk-margin-large">
    <div class="uk-width-xxlarge uk-margin-auto uk-text-center">
        <h3><?= JText::_('COM_CONTENT_MORE_ARTICLES') ?></h3>
        <ul class="uk-list">
            <?php foreach ($this->link_items as $item) : ?>
            <li><a href="<?= JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug)) ?>"><?= $item->title ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
<?php endif ?>

<?php if (($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
<?= $this->pagination->getPagesLinks() ?>
<?php endif ?>
