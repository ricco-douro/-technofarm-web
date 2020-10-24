<?php

use YOOtheme\ImageProvider;
use YOOtheme\Translation\Translator;

return [

    'name' => 'yootheme/common',

    'main' => function ($app) {

        $app['user'] = function ($app) {
            return $app['users']->get();
        };

        $app['image'] = function ($app) {
            return new ImageProvider($app, $app['path.cache'], $app['secret']);
        };

        $app['translator'] = function ($app) {

            $translator = new Translator($app['locator']);

            if (isset($app['locale'])) {
                $translator->setLocale($app['locale']);
            }

            return $translator;
        };

    },

    'routes' => function ($route, $app) {

        $route->get('theme/image', function ($src, $hash, $response) use ($app) {

            $src = base64_decode($src);

            if ($app['image']->getHash($src) !== $hash) {
                $app->abort(401);
            }

            if (!$image = $app['image']->create($src)) {
                $app->abort(404);
            }

            return $response->withFile($image->save()->getFile());
        });

    },

    'events' => [

        'init' => function ($app) {

            $app['kernel']->addMiddleware(function ($request, $response, $next) use ($app) {

                $access = (array) $request->getAttribute('access');

                foreach ($access as $permission) {
                    if (!$app['user']->hasPermission($permission)) {
                        $app->abort(403, 'Insufficient User Rights.');
                    }
                }

                return $next($request, $response);
            });

            $app['kernel']->addMiddleware(function ($request, $response, $next) use ($app) {

                if ($request->isMethod('POST') && !$app['csrf']->validate($request->getHeaderLine('X-XSRF-Token'))) {
                    $app->abort(401, 'Invalid CSRF token.');
                }

                return $next($request, $response);
            });

        }

    ]

];
