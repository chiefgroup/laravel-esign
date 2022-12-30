<?php
require_once "vendor/autoload.php";

$config = [
    'app_id' => '7438810467',
    'secret' => '',
    'response_type' => 'collection',
    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/esign.log',
    ]
];
$app = new \QF\LaravelEsign\Application($config);
//$log = $app->config;
//var_dump($log);
//$accessToken = $app->access_token;
//var_dump($accessToken->getToken(true));
//var_dump($app->getConfig());

$account = $app->account;
//$r = $account->queryPersonalAccountByThirdId('t');
//$r = $account->queryPersonalAccountByAccountId('de01302853764c7c8b157521c11fd6c3');
$r = $account->uploadFile('https://qf-common-test.oss-cn-zhangjiakou.aliyuncs.com/qifu_operation/contract/1672292150805_test.pdf', 'name.pdf', 100);
var_dump($r);
