# chiefgroup-esign
esign API v2.

[esign 文档](https://open.esign.cn/doc/opendoc/saas_api/zi63uy)

## 安装

```
$ composer require chiefgroup/laravel-esign
```

## 使用

```php
use \QF\LaravelEsign\Application;
$config = [
    'app_id' => 'XXX',
    'secret' => 'XXX',
    'response_type' => 'collection',
    'log' => [
        'default' => 'dev',
        'channels' => [
            'dev' => [
                'driver' => 'single',
                'level' => 'debug',
                'path' => '/XXX/esign.log',
            ],
            'prod' => [
                'driver' => 'daily',
                'level' => 'debug',
                'path' => '/XXX/esign.log',
            ]
        ]
    ]
];
$app = new Application($config);
$template = $app->template;
$template->docTemplates($templateId);
```

## Laravel 中使用

### 生成配置文件

```
$ php artisan vendor:publish --provider="QF\LaravelEsign\ServiceProvider"
```

### 使用

```php

$eSign = app('esign');
$eSign->account->createPersonalAccount('thirdId', 'name', 'idNumber');

Esign::account->createPersonalAccount('thirdId', 'name', 'idNumber');
```
## License

The MIT License (MIT). Please see License File for more information.