<?php

namespace sbamtr\LaravelQueryEnrich\String;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Adds two or more expressions together.
 */
class Concat extends DBFunction
{
    private array $parameters;

    public function __construct(...$parameters)
    {
        $this->parameters = $parameters;
    }

    protected function getQuery(): string
    {
        if ($this->getDatabaseEngine() == EDatabaseEngine::SQLite) {
            return $this->getOperatorSeparatedSql('||', $this->parameters);
        }

        return $this->getFunctionCallSql('concat', $this->parameters);
    }
}
