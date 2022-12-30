<?php

namespace QF\LaravelEsign\Account;

use QF\LaravelEsign\Kernel\BaseClient;

class Client extends BaseClient
{
    public function queryPersonalAccountByThirdId($thirdId)
    {
        $params = [
            'thirdPartyUserId' => $thirdId,
        ];

        return $this->httpPostJson('/v1/accounts/getByThirdId', $params);
    }

    public function queryPersonalAccountByAccountId($accountId)
    {
        return $this->httpGet("/v1/accounts/{$accountId}");
    }
}
