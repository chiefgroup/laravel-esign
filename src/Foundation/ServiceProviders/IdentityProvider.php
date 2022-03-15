<?php



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
