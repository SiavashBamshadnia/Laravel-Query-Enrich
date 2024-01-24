<?php

namespace sbamtr\LaravelQueryEnrich\String;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Extracts a number of characters from a string (starting from right).
 */
class Right extends DBFunction
{
    private mixed $string;
    private mixed $numberOfChars;

    public function __construct(mixed $string, mixed $numberOfChars)
    {
        $this->string = $string;
        $this->numberOfChars = $numberOfChars;
    }

    protected function getQuery(): string
    {
        if ($this->getDatabaseEngine() == EDatabaseEngine::SQLite) {
            return $this->getFunctionCallSql('rightstr', [$this->string, $this->numberOfChars]);
        }

        return $this->getFunctionCallSql('right', [$this->string, $this->numberOfChars]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('rightstr', function ($string, $numberOfChars) {
            return substr($string, -1 * $numberOfChars);
        }, 2);
    }
}
