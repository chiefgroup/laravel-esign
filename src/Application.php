<?php

/*
 * This file is part of the chiefgroup/laravel-esign.
 *
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace QF\LaravelEsign;

use QF\LaravelEsign\Kernel\Providers\ConfigServiceProvider;
use QF\LaravelEsign\Kernel\Providers\HttpClientServiceProvider;
use QF\LaravelEsign\Kernel\Providers\LogServiceProvider;
use Pimple\Container;

/**
 * Class Application.
 *
 * @property \QF\LaravelEsign\Kernel\Config $config
 * @property \QF\LaravelEsign\Auth\AccessToken $access_token
 *
 * @property \QF\LaravelEsign\Account\Client $account
 * @property \QF\LaravelEsign\File\Client $file
 * @property \QF\LaravelEsign\Template\Client $template
 */
class Application extends Container
{
    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * @var string[]
     */
    protected array $providers = [
        Auth\ServiceProvider::class,
        Account\ServiceProvider::class,
        File\ServiceProvider::class,
        Template\ServiceProvider::class
    ];

    public function __construct(array $config = [])
    {
        $this->userConfig = $config;

        parent::__construct($config);

        $this->registerProviders($this->getProviders());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            // http://docs.guzzlephp.org/en/stable/request-options.html
            'http' => [
                'timeout' => 30.0,
                'base_uri' => 'https://smlopenapi.esign.cn',
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    public function setProviders(array $providers)
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class,
            HttpClientServiceProvider::class,
            LogServiceProvider::class
        ], $this->providers);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param array $providers
     * @return void
     */
    private function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->register(new $provider());
        }
    }


    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (is_callable([$this['fundamental.api'], $method])) {
            return call_user_func_array([$this['fundamental.api'], $method], $args);
        }

        throw new \Exception("Call to undefined method {$method}()");
    }
}
