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
    public function scopeAutoWhere($query, $where = [])
    {
        //dd(Auto::where()->table(["t"=>"ticket"])->get());
        $autowhere = Auto::where();
        $qb = $query->getQuery();
        $alias = false;
        $table = [];
        if( is_array( $qb->joins ) ){
            foreach ($qb->joins as $join){
                if( strpos(" as ",$join->table) >= 0 ){
                    // Has Alias
                    $table[explode( " as ", $join->table )[1]] = explode( " as ", $join->table )[0];
                }else{
                    $table[$join->table] = $join->table;
                }
            }
        }
        if( strpos(" as ",$qb->from) >= 0 ){
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
        return $query->whereRaw( $autowhere->get() );
    }
}