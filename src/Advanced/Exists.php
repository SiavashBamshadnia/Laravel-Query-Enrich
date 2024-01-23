<?php

namespace sbamtr\LaravelQueryEnrich\Advanced;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Checks if a subquery returns any results using the EXISTS condition.
 */
class Exists extends DBFunction
{
    protected const IS_BOOLEAN = true;
    private QueryBuilder|EloquentBuilder $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('exists', [$this->query]);
    }
}
