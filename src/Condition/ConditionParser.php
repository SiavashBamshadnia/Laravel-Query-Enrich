<?php

namespace sbamtr\LaravelQueryEnrich\Condition;

use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;
use sbamtr\LaravelQueryEnrich\Raw;

/**
 * Responsible for parsing conditions for use in queries.
 * This class takes an array of conditions and returns an instance of Condition, ConditionChain, or Raw depending on the input.
 */
class ConditionParser
{
    /**
     * Parses an array of conditions.
     *
     * @param array $conditions An array of conditions.
     * @return Condition|ConditionChain|Raw Depending on the input, this method returns an instance of Condition, ConditionChain, or Raw.
     * @throws InvalidArgumentException If the input is invalid.
     */
    public static function parse(array $conditions): Condition|ConditionChain|Raw
    {
        if ($conditions[0] instanceof Raw and count($conditions) === 1) {
            return $conditions[0];
        }
        if ($conditions[0] instanceof Condition) {
            return new ConditionChain($conditions);
        }
        if (count($conditions) === 2 || count($conditions) === 3) {
            return new Condition(...$conditions);
        }
        throw new InvalidArgumentException('Invalid condition');
    }
}
