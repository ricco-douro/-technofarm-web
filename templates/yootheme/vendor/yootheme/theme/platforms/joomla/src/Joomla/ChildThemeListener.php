<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Theme\JoomlaViews;

class ChildThemeListener extends EventSubscriber
{
    protected $path;

    public function onInit($theme)
    {
        if (!$child = $theme->get('child_theme')) {
            return;
        }

        if (!$this->path = file_exists($path = "{$theme->path}_{$child}") ? $path : null) {
            return;
        }

        $views = (new \ReflectionClass('JControllerLegacy'))->getProperty('views');
        $views->setAccessible(true);
        $views->setValue(new JoomlaViews(basename($theme->path), basename($path)));

        $this['locator']
            ->addPath($path, 'theme')
            ->addPath($path, 'assets')
            ->addPath("{$path}/templates", 'views')
            ->addPath("{$path}/builder", 'builder');
    }

    public function onAdmin($theme)
    {
        $theme['@customizer']->addData('panels', [
            'advanced' => [
                'fields' => [
                    'child_theme' => [
                        'label' => 'Child Theme',
                        'description' => 'Select a child theme. Note that different template files will be loaded and theme settings will be updated respectively. To set up your own child theme, create new folder on the same level as theme\'s and name it yootheme_child or similar.',
                        'type' => 'select',
                        'default' => '',
                        'options' => array_merge(['None' => false], $this->getChildThemes($theme->path))
                    ],
                ],
            ],
        ]);
    }

    public function onModules(&$modules)
    {
        if ($this['admin'] || !$this->path) {
            return;
        }

        $name = basename($this->path);

        foreach ($modules as $module) {

            $params = json_decode($module->params);
            $layout = isset($params->layout) ? str_replace('_:', '', $params->layout) : 'default';

            if (file_exists("{$this->path}/html/{$module->module}/{$layout}.php")) {
                $params->layout = "{$name}:{$layout}";
                $module->params = json_encode($params);
            }
        }
    }

    public function getChildThemes($path)
    {
        $dir = dirname($path);
        $name = basename($path);
        $themes = [];

        foreach (glob("{$dir}/{$name}_*") as $child) {
            $child = str_replace($name.'_', '', basename($child));
            $themes[ucfirst($child)] = $child;
        }

        return $themes;
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', -10],
            'theme.admin' => 'onAdmin',
            'modules.load' => ['onModules', -5],
        ];
    }
}
