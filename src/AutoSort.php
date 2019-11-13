<?php
namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Auto\Exceptions\AutoSortException;
/**
 * Sortable trait.
 */
trait AutoSort
{
    private $defaultSortParameters = [];
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array|null                         $defaultSortParameters
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoSort($query, $defaultSortParameters = [])
    {
        $this->defaultSortParameters = $defaultSortParameters;

        if (Request::has('sort') && Request::get('order') !== null) {
            return $this->queryOrderBuilder($query, Request::only(['sort', 'order']));
        } elseif ( ! is_null($defaultSortParameters)) {
            $defaultSortArray = $this->formatToSortParameters($defaultSortParameters);
            if (Config::get('laravelauto.sort.allow_request_modification', true) && ! empty($defaultSortArray)) {
                Request::merge($defaultSortArray);
            }
            return $this->queryOrderBuilder($query, $defaultSortArray);
        } else {
            return $query;
        }
    }
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array                              $sortParameters
     *
     * @return \Illuminate\Database\Query\Builder
     *
     * @throws AutoSortException
     */
    private function queryOrderBuilder($query, array $sortParameters)
    {
        $model = $this;
        list($column, $direction) = $this->parseSortParameters($sortParameters);
        if (is_null($column)) {
            return $query;
        }
        if(!empty($direction))
            $query->orderBy($column, $direction);
        return $query;
    }
    /**
     * @param array $sortParameters
     *
     * @return array
     */
    private function parseSortParameters(array $sortParameters)
    {
        $column = $sortParameters['sort'] ?: [] ;
        if (empty($column)) {
            return [null, null];
        }
        $direction = $sortParameters['order'] ?: [];
        if ( ! in_array($direction, ['asc', 'desc'])) {
            $direction = Config::get('laravelauto.sort.default_direction', 'asc');
        }
        return [$column, $direction];
    }

    /**
     * @param array|string $sort
     *
     * @return array
     */
    private function formatToSortParameters($sort)
    {
        if (empty($sort)) {
            return [];
        }
        $configDefaultOrder = Config::get('laravelauto.sort.default_direction', 'asc');
        if (is_string($sort)) {
            return ['sort' => $sort, 'order' => $configDefaultOrder];
        }

        return isset($sort[1]) ? [
            'sort'  => $sort[0],
            'order' => $sort[1]
        ] : ['sort' => $sort[0], 'order' => $configDefaultOrder] ;
    }
}
