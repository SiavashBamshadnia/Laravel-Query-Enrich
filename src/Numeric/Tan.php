<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the tangent of a number.
 */
class Tan extends DBFunction
{
    private mixed $parameter;

    public function __construct(mixed $parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('tan', [$this->parameter]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('tan', 'tan', 1);
    }
}
