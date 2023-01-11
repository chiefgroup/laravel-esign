<?php

namespace QF\LaravelEsign\Template;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
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
