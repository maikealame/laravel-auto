<?php
namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Auto\Exceptions\AutoPaginateException;
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
        return $query->paginate( Request::has("length") ? Request::get("length") : $length );
    }

}