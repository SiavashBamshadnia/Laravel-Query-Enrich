<?php

namespace sbamtr\LaravelQueryEnrich\Condition;

use sbamtr\LaravelQueryEnrich\Advanced\IsNull;
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
     * @param non-empty-array $conditions An array of conditions.
     *
     * @throws InvalidArgumentException If the input is invalid.
     *
     * @return Condition|ConditionChain|Raw Depending on the input, this method returns an instance of Condition, ConditionChain, or Raw.
     */
    public static function parse(array $conditions): Condition|ConditionChain|IsNull|Raw
    {
        $count = count($conditions);
        $condition = reset($conditions);

        if ($count === 1 && $condition instanceof Raw || $condition instanceof IsNull) {
            return $condition;
        }

        if ($condition instanceof Condition) {
            return new ConditionChain($conditions);
        }

        if ($count === 2 || $count === 3) {
            return new Condition(...$conditions);
        }

        throw new InvalidArgumentException('Invalid condition');
    }
}
