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
    'debug' => true,
    'appId' => env('ESIGN_APPID', 'your-app-id'),                            // APP ID
    'appKey' => env('ESIGN_APPKEY', 'your-app-key'),                          // APP KEY
    'server' => env('ESIGN_SERVER', 'https://smlopenapi.esign.cn'),           // esign api v2 url.
    'notify_url' => env('ESIGN_NOTIFY_URL', 'XXXXXX/api/esign/callback'),         // callback url
    // Orgs
    'orgs' => [
        'xxx' => [
            'org_id' => 'xxxxxxxxxxxxxxxxx',
            'name' => 'xxxxxxxxxx有限公司',
            'address' => 'xxxxx',
        ],
    ],
    'log' => [
        'file' => '/tmp/esign.log',
        'level' => 'debug',
        'handler' => null,
        'permission' => null
    ],
    'http' => [
        'max_retries' => 3,
        'retry_delay' => 300
    ]
];
