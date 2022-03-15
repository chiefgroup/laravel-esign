<?php

declare(strict_types=1);

namespace QF\LaravelEsign\Foundation\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use QF\LaravelEsign\Template;

class TemplateProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['template'] = function ($pimple) {
            return new Template\Template($pimple['access_token']);
        };
    }
}
