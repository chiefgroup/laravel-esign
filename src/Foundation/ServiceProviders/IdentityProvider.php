<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use QF\LaravelEsign\Identity;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class IdentityProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['identity'] = function ($pimple) {
            return new Identity\Identity($pimple['access_token']);
        };
    }
}
