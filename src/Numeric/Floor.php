<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the largest integer value that is <= to a number.
 */
class Floor extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('floor', [$this->parameter]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('floor', 'floor', 1);
    }
}
