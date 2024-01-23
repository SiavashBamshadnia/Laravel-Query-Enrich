<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the arc tangent of two numbers.
 */
class Atan2 extends DBFunction
{
    private $y;
    private $x;

    public function __construct($y, $x)
    {
        $this->y = $y;
        $this->x = $x;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('atan2', [$this->y, $this->x]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('atn2', [$this->y, $this->x]);
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('atan2', 'atan2', 2);
    }
}
