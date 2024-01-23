<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;

/**
 * Returns a random number.
 */
class Rand extends DBFunction
{
    private mixed $seed;

    public function __construct(mixed $seed = null)
    {
        $this->seed = $seed;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::SQLServer:
                $function = 'rand';
                break;
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                $function = 'random';
                break;
        }
        if ($this->seed === null) {
            return $this->getFunctionCallSql($function);
        }
        if ($this->getDatabaseEngine() === EDatabaseEngine::PostgreSQL) {
            throw new InvalidArgumentException("Random function cannot have seed in postgresql database.");
        }
        return $this->getFunctionCallSql($function, [$this->seed]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('random', function ($seed) {
            srand($seed);
            return rand();
        }, 1);
    }
}
