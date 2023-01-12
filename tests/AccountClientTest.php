<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\Account\Client;
use QF\LaravelEsign\ConstMapping;

class AccountClientTest extends TestCase
{
    public function testCreatePersonalAccount()
    {
        $client = $this->mockApiClient(Client::class);

        $thirdPartyUserId = 'foo';
        $mobile = '13012341234';
        $name = 'bar';
        $idNumber = 'number';
        $email = '';
        $idType = ConstMapping::INDIVIDUAL_CH_IDCARD;

        $client->expects()->httpPostJson('/v1/accounts/createByThirdPartyUserId', [
            'thirdPartyUserId' => $thirdPartyUserId,
            'name' => $name,
            'idType' => $idType,
            'idNumber' => $idNumber,
            'mobile' => $mobile,
            'email' => $email,
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->createPersonalAccount($thirdPartyUserId, $mobile, $name, $idNumber, $email, $idType));
    }
}
