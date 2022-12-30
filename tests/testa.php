<?php

require_once "vendor/autoload.php";

$config = [
    'app_id' => '7438810467',
    'secret' => '',
    'response_type' => 'collection',
    'log' => [
        'level' => 'debug',
        'file' => '/tmp/esign.log',
    ]
];
$app = new \QF\LaravelEsign\Application($config);
//$log = $app->config;
//var_dump($log);
//$accessToken = $app->access_token;
//var_dump($accessToken->getToken(true));
//var_dump($app->getConfig());

//$account = $app->account;
//$r = $account->queryPersonalAccountByThirdId('t');
//$r = $account->queryPersonalAccountByAccountId('de01302853764c7c8b157521c11fd6c3');
$str = 'https://qf-common-test.oss-cn-zhangjiakou.aliyuncs.com/qifu_operation/contract/1672292150805_test.pdf';
//$r = $account->uploadFile($str, 'name.pdf', 100);

//$file = $app->file;
//$r = $file->getUploadUrl($str, 'name.pdf', 100);
$template = $app->template;
$templateId = '2c76ab829f75457eb07fe2fd4ef5705c';
$r = $template->createByTemplate('文件.pdf', $templateId, ['test'=>'abc']);
//$r = $template->docTemplates($templateId);
var_dump($r);
