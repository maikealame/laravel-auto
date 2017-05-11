<?php
namespace Auto;

use Illuminate\Support\Facades\DB;

class AutoDB
{
    function __construct(){

    }

    function select($query){
        return DB::select($query);
    }
}