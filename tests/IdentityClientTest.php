<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\ConstMapping;
use QF\LaravelEsign\Identity\Client;

class IdentityClientTest extends TestCase
{
    public function testVerifyBankCard4Factors()
    {
        $client = $this->mockApiClient(Client::class);

        $name = 'bar';
        $idNo = 'number';
        $mobileNo = 'mobile';
        $bankCardNo = 'bankNo';
        $certType = ConstMapping::INDIVIDUAL_CH_IDCARD;
        $contextId = 'context';
        $notifyUrl = '';

        $client->expects()->httpPostJson('/v2/identity/auth/api/individual/bankCard4Factors', [
            'name' => $name,
            'idNo' => $idNo,
            'mobileNo' => $mobileNo,
            'bankCardNo' => $bankCardNo,
            'certType' => $certType,
            'notifyUrl' => $notifyUrl,
            'contextId' => $contextId,
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->verifyBankCard4Factors($name, $idNo, $mobileNo, $bankCardNo, $certType, $contextId));
    }
}
