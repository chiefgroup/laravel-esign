<?php

namespace QF\LaravelEsign\Tests;

use Illuminate\Contracts\Support\DeferrableProvider;
use QF\LaravelEsign\ServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function test_services_are_registered()
    {
        $this->assertInstanceOf(DeferrableProvider::class, new ServiceProvider($this->app));
    }
}
