<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the seconds part of a time/datetime.
 */
class Second extends DBFunction
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
                return $this->getFunctionCallSql('second', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
                $parameter = $this->escape($this->parameter);
                return "extract(second from $parameter)";
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('strftime', [QE::raw("'%S'"), $this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('datepart', [QE::raw('second'), $this->parameter]);
        }
    }
}
