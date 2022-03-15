<?php

declare(strict_types=1);

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
        'handler' => null,
        'file' => 'storage/logs/esign.log',
        'level' => 100,
        'permission' => null,
    ],
];
