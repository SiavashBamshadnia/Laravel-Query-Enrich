<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the square root of a number.
 */
class Sqrt extends DBFunction
{
    private $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('sqrt', [$this->parameter]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('sqrt', 'sqrt', 1);
    }
}
