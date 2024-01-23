<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use Illuminate\Support\Facades\DB;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the arc tangent of a number.
 */
class Atan extends DBFunction
{
    private $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    protected function getQuery(): string
    {
        return $this->getFunctionCallSql('atan', [$this->parameter]);
    }

    public function configureForSqlite(): void
    {
        DB::connection()->getPdo()->sqliteCreateFunction('atan', 'atan', 1);
    }
}
