<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\SignFlow;

use XNXK\LaravelEsign\Core\AbstractAPI;
use XNXK\LaravelEsign\Exceptions\HttpException;
use XNXK\LaravelEsign\Support\Collection;

class SignFlow extends AbstractAPI
{
    // Api URL
    public const CREATE_FLOW_NOE_STEP = '/api/v2/signflows/createFlowOneStep';         // 一步发起签署
    public const CREATE_SIGN_PROCESS = '/v1/signflows';                                // 签署流程创建
    public const PROCESS_DOCUMENT_ADD = '/v1/signflows/%s/documents';                  // 流程文档添加
    public const PLATFORM_SIGN_ADD = '/v1/signflows/%s/signfields/platformSign';       // 添加平台方自动盖章签署区
    public const HAND_SIGN_ADD = '/v1/signflows/%s/signfields/handSign';               // 添加手动盖章签署区
    public const AUTO_SIGN_ADD = 'v1/signflows/%s/signfields/autoSign';                // 添加签署方自动盖章签署区
    public const SIGN_PROCESS_START = '/v1/signflows/%s/start';                        // 签署流程开启
    public const EXECUTE_URL = '/v1/signflows/%s/executeUrl';                          // 获取签署地址
    public const SIGN_PROCESS_ARCHIVE = '/v1/signflows/%s/archive';                    // 签署流程归档
    public const SIGN_PROCESS_DOCUMENT = '/v1/signflows/%s/documents';                 // 流程文档下载
    public const SIGN_REVOKE = '/v1/signflows/%s/revoke';                              // 签署流程撤销
    public const SIGN_PROCESS_STATUS = '/v1/signflows/%s';                             // 签署流程状态查询

    /**
     * 一步发起签署.
     *
     * @param  array  $docs  附件信息
     * @param  array  $flowInfo  抄送人人列表
     * @param  array  $signers  待签文档信息
     * @param  array  $attachments  流程基本信息
     * @param  array  $copiers  签署方信息
     *
     * @throws HttpException
     */
    public function createFlowOneStep(array $docs, array $flowInfo, array $signers, array $attachments = [], array $copiers = []): ?Collection
    {
        $params = compact('docs', 'flowInfo', 'signers');
        $attachments and $params['attachments'] = $attachments;
        $copiers and $params['copiers'] = $copiers;

        return $this->parseJSON('json', [self::CREATE_FLOW_NOE_STEP, $params]);
    }

    /**
     * 签署流程创建.
     *
     * @param  string  $businessScene  文件主题
     * @param  string  $noticeDeveloperUrl  回调通知地址
     * @param  bool  $autoArchive  是否自动归档
     *
     * @throws HttpException
     */
    public function createSignFlow(string $businessScene, ?string $noticeDeveloperUrl = null, bool $autoArchive = true): ?Collection
    {
        $params = [
            'autoArchive' => $autoArchive,
            'businessScene' => $businessScene,
            'configInfo' => [
                'noticeDeveloperUrl' => $noticeDeveloperUrl,
            ],
        ];

        return $this->parseJSON('json', [self::CREATE_SIGN_PROCESS, $params]);
    }

    /**
     * 流程文档添加.
     *
     * @param  string  $flowId  流程id
     * @param  string  $fileId  文档id
     * @param  int  $encryption  是否加密
     * @param  null  $fileName  文件名称
     * @param  null  $filePassword  文档密码
     *
     * @throws HttpException
     */
    public function addDocuments(string $flowId, string $fileId, int $encryption = 0, $fileName = null, $filePassword = null): ?Collection
    {
        $url = sprintf(self::PROCESS_DOCUMENT_ADD, $flowId);
        $params = [
            'docs' => [
                [
                    'fileId' => $fileId,
                    'encryption' => $encryption,
                    'fileName' => $fileName,
                    'filePassword' => $filePassword,
                ],
            ],
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 添加平台自动盖章签署区.
     *
     * @param  string  $flowId  流程id
     * @param  string  $signFields  签署区列表数据
     *
     * @throws HttpException
     */
    public function addPlatformSign(string $flowId, string $signFields): ?Collection
    {
        $url = sprintf(self::PLATFORM_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 添加签署方自动盖章签署区.
     *
     * @param  string  $flowId  流程id
     * @param  array  $signFields  签署区列表数据
     *
     * @throws HttpException
     */
    public function addAutoSign(string $flowId, array $signFields): ?Collection
    {
        $url = sprintf(self::AUTO_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 添加手动盖章签署区.
     *
     * @param  string  $flowId  流程id
     * @param  array  $signFields  签署区列表数据
     *
     * @throws HttpException
     */
    public function addHandSign(string $flowId, array $signFields): ?Collection
    {
        $url = sprintf(self::HAND_SIGN_ADD, $flowId);
        $params = [
            'signfields' => $signFields,
        ];

        return $this->parseJSON('json', [$url, $params]);
    }

    /**
     * 签署流程开启.
     *
     * @param  string  $flowId  流程id
     *
     * @throws HttpException
     */
    public function startSignFlow(string $flowId): ?Collection
    {
        $url = sprintf(self::SIGN_PROCESS_START, $flowId);

        return $this->parseJSON('put', [$url]);
    }

    /**
     * 获取签署地址
     *
     * @param  string  $flowId  流程id
     * @param  string  $accountId  签署人账号id
     * @param  null  $orgId  指定机构id
     * @param  int  $urlType  链接类型: 0-签署链接;1-预览链接;默认0
     * @param  null  $appScheme  app内对接必传
     *
     * @throws HttpException
     */
    public function getExecuteUrl(string $flowId, string $accountId, int $urlType = 0, $orgId = 0, $appScheme = null): ?Collection
    {
        $url = sprintf(self::EXECUTE_URL, $flowId);
        $params = [
            'accountId' => $accountId,
            'organizeId' => $orgId,
            'urlType' => $urlType,
            'appScheme' => $appScheme,
        ];

        return $this->parseJSON('get', [$url, $params]);
    }

    /**
     * 签署流程归档.
     *
     * @param  string  $flowId  流程id
     *
     * @throws HttpException
     */
    public function archiveSign(string $flowId): ?Collection
    {
        $url = sprintf(self::SIGN_PROCESS_ARCHIVE, $flowId);

        return $this->parseJSON('put', [$url]);
    }

    /**
     * 流程文档下载.
     *
     * @param  string  $flowId  流程id
     *
     * @throws HttpException
     */
    public function downloadDocument(string $flowId): ?Collection
    {
        $url = sprintf(self::SIGN_PROCESS_DOCUMENT, $flowId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 签署流程撤销.
     *
     * @param  string  $flowId  流程id
     *
     * @throws HttpException
     */
    public function revoke(string $flowId): ?Collection
    {
        $url = sprintf(self::SIGN_REVOKE, $flowId);

        return $this->parseJSON('put', [$url]);
    }

    /**
     * 签署流程结果查询.
     *
     * @param  string  $flowId  流程id
     *
     * @throws HttpException
     */
    public function getSignFlowStatus(string $flowId): ?Collection
    {
        $url = sprintf(self::SIGN_PROCESS_STATUS, $flowId);

        return $this->parseJSON('get', [$url]);
    }
}
