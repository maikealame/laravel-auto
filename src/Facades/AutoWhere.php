<?php
namespace AutoWhere\Facades;

use Illuminate\Support\Facades\Facade;

class Auto extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AutoWhere\Contracts\AutoWhereInterface';
    }
}