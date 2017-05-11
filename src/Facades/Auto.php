<?php
namespace Auto\Facades;

use Illuminate\Support\Facades\Facade;

class Auto extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Auto\Contracts\AutoInterface';
    }
}