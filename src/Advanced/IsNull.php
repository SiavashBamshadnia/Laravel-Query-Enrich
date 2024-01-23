<?php

namespace sbamtr\LaravelQueryEnrich\Advanced;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Checks if a value is NULL.
 */
class IsNull extends DBFunction
{
    protected const IS_BOOLEAN = true;
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return $this->getFunctionCallSql('isnull', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
            case EDatabaseEngine::SQLServer:
                return $this->escape($this->parameter) . " is null";
        }
    }
}
