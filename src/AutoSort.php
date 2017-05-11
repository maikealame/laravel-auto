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
    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param array|null                         $defaultSortParameters
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAutoSort($query, $defaultSortParameters = null)
    {
        if (Request::has('sort') && Request::has('order')) {
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
        $column = array_get($sortParameters, 'sort');
        if (empty($column)) {
            return [null, null];
        }
        $direction = array_get($sortParameters, 'order', []);
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
        reset($sort);
        $each = each($sort);
        return ($each[0] === 0) ? ['sort' => $each[1], 'order' => $configDefaultOrder] : [
            'sort'  => $each[0],
            'order' => $each[1]
        ];
    }
}