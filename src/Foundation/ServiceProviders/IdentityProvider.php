<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use XNXK\LaravelEsign\Identity;

class IdentityProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['identity'] = static function ($pimple) {
            return new Identity\Identity($pimple['access_token']);
        };
    }
}
