<?php

namespace QF\LaravelEsign;

use Illuminate\Support\Facades\Facade;
use QF\LaravelEsign\Account\Account;
use QF\LaravelEsign\File\File;
use QF\LaravelEsign\Identity\Identity;
use QF\LaravelEsign\SignFlow\SignFlow;
use QF\LaravelEsign\Template\Template;

class EsignFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'esign';
    }

    public static function account(): Account
    {
        return app('esign')->account;
    }

    public static function signflow(): SignFlow
    {
        return app('esign')->signflow;
    }

    public static function file(): File
    {
        return app('esign')->file;
    }

    public static function template(): Template
    {
        return app('esign')->template;
    }

    public static function identity(): Identity
    {
        return app('esign')->identity;
    }

}