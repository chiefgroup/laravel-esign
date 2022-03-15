<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use XNXK\LaravelEsign\Account;

class AccountProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['account'] = function ($pimple) {
            return new Account\Account($pimple['access_token']);
        };
    }
}
