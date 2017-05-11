<?php
namespace Auto;

use Auto\Exceptions\AutoWhereException;
use Auto\Facades\Auto;

/**
 * AutoWhere trait.
 */
trait AutoWhere
{
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

        // fix error of soft Delete -> column deleted_at not found
        if( $this->forceDeleting !== null ){ // is using soft delete
            if( empty( array_filter( // not using withTrashed()
                $query->removedScopes(),
                function($var){
                    return stristr( $var, "SoftDelet" ) ? $key : false;
                }
            ) ) ) {
                if ($this->getTableOrAlias($qb) != $this->getTable()) // table not equal table, maybe "table as t"
                    $query = $query->withTrashed()->where($this->getTableOrAlias($qb) . ".deleted_at");
            }
        }

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
}