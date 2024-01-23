<?php

namespace sbamtr\LaravelQueryEnrich\Numeric;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Returns the remainder of a number divided by another number.
 */
class Mod extends DBFunction
{
    private mixed $x;
    private mixed $y;

    public function __construct(mixed $x, mixed $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    protected function getQuery(): string
    {
        return $this->getOperatorSeparatedSql('%', [$this->x, $this->y]);
    }
}
