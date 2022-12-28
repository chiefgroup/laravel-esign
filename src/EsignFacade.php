<?php

namespace QF\LaravelEsign;

use Illuminate\Support\Facades\Facade;

class EsignFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'esign';
    }
}