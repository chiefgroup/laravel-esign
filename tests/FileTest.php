<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileTest extends TestCase
{
    public function testFoo()
    {
        $client = $this->mockClient()->makePartial();

        $url = '';
        $client->expects()->parseJSON('get', [$url])->andReturn(new Collection(['test']));

        $this->assertInstanceOf(StreamedResponse::class,  $client->downloadFile('fileid'));

        $this->assertTrue(true);
    }
}