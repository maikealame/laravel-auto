<?php
namespace maikealame\AutoWhere\Contracts;

use maikealame\AutoWhere\Auto as phpAuto;

interface AutoWhereInterface
{
    /**
     * Returns the auto where class for chain usage
     *
     * @return phpAuto
     */
    public function where();
}