<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the number of records returned by a select query.
 */
class Count extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter = '*')
    {
        if ($parameter === '*') {
            $this->parameter = QE::raw('*');
        } else {
            $this->parameter = $parameter;
        }
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('count', [$this->parameter]);
    }
}
