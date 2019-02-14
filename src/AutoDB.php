<?php
namespace Auto;

use Illuminate\Support\Facades\DB;

class AutoDB
{

    public $db;

    function __construct($pdo = null){
        if(!$pdo) $this->db = DB::connection()->getPdo();
        else $this->db = $pdo;
    }

    function select($query){
        return DB::setPdo($this->db)->select($query);
    }
}
