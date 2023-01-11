<?php

namespace QF\LaravelEsign\Seal;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function createPersonalTemplate(string $accountId, $alias = '', $color = 'RED', $height = 95, $width = 95, $type = 'SQUARE')
    {
        $params = [
            'alias' => $alias,
            'color' => $color,
            'height' => $height,
            'width' => $width,
            'type' => $type,
        ];

        return $this->httpPostJson("/v1/accounts/{$accountId}/seals/personaltemplate", $params);
    }

    /**
     * ===========
     * V3
     * ===========
     */

    public function creteByTemplate(string $psnId, string $name, string $style, string $size)
    {
        return $this->httpPostJson('/v3/seals/psn-seals/create-by-template', [
            'psnId' => $psnId,
            'sealName' => $name,
            'sealTemplateStyle' => $style,
            'sealSize' => $size
        ]);
    }
}
