<?php

namespace AutoWhere;

use Illuminate\Support\Facades\DB;
use AutoWhere\Contracts\AutoWhereInterface;
use Illuminate\Support\Facades\Config;
use PhpAutoWhere\Where;

class Auto implements AutoWhereInterface
{
    public $_core = "laravel";
    public $_class;
    public $_config;
    public $_db;
    public $_dbtype;

    /**
     * Create a new AutoWhere instance.
     */
    public function __construct(){
        $this->_db = new AutoWhereDB();
        $this->_config = (object) Config::get("autowhere");
        $this->_dbtype = $this->_config->db["type"];
    }



    /**
     * Initialize module Where
     */
    public function where(){
        $this->_class = new Where($this);
        return $this->getInstance();
    }

    /**
     * Set table by class
     *
     * @param $class
     *
     * @return Auto
     */
    public function class($class){
        $this->_class->table( (new $class)->getTable() );
        return $this->getInstance();
    }

    public function get(){

    }


    /**
     * Generate chain methods
     *
     * @return Auto
     */
    public function getInstance(){
        return $this;
    }


    /**
     * Call functions in $_class or $this
     *
     * @return mixed
     */
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
