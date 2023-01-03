<?php

namespace QF\LaravelEsign;

use Illuminate\Support\Facades\Facade;
use QF\LaravelEsign\Account\Client;

class Esign extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'esign';
    }

    public static function account(): Client
    {
        return app('esign')->account;
    }

    public function file(): \QF\LaravelEsign\File\Client
    {
        return app('esign')->file;
    }

    public static function template(): \QF\LaravelEsign\Template\Client
    {
        return app('esign')->template;
    }

}
