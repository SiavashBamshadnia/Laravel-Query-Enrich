<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the natural logarithm of a number.
 */
class Ln extends DBFunction
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
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('ln', [$this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('log', [$this->parameter]);
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('ln', 'log', 1);
    }
}
