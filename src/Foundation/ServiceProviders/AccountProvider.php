<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use QF\LaravelEsign\Account;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AccountProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['account'] = function ($pimple) {
            return new Account\Account($pimple['access_token']);
        };
    }
}
