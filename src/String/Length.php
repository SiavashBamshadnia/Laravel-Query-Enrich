<?php

namespace sbamtr\LaravelQueryEnrich\String;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the length of a string.
 */
class Length extends DBFunction
{
    private mixed $string;

    public function __construct(mixed $string)
    {
        $this->string = $string;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('length', [$this->string]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('len', [$this->string]);
        }
    }
}
