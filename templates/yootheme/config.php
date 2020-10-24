<?php

return array_merge([

    'name' => 'YOOtheme',

    'main' => 'YOOtheme\\Theme',

    'version' => '1.5.0',

    'require' => 'yootheme/theme',

    'include' => 'vendor/yootheme/theme/index.php',

    'menus' => [

        'navbar' => 'Navbar',
        'mobile' => 'Mobile',

    ],

    'positions' => [

        'toolbar-left' => 'Toolbar Left',
        'toolbar-right' => 'Toolbar Right',
        'navbar' => 'Navbar',
        'header' => 'Header',
        'top' => 'Top',
        'sidebar' => 'Sidebar',
        'bottom' => 'Bottom',
        'mobile' => 'Mobile',

    ],

    'styles' => [

        'imports' => [
            'less/*.less',
            'vendor/assets/uikit/src/images/backgrounds/*.svg',
            'vendor/assets/uikit-themes/*/images/*.svg',
        ],

    ],

    'config' => [

        'menu' => [
            'positions' => [
                'navbar' => '',
                'mobile' => '',
            ]
        ]

    ],

    'events' => [

        'theme.init' => [function ($theme) {

            // Deprecated
            if ($theme->get('header.layout') == 'toggle-offcanvas') {
                $theme->set('header.layout', 'offcanvas-top-a');
            }

            // Deprecated
            if ($theme->get('header.layout') == 'toggle-modal') {
                $theme->set('header.layout', 'modal-center-a');
                $theme->set('navbar.toggle_menu_style', 'primary');
                $theme->set('navbar.toggle_menu_center', true);
            }

            // Deprecated
            if ($theme->get('mobile.animation') == 'modal' && !$theme->has('mobile.menu_center')) {
                $theme->set('mobile.menu_style', 'primary');
                $theme->set('mobile.menu_center', true);
                $theme->set('mobile.menu_center_vertical', true);
            }

        }, -10],

        'theme.site' => function ($theme) {

            $this['styles']->add('theme-style', 'css/theme'.($this->get('direction') === 'rtl' ? '.rtl' : '').'.css', 'highlight', [
                'version' => $css = @filemtime("{$this->path}/css/theme.css")
            ]);

            if (filemtime(__FILE__) >= $css) {
                $this['styles']->add('theme-style-update', 'css/theme.update.css');
            }

            $icons = "{$this->path}/vendor/assets/uikit/dist/js/uikit-icons";
            $style = "{$icons}-{$this->get('style')}.min.js";
            $this['scripts']
                ->add('theme-uikit', 'vendor/assets/uikit/dist/js/uikit.min.js')
                ->add('theme-uikit-icons', file_exists($style) ? $style : "{$icons}.min.js")
                ->add('theme-script', 'js/theme.js', 'theme-uikit');

            if ($custom = $this['locator']->find('@assets/css/custom.css')) {
                $this['styles']->add('theme-custom', $custom, 'theme-style');
            }

            if ($custom = $this->get('custom_js')) {
                $this['scripts']->add('theme-custom', "try { {$custom} } catch (e) { console.error('Custom Theme JS Code: ', e); }", 'theme-script', 'string');
            }

        },

        'content' => function ($content) {

            if ($style = $this->get('highlight') and strpos($content, '</code>')) {
                $this['styles']->add('highlight', "vendor/assets/highlightjs/styles/{$style}.css", '', ['defer' => true]);
                $this['scripts']
                    ->add('highlight', 'vendor/assets/highlightjs/highlight.pack.min.js', 'theme-script', ['defer' => true])
                    ->add('highlight-init', 'jQuery(function() {hljs.initHighlightingOnLoad()});', 'highlight', ['type' => 'string', 'defer' => true]);
            }

        }

    ],

    'yootheme/layout' => require 'config/layout.php',
    'yootheme/settings' => require 'config/settings.php',
    'yootheme/styler' => require 'config/styler.php',

], require 'config/platform.php');
