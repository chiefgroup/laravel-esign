<?php

declare(strict_types=1);

namespace QF\LaravelEsign\SignFlow;

use QF\LaravelEsign\Core\AbstractAPI;
use QF\LaravelEsign\Exceptions\HttpException;
use QF\LaravelEsign\Support\Collection;

class SignFlow extends AbstractAPI
{
    // Api URL
    const CREATE_FLOW_NOE_STEP = '/api/v2/signflows/createFlowOneStep';         // 一步发起签署
    const CREATE_SIGN_PROCESS = '/v1/signflows';                                // 签署流程创建
    const PROCESS_DOCUMENT_ADD = '/v1/signflows/%s/documents';                  // 流程文档添加
    const PLATFORM_SIGN_ADD = '/v1/signflows/%s/signfields/platformSign';       // 添加平台方自动盖章签署区
    const HAND_SIGN_ADD = '/v1/signflows/%s/signfields/handSign';               // 添加手动盖章签署区
    const AUTO_SIGN_ADD = 'v1/signflows/%s/signfields/autoSign';                // 添加签署方自动盖章签署区
    const SIGN_PROCESS_START = '/v1/signflows/%s/start';                        // 签署流程开启
    const EXECUTE_URL = '/v1/signflows/%s/executeUrl';                          // 获取签署地址
    const SIGN_PROCESS_ARCHIVE = '/v1/signflows/%s/archive';                    // 签署流程归档
    const SIGN_PROCESS_DOCUMENT = '/v1/signflows/%s/documents';                 // 流程文档下载
    const SIGN_REVOKE = '/v1/signflows/%s/revoke';                              // 签署流程撤销
    const SIGN_PROCESS_STATUS = '/v1/signflows/%s';                             // 签署流程状态查询

    /**
     * 一步发起签署.
     *
     * @param  array  $docs  附件信息
     * @param  array  $flowInfo  抄送人人列表
     * @param  array  $signers  待签文档信息
     * @param  array  $attachments  流程基本信息
     * @param  array  $copiers  签署方信息
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createFlowOneStep($docs, $flowInfo, $signers, $attachments = [], $copiers = [])
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function createSignFlow($businessScene, $noticeDeveloperUrl = null, $autoArchive = true)
    {
        $params = [
            'autoArchive'   => $autoArchive,
            'businessScene' => $businessScene,
            'configInfo'    => [
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function addDocuments($flowId, $fileId, $encryption = 0, $fileName = null, $filePassword = null)
    {
        $url = sprintf(self::PROCESS_DOCUMENT_ADD, $flowId);
        $params = [
            'docs' => [
                [
                    'fileId'       => $fileId,
                    'encryption'   => $encryption,
                    'fileName'     => $fileName,
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function addPlatformSign($flowId, $signFields)
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function addAutoSign($flowId, $signFields)
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function addHandSign($flowId, $signFields)
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function startSignFlow($flowId)
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
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function getExecuteUrl($flowId, $accountId, $urlType = 0, $orgId = 0, $appScheme = null)
    {
        $url = sprintf(self::EXECUTE_URL, $flowId);
        $params = [
            'accountId'  => $accountId,
            'organizeId' => $orgId,
            'urlType'    => $urlType,
            'appScheme'  => $appScheme,
        ];

        return $this->parseJSON('get', [$url, $params]);
    }

    /**
     * 签署流程归档.
     *
     * @param  string  $flowId  流程id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function archiveSign($flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_ARCHIVE, $flowId);

        return $this->parseJSON('put', [$url]);
    }

    /**
     * 流程文档下载.
     *
     * @param  string  $flowId  流程id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function downloadDocument($flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_DOCUMENT, $flowId);

        return $this->parseJSON('get', [$url]);
    }

    /**
     * 签署流程撤销.
     *
     * @param  string  $flowId  流程id
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function revoke($flowId)
    {
        $url = sprintf(self::SIGN_REVOKE, $flowId);

        return $this->parseJSON('put', [$url]);
    }

    /**
     * 签署流程结果查询.
     *
     * @param  string  $flowId  流程id
     * @return Collection|null
     * @throws HttpException
     */
    public function getSignFlowStatus($flowId)
    {
        $url = sprintf(self::SIGN_PROCESS_STATUS, $flowId);

        return $this->parseJSON('get', [$url]);
    }
}
