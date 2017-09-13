<?php
namespace Auto;

use Illuminate\Support\Facades\DB;

class AutoDB
{

    public $db;

    function __construct(){
        $this->db = DB::connection()->getPdo();
    }

    function select($query){
        return DB::select($query);
    }
}