<?php

namespace sbamtr\LaravelQueryEnrich\String;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Extracts a substring from a string starting at a specified position with optional length.
 */
class Substr extends DBFunction
{
    private mixed $string;
    private mixed $start;
    private mixed $length;

    public function __construct(mixed $string, mixed $start, mixed $length = null)
    {
        $this->string = $string;
        $this->start = $start;
        $this->length = $length;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                if ($this->length === null) {
                    return $this->getFunctionCallSql('substr', [$this->string, $this->start]);
                }

                return $this->getFunctionCallSql('substr', [$this->string, $this->start, $this->length]);
            case EDatabaseEngine::SQLServer:
                if ($this->length === null) {
                    return $this->getFunctionCallSql('stuff', [$this->string, 1, QE::subtract($this->start, 1), '']);
                }

                return $this->getFunctionCallSql('substring', [$this->string, $this->start, $this->length]);
        }
    }
}
