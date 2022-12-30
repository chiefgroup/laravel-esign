<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['access_token'] = function ($app) {
            return new AccessToken($app);
        };
    }
}
