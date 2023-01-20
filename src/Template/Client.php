<?php

namespace QF\LaravelEsign\Template;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use QF\LaravelEsign\Kernel\BaseClient;
use QF\LaravelEsign\Kernel\Exceptions\BadResponseException;

class Client extends BaseClient
{
    /**
     * @param string $templateId
     * @return array|object|ResponseInterface|string
     * @throws GuzzleException
     * @throws BadResponseException
     */
    public function docTemplates(string $templateId)
    {
        return $this->httpGet("/v1/docTemplates/{$templateId}");
    }

    public function createByUploadUrl(string $contentMd5, string $fileName, string $contentType = 'application/pdf', $convert2Pdf = false)
    {
        $params = [
            'contentMd5' => $contentMd5,
            'contentType' => $contentType,
            'fileName' => $fileName,
            'convert2Pdf' => $convert2Pdf,
        ];

        return $this->httpPostJson('/v1/docTemplates/createByUploadUrl', $params);
    }
}
