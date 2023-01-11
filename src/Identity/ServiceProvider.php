<?php

namespace QF\LaravelEsign\Identity;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['identity'] = function ($app) {
            return new Client($app);
        };
    }
}
