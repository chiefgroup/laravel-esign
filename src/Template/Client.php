<?php

namespace QF\LaravelEsign\Template;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function docTemplates(string $templateId)
    {
        return $this->httpGet("/v1/docTemplates/{$templateId}");
    }

    /**
     * 填充内容生成PDF
     *
     * @see https://open.esign.cn/doc/opendoc/saas_api/siipw3
     *
     * @param string $name
     * @param string $templateId
     * @param array $simpleFormFields
     * @param bool $strictCheck
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createByTemplate(string $name, string $templateId, array $simpleFormFields, bool $strictCheck = false)
    {
        return $this->httpPostJson('/v1/files/createByTemplate', [
            'name' => $name,
            'templateId' => $templateId,
            'simpleFormFields' => $simpleFormFields,
            'strictCheck' => $strictCheck
        ]);
    }

    /**
     * 查询PDF文件详情
     *
     * @see https://open.esign.cn/doc/opendoc/saas_api/yingmd
     *
     * @param string $fileId
     * @return array|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function files(string $fileId)
    {
        return $this->httpGet("/v1/files/{$fileId}");
    }
}
