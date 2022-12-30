<?php

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\File\Client;

class FileTest extends TestCase
{
    public function testGetUploadUrl()
    {
        $client = $this->mockApiClient(Client::class, ['getFileBase64Md5']);

        $fileMd5 = 'foo';
        $fileName = 'bar.pdf';
        $fileSize = 10;

        $client->expects()->httpPostJson('/v1/files/getUploadUrl', [
            'contentMd5' => $fileMd5,
            'fileName' => $fileName,
            'fileSize' => $fileSize,
            'contentType' => 'application/pdf',
            'convert2Pdf' => false,
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->getUploadUrl($fileMd5, $fileName, $fileSize));
    }
}
