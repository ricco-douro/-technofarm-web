<?php

$config = [

    'name' => 'yootheme/joomla-theme',

    'main' => 'YOOtheme\\Theme\\Joomla',

    'routes' => function ($route) {

        $user = JFactory::getUser();
        $config = JFactory::getConfig();
        $document = JFactory::getDocument();
        $application = JFactory::getApplication();

        $route->get('/customizer', function ($return = false, $response) use ($config, $document) {

            $this['events']->trigger('theme.admin', [$this['theme']]);
            $this['@customizer']->mergeData([
                'return' => $return ?: $this['url']->to('administrator/index.php'),
                'config' => $this['theme']['@config'],
            ]);

            JHtml::_('behavior.keepalive');
            JHtml::_('bootstrap.tooltip');

            return $document
                ->setTitle("Website Builder - {$config->get('sitename')}")
                ->addFavicon(JUri::root(true) . '/administrator/templates/isis/favicon.ico')
                ->render(false, [
                    'file' => 'component.php',
                    'template' => $this['theme']->template,
                ]);
        });

        $route->post('/customizer', function ($config, $response) use ($user) {

            if (!$user->authorise('core.edit', 'com_templates')) {
                $this['app']->abort(403, 'Insufficient User Rights.');
            }

            // alter custom_data type to MEDIUMTEXT
            $query = "SHOW FIELDS FROM @extensions WHERE Field = 'custom_data'";
            $alter = "ALTER TABLE @extensions CHANGE `custom_data` `custom_data` MEDIUMTEXT NOT NULL";

            if ($this['db']->fetchObject($query)->Type == 'text') {
                $this['db']->executeQuery($alter);
            }

            // update template style params
            $params = array_replace($this['theme']->params->toArray(), ['config' => json_encode($config)]);
            $this['db']->update('@template_styles', ['params' => json_encode($params)], ['id' => $this['theme']->id]);

            return 'success';
        });

        $route->get('/finder', function ($response) {

            $base = JPATH_ADMINISTRATOR.'/components/com_media';
            JLoader::register('MediaHelper', "{$base}/helpers/media.php");
            define('COM_MEDIA_BASE', JPATH_ROOT.'/'.JComponentHelper::getParams('com_media')->get('file_path'));

            $files = [];

            foreach (JControllerLegacy::getInstance('Media', ['base_path' => $base])->getModel('list')->getList() as $type => $items) {
                foreach ($items as $item) {
                    $files[] = [
                        'name' => $item->get('name'),
                        'path' => $item->get('path_relative'),
                        'url' => strtr(ltrim(substr($item->get('path'), strlen(JPATH_ROOT)), '/'), '\\', '/'),
                        'type' => $type == 'folders' ? 'folder' : 'file',
                        'size' => $item->get('size') ? JHtml::_('number.bytes', $item->get('size')) : 0
                    ];
                }
            }

            return $response->withJson($files);
        });

        $route->post('/builder/image', function ($src, $md5, $response) use ($application) {

            $params = JComponentHelper::getParams('com_media');

            try {

                $file = JFile::makeSafe(explode('?', basename($src))[0]);
                $path = JPath::check(rtrim(implode('/', [JPATH_ROOT, $params->get('image_path'), $this['theme']->get('media_folder')]), '/\\'));

                // file already exists?
                while ($iterate = @md5_file("{$path}/{$file}")) {

                    if ($iterate === $md5) {
                        return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));
                    }

                    $file = preg_replace_callback('/-?(\d*)(\.[^.]+)?$/', function ($match) {
                        return sprintf("-%02d%s", intval($match[1]) + 1, isset($match[2]) ? $match[2] : '');
                    }, $file, 1);
                }

                // download file
                $tmp = "{$path}/".uniqid();
                $res = JHttpFactory::getHttp()->get($src);

                if ($res->code != 200) {
                    throw new Exception('Download failed.');
                } else if (!JFile::write($tmp, $res->body)) {
                    throw new Exception('Error writing file.');
                }

                // allow .svg files
                $params->set('upload_extensions', $params->get('upload_extensions').',svg');

                if (!(new JHelperMedia)->canUpload(['name' => $file, 'tmp_name' => $tmp, 'size' => filesize($tmp)])) {

                    JFile::delete($tmp);

                    $queue = $application->getMessageQueue();
                    $message = count($queue) ? $queue[0]['message'] : '';

                    throw new Exception($message);
                }

                // move file
                if (!JFile::move($tmp, "{$path}/{$file}")) {
                    throw new Exception('Error writing file.');
                }

                return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));

            } catch (\Exception $e) {
                $this['app']->abort(500, $e->getMessage());
            }

        });

    },

    'events' => [

        'init' => function () {

            $this['kernel']->addMiddleware(function ($request, $response, $next) {

                $user = JFactory::getUser();
                $allowed = in_array($request->getParam('p'), ['theme/image'], true);

                // check user permissions
                if (!$allowed && !$user->authorise('core.edit', 'com_templates') && !$user->authorise('core.edit', 'com_content')) {
                    $this['app']->abort(403, 'Insufficient User Rights.');
                }

                return $next($request, $response);
            });

            $this['events']->trigger('theme.init', [$this['theme']]);
        },

        'theme.init' => [function ($theme) {

            // set defaults and config
            $theme->merge($this->options['config']['defaults'], true);
            $theme->merge(json_decode($theme->params->get('config', '{}'), true), true);

        }, -5],

    ],

    'config' => [

        'panels' => [

            'system' => [
                'title' => 'System',
                'width' => 400,
                'fields' => [

                    'media_folder' => [
                        'label' => 'Media Folder',
                        'description' => 'This folder stores images that you download when using layouts from the YOOtheme Pro library. It\'s located inside the Joomla images folder.',
                        'type' => 'text',
                    ],

                ],

            ],

            'system-post' => [
                'title' => 'Post',
                'width' => 400,
                'fields' => [

                    'post.meta_style' => [
                        'label' => 'Meta Style',
                        'description' => 'Display the meta text in a sentence or a horizontal list.',
                        'type' => 'select',
                        'options' => [
                            'List' => 'list',
                            'Sentence' => 'sentence',
                        ],
                    ],

                    'post.header_align' => [
                        'label' => 'Alignment',
                        'description' => 'The alignment option applies to both, the blog and single posts.',
                        'type' => 'checkbox',
                        'text' => 'Center the header and footer',
                    ],

                    'post.content_width' => [
                        'label' => 'Max Width',
                        'description' => 'Set a smaller width than the image\'s for the content.',
                        'type' => 'checkbox',
                        'text' => 'Small',
                    ],

                    'post.content_dropcap' => [
                        'label' => 'Drop Cap',
                        'description' => 'Set a large initial letter that drops below the first line of the first paragraph.',
                        'type' => 'checkbox',
                        'text' => 'Show drop cap',
                    ],

                ],
            ],

            'system-blog' => [
                'title' => 'Blog',
                'width' => 400,
                'fields' => [

                    'blog.column_gutter' => [
                        'type' => 'checkbox',
                        'text' => 'Large gutter',
                        'show' => 'blog.column != "1"',
                    ],

                    'blog.column_divider' => [
                        'description' => 'Set a larger gutter and display dividers between columns.',
                        'type' => 'checkbox',
                        'text' => 'Display dividers between columns',
                        'show' => 'blog.column != "1"',
                    ],

                    'blog.content_align' => [
                        'label' => 'Alignment',
                        'description' => 'This option applies to the blog overview and not to single posts. To center the post\'s header and footer, go to the post settings.',
                        'type' => 'checkbox',
                        'text' => 'Center the content',
                    ],

                    'blog.button_style' => [
                        'label' => 'Button',
                        'description' => 'Select a style for the continue reading button.',
                        'type' => 'select',
                        'options' => [
                            'Default' => 'default',
                            'Primary' => 'primary',
                            'Secondary' => 'secondary',
                            'Danger' => 'danger',
                            'Text' => 'text',
                        ],
                    ],

                    'blog.navigation' => [
                        'label' => 'Navigation',
                        'description' => 'Use a numeric pagination or previous/next links to move between blog pages.',
                        'type' => 'select',
                        'options' => [
                            'Pagination' => 'pagination',
                            'Previous/Next' => 'previous/next',
                        ],
                    ],

                    'blog.pagination_startend' => [
                        'type' => 'checkbox',
                        'text' => 'Show Start/End links',
                        'show' => 'blog.navigation == "pagination"',
                    ],

                ],
            ]

        ],

        'defaults' => [

            'post' => [

                'meta_style' => 'sentence',
                'header_align' => 0,
                'content_width' => 0,
                'content_dropcap' => 0,
                'navigation' => 1,
            ],

            'blog' => [

                'column_gutter' => 0,
                'column_divider' => 0,
                'content_align' => 0,
                'button_style' => 'default',
                'navigation' => 'pagination',
            ],

            'media_folder' => 'yootheme',

        ],

    ],

];

return defined('_JEXEC') ? $config : false;
