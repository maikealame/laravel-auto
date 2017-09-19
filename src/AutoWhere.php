<?php
namespace Auto;

use Illuminate\Support\Facades\Request;
use Auto\Exceptions\AutoWhereException;
use Auto\Facades\Auto;

/**
 * AutoWhere trait.
 */
trait AutoWhere
{
    private $autoTrash = null;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array|null                         $where
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoWhere($query, $options = [])
    {
        // Alias options
        $autowhere = Auto::where();
        $qb = $query->getQuery();
        $table = [];
        if( is_array( $qb->joins ) ){
            foreach ($qb->joins as $join){
                if( strpos($join->table, " as ") !== false ){
                    // Has Alias
                    $table[explode( " as ", $join->table )[1]] = explode( " as ", $join->table )[0];
                }else{
                    $table[$join->table] = $join->table;
                }
            }
        }
        if( strpos($qb->from, " as ") !== false ){
            // Has Alias
            $table[explode( " as ", $qb->from )[1]] = explode( " as ", $qb->from )[0];
        }else{
            // Hasn't Alias
            if(empty($table))
                $table = $qb->from; // where column
            else
                $table[$qb->from] = $qb->from; // where table.column
        }
        $autowhere->table($table);
//        dd( $table );


        // Or options

        if(isset($options["or"])){
            $autowhere->or($options["or"]);
        }

        // Columns options

        if(isset($options["columns"])){
            $autowhere->columns($options["columns"]);
        }

        // Having options

        if(isset($options["having"])){
            $autowhere->having = array_merge($autowhere->having,$options["having"]);
        }
        if(Request::has("filter_having")){
            $autowhere->having = array_merge($autowhere->having,Request::get("filter_having"));
        }


        // fix error of soft Delete -> column deleted_at not found
        if( $this->forceDeleting !== null ){ // is using soft delete
            if( empty( array_filter( // not using withTrashed()
                $query->removedScopes(),
                function($var,$key){
                    return stristr( $var, "SoftDelet" ) ? $key : false;
                }
            ) ) ) {
//                if ($this->getTableOrAlias($qb) != $this->getTable()) // table not equal table, maybe "table as t"
                if(Request::has("show_disabled")){
                    $query = $query->withTrashed();
                }elseif(Request::has("only_disabled")){
                    $query = $query->onlyTrashed();
                }elseif(Request::has("trashed")){
                    if(!is_null($this->autoTrash) ? $this->autoTrash : Request::get("trashed") == 0){ // without
//                            $query = $query->withoutTrashed();
                        $query = $query->withTrashed()->whereNull($this->getTableOrAlias($qb) . ".deleted_at");
                    }
                    if(!is_null($this->autoTrash) ? $this->autoTrash : Request::get("trashed") == 1){ // with
//                            $query = $query->withTrashed();
                        $query = $query->withTrashed();
                    }
                    if(!is_null($this->autoTrash) ? $this->autoTrash : Request::get("trashed") == 2){ // only
//                            $query = $query->onlyTrashed();
                        $query = $query->withTrashed()->whereNotNull($this->getTableOrAlias($qb) . ".deleted_at");
                    }
                }else
                    $query = $query->withTrashed()->where($this->getTableOrAlias($qb) . ".deleted_at");
            }
        }

        if(count($autowhere->having)) $query->havingRaw( $autowhere->getHaving() );

        return $query->whereRaw( $autowhere->get() );
    }


    private function getTableOrAlias($qb){
        if( strpos($qb->from, " as ") !== false ){
            // Has Alias
            return explode( " as ", $qb->from )[1];
        }else {
            // Hasn't Alias
            return $qb->from;
        }
    }

    /**
     * set field value for one column in the query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoSetField($query, $column, $value, $type = null)
    {
        Auto::setField($column, $value);
        if($type){
            Auto::setColumn($column, $type);
        }
        return $query;
    }

    /**
     * set default field value for one column in the query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoSetDefaultField($query, $column, $value, $type = null)
    {
        Auto::setDefaultField($column, $value);
        if($type){
            Auto::setColumn($column, $type);
        }
        return $query;
    }

    /**
     * set column type in the query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoSetColumn($query, $column, $type)
    {
        Auto::setColumn($column, $type);
        return $query;
    }

    /**
     * set withTrashed soft delete
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoWithTrashed($query){
        $this->autoTrash = 1;
        return $query;
    }

    /**
     * set withoutTrashed soft delete
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoWithoutTrashed($query){
        $this->autoTrash = 0;
        return $query;
    }


    /**
     * set onlyTrashed soft delete
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoOnlyTrashed($query){
        $this->autoTrash = 2;
        return $query;
    }
}