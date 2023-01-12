<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'app_id' => env('ESIGN_APPID', ''),
    'secret' => env('ESIGN_APPKEY', ''),
    'server' => env('ESIGN_SERVER', 'https://smlopenapi.esign.cn'),
    'notify_url' => env('ESIGN_NOTIFY_URL', ''),
    'log' => [
        'default' => env('APP_ENV', 'local'),
        'channels' => [
            'local' => [
                'driver' => 'single',
                'level' => 'debug',
                'path' => storage_path('/logs/esign.log'),
            ],
            'dev' => [
                'driver' => 'daily',
                'level' => 'debug',
                'path' => storage_path('/logs/esign.log'),
            ],
            'prod' => [
                'driver' => 'daily',
                'level' => 'debug',
                'path' => '/Users/observer/Downloads/esign.log',
            ]
        ]
    ],
    'http' => [
        'max_retries' => 3,
        'retry_delay' => 300
    ]
];
