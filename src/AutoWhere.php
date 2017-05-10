<?php

namespace Auto;

use Illuminate\Support\Facades\DB;
use maikealame\AutoWhere\Auto as phpAuto;
use maikealame\AutoWhere\Contracts\AutoWhereInterface;

class Auto implements AutoWhereInterface
{

    private $db;

    /**
     * Create a new AutoWhere instance.
     */
    public function __construct(){
        $this->db = Config::get('autowhere.db');
    }

    /**
     * Initialize module Where
     */
    public function where(){
        return phpAuto::where();
    }

}
