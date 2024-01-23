<?php

namespace sbamtr\LaravelQueryEnrich\String;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns a string consisting of a specified number of spaces.
 */
class Space extends DBFunction
{
    private mixed $number;

    public function __construct(mixed $number)
    {
        $this->number = $number;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::SQLite:
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('space', [$this->number]);
            case EDatabaseEngine::PostgreSQL:
                return QE::repeat(' ', $this->number);
        }
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('space', function ($number) {
            return Str::repeat(' ', $number);
        }, 1);
    }
}
