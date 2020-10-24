<?php

return [

    'yootheme/joomla-modules' => require 'modules.php',

    'config' => [

        'menu' => [
            'positions' => [
                'navbar' => 'mainmenu',
                'mobile' => 'mainmenu',
            ]
        ],

        'mobile' => [

            'toggle' => 'left'

        ]

    ],

    'replacements' => [

        'list_match' => '$match(type, "(articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar)")',

    ],

];
