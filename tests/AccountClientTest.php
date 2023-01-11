<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\Account\Client;

class AccountClientTest extends TestCase
{
    public function testCreatePersonalAccount()
    {
        $client = $this->mockApiClient(Client::class);

        $thirdPartyUserId = 'foo';
        $name = 'bar';
        $idType = 'CRED_PSN_CH_IDCARD';
        $idNumber = 'number';

        $client->expects()->httpPostJson('/v1/accounts/createByThirdPartyUserId', [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'mobile' => null,
            'email' => null,
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->createPersonalAccount($thirdPartyUserId, $name, $idNumber, $idType));
    }
}
