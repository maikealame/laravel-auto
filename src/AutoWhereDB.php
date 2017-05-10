<?php
namespace AutoWhere;

use Illuminate\Support\Facades\DB;

class AutoWhereDB
{
    function __construct(){

    }

    function select($query){
        return DB::select($query);
    }
}