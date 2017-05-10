<?php

namespace MaikeAlame\AutoWhere;

use Illuminate\Support\Facades\DB;
use maikealame\AutoWhere\Auto as phpAuto;
use maikealame\AutoWhere\Contracts\AutoWhereInterface;

class Auto implements AutoWhereInterface
{

    // instance for chain methods
    public $_class;
    public $_db;

    /**
     * Create a new AutoWhere instance.
     */
    public function __construct(){
        $this->_db = (new DB);
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
