<?php

/*
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace QF\LaravelEsign\Tests;

use QF\LaravelEsign\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        \Mockery::close();
    }
}
