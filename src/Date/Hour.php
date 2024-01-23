<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the hour part for a given time/datetime.
 */
class Hour extends DBFunction
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
                return $this->getFunctionCallSql('hour', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
                $parameter = $this->escape($this->parameter);
                return "extract(hour from $parameter)";
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('strftime', [QE::raw("'%H'"), $this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('datepart', [QE::raw('hour'), $this->parameter]);
        }
    }
}
