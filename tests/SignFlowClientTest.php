<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\SignFlow\Client;

class SignFlowClientTest extends TestCase
{
    public function testCreateFlowOneStep()
    {
        $client = $this->mockApiClient(Client::class);

        $flowInfo = [];
        $signers = [];
        $docs = [];
        $client->expects()->httpPostJson('/api/v2/signflows/createFlowOneStep', [
            'flowInfo' => $flowInfo,
            'signers' => $signers,
            'docs' => $docs
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->createFlowOneStep($flowInfo, $signers, $docs));
    }
}
