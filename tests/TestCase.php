<?php

/*
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace QF\LaravelEsign\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use QF\LaravelEsign\Core\AccessToken;
use QF\LaravelEsign\Core\Http;
use QF\LaravelEsign\File\File;

class TestCase extends BaseTestCase
{
    public function mockClient()
    {
        $accessToken = \Mockery::mock(AccessToken::class);
        $accessToken->shouldReceive('getAppId')->andReturn('app-id');
        $accessToken->shouldReceive('getToken')->andReturn('token');

        Http::setDefaultOptions(['timeout' => 5.0, 'base_uri' => 'https://smlopenapi.esign.cn']);
        $methods = 'downloadFile';
        $file = \Mockery::mock(File::class . "[{$methods}]", [$accessToken])->shouldAllowMockingProtectedMethods();
        $file->allows()->registerHttpMiddlewares()->andReturnNull();

        return $file;
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

}
