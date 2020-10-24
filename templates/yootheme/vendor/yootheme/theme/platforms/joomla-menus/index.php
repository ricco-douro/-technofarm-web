<?php

$config = [

    'name' => 'yootheme/joomla-menus',

    'main' => function () {

        $this['menus'] = function () {

            return array_map(function ($menu) {
                return [
                    'id' => $menu->value,
                    'name' => $menu->text
                ];
            }, JHtmlMenu::menus());

        };

        $this['items'] = function () {

            return array_values(array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'level' => $item->level > 1 ? 1 : 0,
                    'menu' => $item->menutype,
                    'parent' => $item->parent_id
                ];
            }, JMenu::getInstance('site')->getMenu()));

        };

    },

    'routes' => function ($route) {

        $route->get('/items', function ($response) {
            return $response->withJson($this['items']);
        });

    },

    'events' => [

        'theme.admin' => function ($theme) {

            // add assets
            $this['scripts']->add('customizer-menus', "{$this->path}/app/menus.min.js", 'customizer');

            // add data
            $theme['@customizer']->addData('menu', [
                'menus' => $this['menus'],
                'items' => $this['items'],
                'positions' => $theme->options['menus']
            ]);
        },

        'modules.load' => function (&$modules) {

            if ($this['admin']) {
                return;
            }

            foreach ($this['theme']->get('menu.positions') as $position => $menu) {

                if (!$menu) {
                    continue;
                }

                $module = [
                    'id' => 0,
                    'name' => 'menu',
                    'module' => 'mod_menu',
                    'title' => '',
                    'showtitle' => 0,
                    'position' => $position,
                    'params' => json_encode([
                        'menutype' => $menu,
                        'showAllChildren' => true,
                        'split' => $position == 'navbar'
                    ])
                ];

                array_unshift($modules, (object) $module);

                if ($position == 'navbar') {
                    $module['position'] = 'navbar-split';
                    array_unshift($modules, (object) $module);
                }
            }

        }

    ],

    'config' => [

        'section' => [
            'title' => 'Menus',
            'priority' => 30
        ],

        'fields' => []

    ]

];

return defined('_JEXEC') ? $config : false;
