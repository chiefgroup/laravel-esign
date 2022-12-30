<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 * (c) peng <2512422541@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign;

use Illuminate\Contracts\Support\DeferrableProvider;
use \Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{

    public function register()
    {
        $this->app->singleton(Application::class, function () {
            return new Application(config('esign'));
        });

        $this->app->alias(Application::class, 'esign');
    }

    public function provides()
    {
        return [Application::class, 'esign'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('esign.php'),
        ], 'esign');
    }
}
