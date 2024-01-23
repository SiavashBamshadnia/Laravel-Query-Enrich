<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the month part for a given date/datetime.
 */
class Month extends DBFunction
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
                return $this->getFunctionCallSql('month', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
                $parameter = $this->escape($this->parameter);
                return "extract(month from $parameter)";
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('strftime', [QE::raw("'%m'"), $this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('datepart', [QE::raw('month'), $this->parameter]);
        }
    }
}
