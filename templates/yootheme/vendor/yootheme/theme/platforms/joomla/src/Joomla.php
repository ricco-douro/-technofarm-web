<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Theme\Joomla\ChildThemeListener;
use YOOtheme\Theme\Joomla\ContentListener;
use YOOtheme\Theme\Joomla\CustomizerListener;

class Joomla extends Module implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $this['events']
            ->subscribe(new ContentListener($this))
            ->subscribe(new CustomizerListener($this))
            ->subscribe(new ChildThemeListener($this));

        $this['locator']->addPath("{$this->path}/assets", 'assets');
    }

    public function onInit($theme)
    {
        \JFactory::getLanguage()->load('tpl_yootheme', $theme->path);
        \JFactory::getDocument()->setBase(htmlspecialchars(\JUri::current()));

        $this['url']->addResolver(function ($path, $parameters, $secure, $next) use ($theme) {

            $uri = $next($path, $parameters, $secure, $next);
            $query = $uri->getQueryParams();

            if (isset($query['p']) && strpos($query['p'], 'theme/') === 0) {

                $query['option'] = 'com_ajax';
                $query['style'] = $theme->id;

                $uri = $uri->withQueryParams($query);
            }

            return $uri;
        });

        if (!$this['admin'] && !$theme->customizer) {
            $this['events']->trigger('theme.site', [$theme]);
        }
    }

    public function onSite($theme)
    {
        require "{$theme->path}/html/helpers.php";

        $theme->set('direction', \JFactory::getDocument()->direction);
        $theme->set('site_url', rtrim(\JUri::root(), '/'));
        $theme->set('uikit_dev', $theme->params->get('uikit_dev'));
        $theme->set('page_class', \JFactory::getApplication()->getParams()->get('pageclass_sfx'));

        if ($theme['@customizer']->isActive()) {
            \JHtml::_('behavior.keepalive');
            \JFactory::getConfig()->set('caching', 0);
        }

        $this['builder']->addRenderer(function ($element, $type, $next) {

            $result = $next($element, $type);

            if ($element->type == 'layout') {
                $result = \JHtmlContent::prepare($result);
            }

            return $result;
        });
    }

    public function onDispatch($document)
    {
        if (!$this['view']['sections']->exists('builder') && null !== $data = $this['theme']->get('builder')) {
            $this['view']['sections']->set('builder', function () use ($data) {
                $result = $this['builder']->render($data['content'], 'page').$data['edit'];
                $this['events']->trigger('content', [$result]);
                return $result;
            });
        }

        if ($this['view']['sections']->exists('builder')) {
            $this['theme']->set('builder', true);
            $document->setBuffer('', 'component');
        }
    }

    public function onContentData($context, $data)
    {
        if ($context == 'com_templates.style') {
            $params = ['style' => $data->id];
        } elseif ($context == 'com_content.article' && $data->id) {
            jimport('components.com_content.helpers.route', JPATH_SITE);
            $params = ['section' => 'builder', 'site' => \JUri::root().\ContentHelperRoute::getArticleRoute($data->id)];
        } else {
            return;
        }

        $this['scripts']
            ->add('$customizer', 'platforms/joomla/app/customizer.js', '$customizer-data')
            ->add('$customizer-data', sprintf('var $customizer = %s;', json_encode([
                'context' => $context,
                'apikey' => ($installer = \JPluginHelper::getPlugin('installer', 'yootheme')) ? (new \JRegistry($installer->params))->get('apikey') : false,
                'url' => $this['url']->to(($this['admin'] ? 'administrator/' :  '') . 'index.php?p=customizer&option=com_ajax', $params),
            ])), [], 'string');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', -15],
            'theme.site' => ['onSite', 10],
            'dispatch' => 'onDispatch',
            'content.data' => 'onContentData',
        ];
    }
}
