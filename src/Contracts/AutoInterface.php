<?php
namespace Auto\Contracts;

interface AutoInterface
{
    /**
     * Returns the auto where class for chain usage
     *
     * @return mixed
     */
    public function where();
}