<?php
namespace maikealame\AutoWhere\Facades;

use Illuminate\Support\Facades\Facade;

class Whatsapi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'maikealame\AutoWhere\Contracts\AutoWhereInterface';
    }
}