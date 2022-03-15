<?php

declare(strict_types=1);

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use QF\LaravelEsign\File;

class FileProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['file'] = function ($pimple) {
            return new File\File($pimple['access_token']);
        };
    }
}
