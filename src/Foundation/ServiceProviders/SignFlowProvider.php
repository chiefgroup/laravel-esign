<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use QF\LaravelEsign\SignFlow;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SignFlowProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['signflow'] = function ($pimple) {
            return new SignFlow\SignFlow($pimple['access_token']);
        };
    }
}
