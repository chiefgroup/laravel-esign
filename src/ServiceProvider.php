<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Esign::class, function () {
            return new Esign(config('esign'));
        });

        $this->app->alias(Esign::class, 'esign');
    }

    public function provides()
    {
        return [Esign::class, 'esign'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('esign.php'),
        ]);
    }
}
