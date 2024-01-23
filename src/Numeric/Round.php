<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Rounds a number to a specified number of decimal places.
 */
class Round extends DBFunction
{
    private mixed $parameter;
    private mixed $decimals;

    public function __construct(mixed $parameter, mixed $decimals = 0)
    {
        $this->parameter = $parameter;
        $this->decimals = $decimals;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('round', [$this->parameter, $this->decimals]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('round', [QE::multiply(1.0, $this->parameter), $this->decimals]);
        }
    }
}
