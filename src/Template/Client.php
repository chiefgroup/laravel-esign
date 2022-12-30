<?php

namespace QF\LaravelEsign\Template;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function docTemplates(string $templateId)
    {
        return $this->httpGet("/v1/docTemplates/{$templateId}");
    }

    public function createByTemplate(string $name, string $templateId, array $simpleFormFields, bool $strictCheck = false)
    {
        return $this->httpPostJson('/v1/files/createByTemplate', [
            'name' => $name,
            'templateId' => $templateId,
            'simpleFormFields' => $simpleFormFields,
            'strictCheck' => $strictCheck
        ]);
    }
}
