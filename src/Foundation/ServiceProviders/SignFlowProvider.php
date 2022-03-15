<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use XNXK\LaravelEsign\SignFlow;

class SignFlowProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['signflow'] = static function ($pimple) {
            return new SignFlow\SignFlow($pimple['access_token']);
        };
    }
}
