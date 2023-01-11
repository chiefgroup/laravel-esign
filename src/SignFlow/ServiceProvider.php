<?php

namespace QF\LaravelEsign\SignFlow;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['signFlow'] = function ($app) {
            return new Client($app);
        };
    }
}
