<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Theme\Builder;

class ContentListener extends EventSubscriber
{
    const PATTERN = '/^<!-- (\{.*\}) -->/';

    protected $user;
    protected $edit;
    protected $isRoot;

    public function onInit($theme)
    {
        $this->user = \JFactory::getUser();
        $this->edit = $this->user->authorise('core.edit', 'com_content');

        $this['routes']->post('/page', [$this, 'savePage']);
    }

    public function onAdmin($theme)
    {
        $this['modules']->get('yootheme/builder')['@config']->set('section.edit', $this->edit);
    }

    public function onSite($theme)
    {
        $input = \JFactory::getApplication()->input;

        if ($input->getCmd('option') == 'com_content' && $input->getCmd('view') == 'article' && $this['@customizer']->isActive()) {
            $this->isRoot = $this->user->get('isRoot');
            $this->user->set('isRoot', true);
        }
    }

    public function onDispatch($document, $input)
    {
        if ($this['admin'] || $input->getCmd('option') != 'com_content' || $input->getCmd('view') != 'article' || !$document->getBuffer('component')) {
            return;
        }

        if (!$article = \JControllerLegacy::getInstance('Content')->getView('article', 'html')->get('Item')) {
            return;
        }

        $edit = '';
        $content = preg_match(self::PATTERN, $article->fulltext, $matches) ? json_decode($matches[1], true) : null;

        if ($this['@customizer']->isActive()) {

            $this->user->set('isRoot', $this->isRoot);

            if ($page = $this['theme']->params->get('page')) {
                $content = $page['content'];
            }

            if ($content) {
                $content = Builder::encode($content, false);
            }

            $data = [
                'id' => $article->id,
                'catid' => $article->catid,
                'title' => $article->title,
                'content' => $content,
                'modified' => !empty($page),
            ];

            $this['@customizer']->addData('page', $data);

        } elseif ($article->params->get('access-edit')) {

            $url = \JRoute::_(
                \ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)
                .'&task=article.edit&a_id='.$article->id
                .'&return='.base64_encode(\JUri::getInstance())
            );

            $edit = "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-button uk-button-primary\" href=\"{$url}\">Edit</a>";
        }

        $this['theme']->set('builder', $content !== null ? compact('content', 'edit') : null);
    }

    public function savePage($page = [])
    {
        jimport('legacy.model.legacy');

        $data = [
            'id' => $page['id'],
            'catid' => $page['catid'],
            'title' => $page['title'],
            'introtext' => Builder::content($page['content']),
            'fulltext' => '<!-- '.Builder::encode($page['content']).' -->',
        ];

        if (!$this->edit) {
            $this['app']->abort(403, 'Insufficient User Rights.');
        }

        if ($tags = (new \JHelperTags)->getTagIds($page['id'], 'com_content.article')) {
            $data['tags'] = explode(',', $tags);
        }

        \JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_content/models', 'ContentModel');
        \JModelLegacy::getInstance('Article', 'ContentModel', ['ignore_request' => true])->save($data);

        return 'success';
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.admin' => 'onAdmin',
            'theme.site' => 'onSite',
            'dispatch' => ['onDispatch', 10]
        ];
    }
}
