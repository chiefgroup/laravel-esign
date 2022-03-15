<?php

declare(strict_types=1);

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use QF\LaravelEsign\Identity;

class IdentityProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['identity'] = function ($pimple) {
            return new Identity\Identity($pimple['access_token']);
        };
    }
}
