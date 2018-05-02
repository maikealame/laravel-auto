<?php
namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Auto\Exceptions\AutoPaginateException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * AutoPaginate trait.
 */
trait AutoPaginate
{
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array|null                         $defaultSortParameters
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoPaginate($query, $length = null)
    {
        if( is_null( $length ) ) $length = Config::get("laravelauto.pages.default_length");
        $havings = $query->getQuery()->havings ?: [];
        if(!count( $havings ))
            return $query->paginate( Request::has("length") ? Request::get("length") : $length );
        else{
            $perPage = Request::has("length") ? Request::get("length") : $length;
            $curPage = Paginator::resolveCurrentPage(); // reads the query string, defaults to 1

// clone the query to make 100% sure we don't have any overwriting
            $itemQuery = clone $query;
//            $itemQuery->addSelect('posts.*');
// this does the sql limit/offset needed to get the correct subset of items
            $items = $itemQuery->forPage($curPage, $perPage)->get();

// manually run a query to select the total item count
// use addSelect instead of select to append
            $totalResult = $query->addSelect(DB::raw('count(distinct '.$query->getModel()->getTable().'.'.$query->getModel()->getKeyName().') as count'))->get();
            $totalItems = isset($totalResult->first()->count) ? $totalResult->first()->count : 0;

// make the paginator, which is the same as returned from paginate()
// all() will return an array of models from the collection.
            return new LengthAwarePaginator($items->all(), $totalItems, $perPage);
        }
    }

}