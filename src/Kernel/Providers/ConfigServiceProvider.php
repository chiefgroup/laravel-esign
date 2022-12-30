<?php

namespace QF\LaravelEsign\Kernel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use QF\LaravelEsign\Kernel\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        !isset($pimple['config']) && $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}
