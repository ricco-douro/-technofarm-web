<?php

$config = [

    'name' => 'yootheme/joomla',

    'main' => 'YOOtheme\\Joomla',

    'events' => [

        'init' => function ($app) {

            if (isset($this['path.cache']) && !is_dir($this['path.cache']) && !\JFolder::create($this['path.cache'])) {
                throw new \RuntimeException(sprintf('Unable to create cache folder in "%s"', $this['path.cache']));
            }

        }

    ]

];

return defined('_JEXEC') ? $config : false;
