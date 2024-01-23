<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the minimum value in a set of values.
 */
class Min extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('min', [$this->parameter]);
    }
}
