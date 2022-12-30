<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\Template\Client;

class TemplateClientTest extends TestCase
{
    public function testDocTemplates()
    {
        $client = $this->mockApiClient(Client::class);
        $templateId = 'id';
        $client->expects()->httpGet("/v1/docTemplates/{$templateId}")->andReturn("mock-result");

        $this->assertSame('mock-result', $client->docTemplates($templateId));
    }
}
