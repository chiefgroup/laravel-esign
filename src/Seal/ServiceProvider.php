<?php

namespace QF\LaravelEsign\Seal;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['seal'] = function ($app) {
            return new Client($app);
        };
    }
}
