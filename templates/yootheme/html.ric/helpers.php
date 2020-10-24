<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

// Load jQuery/Bootstrap
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

// Register helpers
JHtml::register('attrs', [$this['view'], 'attrs']);
JHtml::register('render', [$this['view'], 'render']);
JHtml::register('section', [$this['view'], 'section']);
JHtml::register('builder', [$this['builder'], 'render']);

// Add article loader
$this['view']->addLoader(function ($name, $parameters, $next) use ($theme) {

    $defaults = array_fill_keys(['title', 'author', 'content', 'hits', 'created', 'modified', 'published', 'category', 'image', 'tags', 'icons', 'readmore', 'pagination', 'link', 'permalink', 'event', 'single'], null);

    // Vars
    extract(array_replace($defaults, $parameters), EXTR_SKIP);

    // Params
    if (!isset($params)) {
        $params = $article->params;
    } elseif (is_array($params)) {
        $params = new Registry($params);
    }

    // Link
    if (!isset($link)) {
        $link = ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
    }

    // Permalink
    if (!isset($permalink)) {
        $permalink = JRoute::_($link, true, -1);
    }

    if ($params['access-view'] === false) {
        $menu = JFactory::getApplication()->getMenu()->getActive();
        $link = new JUri(JRoute::_("index.php?option=com_users&view=login&Itemid={$menu->id}", false));
        $link->setVar('return', base64_encode(JRoute::_($link, false)));
    }

    // Title
    if ($params['show_title']) {

        $title = $article->title;

        if ($params['link_titles']) {
            $title = JHtml::_('link', $link, $title, ['class' => 'uk-link-reset']);
        }
    }

    // Author
    if ($params['show_author']) {

        $author = $article->created_by_alias ?: $article->author;

        if ($params['link_author'] && $article->contact_link) {
            $author = JHtml::_('link', $article->contact_link, $author);
        }
    }

    if (!empty($article->created_by_alias)) {
        $article->author = $article->created_by_alias;
    }

    // Hits
    if ($params['show_hits']) {
        $hits = $article->hits;
    }

    // Create date
    if ($params['show_create_date']) {
        $created = JHtml::_('date', $article->created, JText::_('DATE_FORMAT_LC3'));
        $created = "<time datetime=\"" . JHtml::_('date', $article->created, 'c') . "\">{$created}</time>";
    }

    // Modify date
    if ($params['show_modify_date']) {
        $modified = JHtml::_('date', $article->modified, JText::_('DATE_FORMAT_LC3'));
        $modified = "<time datetime=\"" . JHtml::_('date', $article->modified, 'c') . "\">{$modified}</time>";
    }

    // Publish date
    if ($params['show_publish_date']) {
        $published = JHtml::_('date', $article->publish_up, JText::_('DATE_FORMAT_LC3'));
        $published = "<time datetime=\"" . JHtml::_('date', $article->publish_up, 'c') . "\">{$published}</time>";
    }

    // Category
    if ($params['show_category']) {

        $category = $article->category_title;

        if ($params['link_category'] && $article->catslug) {
           $category = JHtml::_('link', JRoute::_(ContentHelperRoute::getCategoryRoute($article->catslug)), $category);
        }
    }

    // Image
    if (is_string($image)) {

        $images = new Registry($article->images);
        $imageType = $image;

        if ($images->get("image_{$imageType}")) {

            $image = new stdClass();
            $image->link = $params['link_titles'] ? $link : null;
            $image->align = $images->get("float_{$imageType}") ?: $params["float_{$imageType}"];
            $image->caption = $images->get("image_{$imageType}_caption");
            $image->attrs = [
                'src' => $images->get("image_{$imageType}"),
                'alt' => $images->get("image_{$imageType}_alt", basename($images->get("image_{$imageType}"))),
                'title' => $images->get("image_{$imageType}_caption"),
            ];

        } else {

            $image = null;
        }
    }

    // Tags
    if ($params->get('show_tags', 1) && !empty($article->tags->itemTags)) {
        $tags = JLayoutHelper::render('joomla.content.tags', $article->tags->itemTags);
    }

    // Icons
    if (!isset($icons)) {
        $icons['print'] = $params['show_print_icon'] ? JHtml::_('icon.print_popup', $article, $params) : '';
        $icons['email'] = $params['show_email_icon'] ? JHtml::_('icon.email', $article, $params) : '';
        $icons['edit']  = $params['access-edit'] ? JHtml::_('icon.edit', $article, $params) : '';
    }

    $icons = array_filter($icons);

    // Readmore
    if ($params['show_readmore'] && !empty($article->readmore)) {

        $readmore = new stdClass();
        $readmore->link = $link;

        if ($params['access-view']) {

            $attribs = new Registry($article->attribs);

            if (!$readmore->text = $attribs->get('alternative_readmore')) {
                $readmore->text = JText::_($params['show_readmore_title'] ? 'COM_CONTENT_READ_MORE' : 'TPL_YOOTHEME_READ_MORE');
            }

        } else {

            $readmore->text = JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
        }

        if ($params['show_readmore_title']) {
            $readmore->text .= JHtml::_('string.truncate', $article->title, $params['readmore_limit']);
        }
    }

    // Pagination
    if (isset($article->pagination)) {
        $pagination = new stdClass();
        $pagination->prev = $article->prev;
        $pagination->next = $article->next;
    }

    // Event
    if (isset($article->event)) {

        $event = $article->event;

        if ($params['show_intro']) {
            $event->afterDisplayTitle = '';
        }
    }

    // Blog
    if (in_array($name, ['article:blog', 'article:featured'])) {

        $data = $theme->get('post', []);

        if (!$single) {
            $data->merge($theme->get('blog', []));
        }

        $params->loadArray($data->all());
    }

    return $next($name, array_diff_key(get_defined_vars(), array_flip(['data', 'next', 'name', 'parameters', 'defaults'])));

}, 'article*');
