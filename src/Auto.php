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
    public $having = [];
    public $fields = [];

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
        foreach($this->fields as $k=>$v){
            $where[$k] = $v;
        }
        $having = Request::get('filter_having') ? Request::get('filter_having') : [];
        foreach($having as $k=>$v){ unset($where[$k]); }
        $columns = Request::get('columns') ? Request::get('columns') : [];
        return $this->_class->columns($columns)->render($where);
    }

    /**
     * get quickly having sql without params
     *
     * @return string
     */
    public function getHaving(){
        $where = Request::get('filter') ? Request::get('filter') : [];
        foreach($this->fields as $k=>$v){
            $where[$k] = $v;
        }
        $hav = Request::get('filter_having') ? Request::get('filter_having') : [];
        $having = array_filter($where,function($key) use($hav){
            return in_array($key,array_keys($hav));
        },ARRAY_FILTER_USE_KEY);
        $r = $this->_class->columns($hav)->render($having);
        return  trim($r) == "true" ? "true" : $r;
    }


    /**
     * set default field value for one column in the query
     *
     * @return void
     */
    public function setDefaultField($column, $value){
        Request::has("filter[$column]") ?: Request::merge(["filter"=>array_merge(Request::get("filter")?Request::get("filter"):[],[$column=>$value])]);
    }

    /**
     * set field value for one column in the query
     *
     * @return void
     */
    public function setField($column, $value){
//        Request::has("filter") ?: Request::merge(["filter"=>[]]);
//        Request::merge(["filter"=>array_merge(Request::get("filter"),[$column=>$value])]);
        $this->fields = array_merge($this->fields,[$column=>$value]);
    }

    /**
     * set column type in the query
     *
     * @return void
     */
    public function setColumn($column, $type){
        Request::has("columns") ?: Request::merge(["columns"=>[]]);
        Request::merge(["columns"=>array_merge(Request::get("columns"),[$column=>$type])]);
    }

    /**
     * set withTrashed soft delete
     *
     * @return void
     */
    public function withTrashed(){
        Request::merge(["trashed"=> Request::has("trashed") ? Request::get("trashed") : 1]);
    }

    /**
     * set withoutTrashed soft delete
     *
     * @return void
     */
    public function withoutTrashed(){
        Request::merge(["trashed"=> Request::has("trashed") ? Request::get("trashed") : 0]);
    }


    /**
     * set onlyTrashed soft delete
     *
     * @return void
     */
    public function onlyTrashed(){
        Request::merge(["trashed"=> Request::has("trashed") ? Request::get("trashed") : 2]);
    }



    /**
     * set ignore filter
     *
     * @return void
     */
    public function ignore($column){
        Request::has("filter_ignore") ?: Request::merge(["filter_ignore"=>[]]);
        Request::merge(["filter_ignore"=>array_push(Request::get("filter_ignore"),$column)]);
    }


    /**
     * set having filter, excludes from 'where' on query
     *
     * @return void
     */
    public function having($column, $type = null){
        Request::has("filter_having") ?: Request::merge(["filter_having"=>[]]);
        Request::merge(["filter_having"=>
            is_array($column) ?
                array_merge(Request::get("filter_having"),$column) :
                array_push(Request::get("filter_having"),[$column=>$type])
        ]);
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