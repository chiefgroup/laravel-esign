<?php

namespace QF\LaravelEsign\Kernel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use QF\LaravelEsign\Kernel\Log\LogManager;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        !isset($pimple['log']) && $pimple['log'] = function ($app) {
            $config = $app['config']->get('log');

            if (!empty($config)) {
                $app->rebind('config', $app['config']->merge($config));
            }

            return new LogManager($app);
        };

        !isset($pimple['logger']) && $pimple['logger'] = $pimple['log'];
    }
}