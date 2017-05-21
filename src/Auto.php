<?php

namespace Auto;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Auto\Contracts\AutoInterface;
use PhpAutoWhere\Where;

class Auto implements AutoInterface
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
        $this->_config = (object) Config::get("laravelauto");
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
    public function _class($class){
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
     * get quickly where sql without params
     *
     * @return string
     */
    public function setField($column, $value){
        Request::has("filter[$column]") ?: Request::merge(["filter"=>array_merge(Request::get("filter"),[$column=>$value])]);
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

            }elseif( $method == "or"){ // Keyword reserved of php

                return call_user_func_array(array($this->_class, "_or"), $arguments);

            }
        }
        if( $method == "class"){ // Keyword reserved of php

            return call_user_func_array(array($this, "_class"), $arguments);

        }

        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }
        return $this->getInstance();

    }

}