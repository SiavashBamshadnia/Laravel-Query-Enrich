<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the number of records returned by a select query.
 */
class Count extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('count', [$this->parameter]);
    }
}
