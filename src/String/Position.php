<?php

namespace sbamtr\LaravelQueryEnrich\String;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Finds the position of a substring within a string.
 */
class Position extends DBFunction
{
    private mixed $subString;
    private mixed $string;

    public function __construct(mixed $subString, mixed $string)
    {
        $this->subString = $subString;
        $this->string = $string;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('instr', [$this->string, $this->subString]);
            case EDatabaseEngine::MySQL:
            case EDatabaseEngine::PostgreSQL:
                $subString = $this->escape($this->subString);
                $string = $this->escape($this->string);

                return "POSITION($subString IN $string)";
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('charindex', [$this->subString, $this->string]);
        }
    }
}
