<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the week number for a given date/datetime.
 */
class Year extends DBFunction
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
                return $this->getFunctionCallSql('year', [$this->parameter]);
            case EDatabaseEngine::PostgreSQL:
                $parameter = $this->escape($this->parameter);
                return "extract(year from $parameter)";
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('strftime', [QE::raw("'%Y'"), $this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('datepart', [QE::raw('year'), $this->parameter]);
        }
    }
}
