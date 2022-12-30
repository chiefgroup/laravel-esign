<?php

/*
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace QF\LaravelEsign\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Pimple\Container;
use QF\LaravelEsign\Auth\AccessToken;

class TestCase extends BaseTestCase
{
    public function mockApiClient($name, $methods = [], Container $app = null)
    {
        $methods = implode(',', array_merge([
            'httpGet', 'httpPost', 'httpPostJson', 'httpUpload',
            'request', 'requestRaw', 'requestArray', 'registerMiddlewares',
        ], (array) $methods));

        $client = \Mockery::mock(
            $name."[{$methods}]",
            [
                $app ?? \Mockery::mock(Container::class),
                \Mockery::mock(AccessToken::class)
            ]
        )->shouldAllowMockingProtectedMethods();
        $client->allows()->registerHttpMiddlewares()->andReturnNull();

        return $client;
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }
}
