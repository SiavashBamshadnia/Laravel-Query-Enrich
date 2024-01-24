<?php

namespace sbamtr\LaravelQueryEnrich\Advanced;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Implements an IF condition to introduce branching.
 */
class _If extends DBFunction
{
    private DBFunction $condition;
    private mixed $valueIfTrue;

    private mixed $valueIfFalse;

    public function __construct(DBFunction $condition, mixed $valueIfTrue, mixed $valueIfFalse)
    {
        $this->condition = $condition;
        $this->valueIfTrue = $valueIfTrue;
        $this->valueIfFalse = $valueIfFalse;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('iif', [$this->condition, $this->valueIfTrue, $this->valueIfFalse]);
            case EDatabaseEngine::MySQL:
                return $this->getFunctionCallSql('if', [$this->condition, $this->valueIfTrue, $this->valueIfFalse]);
            case EDatabaseEngine::PostgreSQL:
            case EDatabaseEngine::SQLite:
                return QE::case()->when($this->condition)->then($this->valueIfTrue)->else($this->valueIfFalse);
        }
    }
}
