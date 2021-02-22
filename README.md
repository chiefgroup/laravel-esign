# chiefgroup-esign
esign API v2.

## 安装

```
$ composer require chiefgroup/laravel-esign
```

## 配置文件

generate config file
```
$ php artisan vendor:publish --provider="QF\LaravelEsign\ServiceProvider"
```

## 使用

```php
$eSign = app('esign');
$thirdPartyUserId = 'your_party_user_id'; // 用户唯一标识，可传入第三方平台的个人用户id、证件号、手机号、邮箱等，如果设置则作为账号唯一性字段，相同信息不可重复创建。
$name = 'your_name'; // 姓名
$idType = 'CRED_PSN_CH_IDCARD'; // 证件类型
$idNumber = 'your_id_number'; // 证件号
$mobile = 'your_mobile'; // 手机号, 签署流程开始时对应的签署人会收到短信通知
$accountService = app('esign')->account; // 账户模块相关
// 个人账户创建, 有唯一标志, 需要记录返回的 accountId
//$accountInfo =  $accountService->createPersonalAccount($thirdPartyUserId, $mobile, $name, $idNumber, $email, $idType);
$orgInfo =  $accountService->createOrganizeAccount($orgThirdPartyUserId, 'b5b9c524fa254c0fbf2150c98b87ac11', $name);
$accountInfo1 = $accountService->updatePersonalAccountByThirdId($thirdPartyUserId, $mobile, $idNumber = null, $name = null);
$accountInfo2 = $accountService->queryPersonalAccountByAccountId('b5b9c524fa254c0fbf2150c98b87ac45');
$orgInfo2 = $accountService->queryOrganizeAccountByOrgId($orgInfo['orgId']);
$accountId = $accountInfo['accountId'];

$fileService = app('esign')->file; // 合同文件模块
$path = public_path().'/template/合同.pdf';
$md5 = getContentBase64Md5($path);
$result = $accountService->createByUploadFile($path, basename($path)); // 通过上传方式创建模板
$contract_file = file_get_contents($path);
$fileSize = strlen($contract_file);
$result2 = $fileService->getUploadFile($path, 'xx合同', $fileSize); // 通过上传方式创建文件
$fileId = $result2['fileId'];

$signService = app('esign')->signflow; // 合同签署模块实例

// 创建一个签署流程
$flowInfo = $signService->createFlowOneStep("租赁合同");
$flowId = $flowInfo['flowId'];
// 一步发起签署
$signService->createFlowOneStep($docs, $flowInfo, $signers); 

// 把文档加入签署流程中
$addDocRet = $signService->addDocuments($flowId, $fileId);
// 在签署流程中添加一个手动签署区域, 前提是流程已经添加文档, 同时指定签署人 accountId
$handSignData = $signService->addHandSign($flowId, $fileId, $accountId, 1, 100, 100);
// 签署流程开始, 签署人会收到通知 (前提有 mobile/email)
$startSignFlowRet = $signService->startSignFlow($flowId);
echo $startSignFlowRet;
```
## License

The MIT License (MIT). Please see License File for more information.