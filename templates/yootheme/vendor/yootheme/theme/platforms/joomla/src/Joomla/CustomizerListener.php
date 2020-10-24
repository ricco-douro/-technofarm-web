<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Theme\Customizer;

class CustomizerListener extends EventSubscriber
{
    protected $cookie;

    public function onInit($theme)
    {
        $input = \JFactory::getApplication()->input;

        $this->cookie = hash_hmac('md5', $theme->id, $this['secret']);
        $theme->customizer = $input->get('p') == 'customizer';

        $active = $theme->customizer || $input->cookie->get($this->cookie) == $theme->id;

        // override params
        if ($active) {

            $custom = $input->getBase64('customizer');
            $params = $this['session']->get($this->cookie) ?: [];

            foreach ($params as $key => $value) {
                $theme->params->set($key, $value);
            }

            if ($custom && $data = json_decode(base64_decode($custom), true)) {

                foreach ($data as $key => $value) {

                    if ($key == 'config') {
                        $this['session']->set($this->cookie, [$key => $value = json_encode($value)]);
                    }

                    $theme->params->set($key, $value);
                }
            }
        }

        $this['@customizer'] = function () use ($active) {
            return new Customizer($active);
        };

        $theme['@customizer'] = function () {
            return $this['@customizer'];
        };

    }

    public function onSite($theme)
    {
        // is active?
        if (!$this['@customizer']->isActive()) {
            return;
        }

        // add assets
        $this['styles']->add('customizer', 'platforms/joomla/assets/css/site.css');

        // add data
        $this['@customizer']->addData('id', $theme->id);
    }

    public function onAdmin($theme)
    {
        // add assets
        $this['styles']->add('customizer', 'platforms/joomla/assets/css/admin.css');
        $this['scripts']->add('customizer', 'platforms/joomla/app/customizer.min.js', ['uikit', 'vue']);

        // add data
        $this['@customizer']->mergeData([
            'id' => $theme->id,
            'cookie' => $this->cookie,
            'template' => basename($theme->path),
            'site' => $this['url']->base().'/index.php',
            'root' => \JUri::base(true),
            'token' => \JSession::getFormToken(),
            'media' => \JComponentHelper::getParams('com_media')->get('file_path'),
            'apikey' => ($installer = \JPluginHelper::getPlugin('installer', 'yootheme')) ? (new \JRegistry($installer->params))->get('apikey') : false,
        ]);
    }

    public function onView($event)
    {
        // add data
        if ($this['@customizer']->isActive() && \JFactory::getApplication()->get('themeFile') != 'offline.php' && $data = $this['@customizer']->getData()) {
            $this['scripts']->add('customizer-data', sprintf('var $customizer = %s;', json_encode($data)), 'customizer', 'string');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', 10],
            'theme.site' => ['onSite', 15],
            'theme.admin' => 'onAdmin',
            'view' => 'onView'
        ];
    }
}
