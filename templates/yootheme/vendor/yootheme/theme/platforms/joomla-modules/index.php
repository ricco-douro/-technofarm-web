<?php

$config = [

    'name' => 'yootheme/joomla-modules',

    'main' => 'YOOtheme\\Theme\\Modules',

    'routes' => function ($route) {

        $route->get('/modules', function ($response) {
            return $response->withJson($this['@modules']);
        });

    },

    'config' => [

        'section' => [
            'title' => 'Modules',
            'priority' => 40
        ],

        'fields' => [],

        'defaults' => [],

    ]

];

return defined('_JEXEC') ? $config : false;
