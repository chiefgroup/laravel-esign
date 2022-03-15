<?php

declare(strict_types=1);

namespace XNXK\LaravelEsign;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register(): void
    {
        $this->app->singleton(Esign::class, static function () {
            return new Esign(config('esign'));
        });

        $this->app->alias(Esign::class, 'esign');
    }

    public function provides()
    {
        return [Esign::class, 'esign'];
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('esign.php'),
        ]);
    }
}
