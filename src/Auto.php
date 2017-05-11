<?php

namespace Auto;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Auto\Contracts\AutoWhereInterface;
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
        $this->_db = new AutoDB();
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

    /**
     * get quickly where sql without params
     *
     * @return string
     */
    public function get(){
        $where = Request::get('filter') ? Request::get('filter') : [];
        $columns = Request::get('columns') ? Request::get('columns') : [];
        return $this->_class->columns($columns)->render($where);
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