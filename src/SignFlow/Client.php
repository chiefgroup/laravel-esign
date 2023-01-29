<?php

namespace QF\LaravelEsign\SignFlow;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function createFlowOneStep(array $flowInfo, array $signers, array $docs, array $attachments = [], array $copiers = [])
    {
        $params = compact('docs', 'flowInfo', 'signers');
        $attachments && $params['attachments'] = $attachments;
        $copiers && $params['copiers'] = $copiers;

        return $this->httpPostJson('/api/v2/signflows/createFlowOneStep', $params);
    }

    public function getExecuteUrl($flowId, $accountId, $urlType = 0, $orgId = 0, $appScheme = null)
    {
        $query = [
            'accountId' => $accountId,
            'organizeId' => $orgId,
            'urlType' => $urlType,
            'appScheme' => $appScheme,
        ];

        return $this->httpGet("/v1/signflows/{$flowId}/executeUrl", $query);
    }

    public function downloadDocument($flowId)
    {
        return $this->httpPostJson("/v1/signflows/{$flowId}/documents");
    }
}
