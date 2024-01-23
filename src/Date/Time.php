<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Extracts the time part from a given time/datetime.
 */
class Time extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::SQLite:
            return $this->getFunctionCallSql('time', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
                $parameter = $this->escape($this->parameter);
                return "$parameter::time";
            case EDatabaseEngine::SQLServer:
                $parameter = $this->escape($this->parameter);
                return "cast($parameter as time(0))";
        }
    }
}
