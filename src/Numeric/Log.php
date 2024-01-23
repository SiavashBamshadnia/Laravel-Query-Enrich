<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the logarithm of a number.
 */
class Log extends DBFunction
{

    private mixed $parameter;
    private mixed $base;

    public function __construct(mixed $parameter, mixed $base = 2)
    {
        $this->parameter = $parameter;
        $this->base = $base;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('log', [$this->base, $this->parameter]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('log', [$this->parameter, $this->base]);
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('log', function ($base, $parameter) {
            return log($parameter, $base);
        }, 2);
    }
}
