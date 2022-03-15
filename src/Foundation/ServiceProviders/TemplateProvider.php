<?php



namespace QF\LaravelEsign\Foundation\ServiceProviders;

use QF\LaravelEsign\Template;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TemplateProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['template'] = function ($pimple) {
            return new Template\Template($pimple['access_token']);
        };
    }
}
