<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the value of a number raised to the power of another number.
 */
class Pow extends DBFunction
{
    private mixed $x;
    private mixed $y;

    public function __construct(mixed $x, mixed $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('pow', [$this->x, $this->y]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('power', [$this->x, $this->y]);
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('pow', 'pow', 2);
    }
}
