<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Multiply multiple numeric parameters.
 */
class Multiply extends DBFunction
{
    private array $parameters;

    public function __construct(...$parameters)
    {
        $this->parameters = $parameters;
    }

    protected function getQuery(): string
    {
        return $this->getOperatorSeparatedSql('*', $this->parameters);
    }
}
