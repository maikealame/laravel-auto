<?php

namespace AutoWhere;

use Illuminate\Support\Facades\DB;
use AutoWhere\Contracts\AutoWhereInterface;

class Auto implements AutoWhereInterface
{
    public $_core = "php";
    public $_class;
    public $_db;
    public $_dbtype;

    /**
     * Create a new AutoWhere instance.
     */
    public function __construct(){
        $this->_db = (new DB);
        $this->_dbtype = Config::get("autowhere.db");
    }

    /**
     * Initialize module Where
     */
    public function where(){
        $this->_class = new Where($this);
        return $this->getInstance();
    }



    /**
     * Generate chain methods
     *
     * @return Auto
     */
    public function getInstance(){
        return $this;
    }

    public function __call($method,$arguments) {
        if($this->_class) {
            if (method_exists($this->_class, $method)) {
                return call_user_func_array(array($this->_class, $method), $arguments);
            }else return $this;
        }else{
            return call_user_func_array(array($this, $method), $arguments);
        }
    }

}
