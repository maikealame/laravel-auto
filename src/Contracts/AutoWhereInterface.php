<?php
namespace AutoWhere\Contracts;

interface AutoWhereInterface
{
    /**
     * Returns the auto where class for chain usage
     *
     * @return mixed
     */
    public function where();
}