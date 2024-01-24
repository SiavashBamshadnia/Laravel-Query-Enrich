<?php

namespace sbamtr\LaravelQueryEnrich\Condition;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;

/**
 * Represents a condition for use in queries.
 * This class helps in building complex query conditions using logical operators.
 */
class Condition extends DBFunction
{
    protected const IS_BOOLEAN = true;

    /**
     * Valid operators that can be used in the condition.
     *
     * @var array
     */
    private const VALID_OPERATORS = ['=', '<>', '!=', 'like', 'not like', '<', '>', '<=', '>=', 'is', 'in', 'not in'];

    /**
     * Operators that accept arrays as parameters.
     *
     * @var array
     */
    private const ARRAY_OPERATORS = ['in', 'not in'];

    /**
     * First parameter of the condition.
     *
     * @var mixed
     */
    private mixed $parameter1;

    /**
     * Operator of the condition.
     *
     * @var string
     */
    private string $operator;

    /**
     * Second parameter of the condition.
     *
     * @var mixed
     */
    private mixed $parameter2;

    /**
     * Constructs a new condition.
     *
     * @param mixed  $parameter1 The first parameter of the condition.
     * @param string $operator   The operator of the condition. Default is '='.
     * @param mixed  $parameter2 The second parameter of the condition.
     *
     * @throws InvalidArgumentException If the operator is not valid.
     */
    public function __construct(mixed $parameter1, mixed $operator = null, mixed $parameter2 = null)
    {
        if (func_num_args() === 2) {
            $parameter2 = $operator;
            $operator = '=';
        }

        if (!$this->isOperatorValid($operator)) {
            throw new InvalidArgumentException('The operator '.$operator.' is not supported');
        }

        $this->parameter1 = $parameter1;
        $this->operator = $operator;
        $this->parameter2 = $parameter2;
    }

    protected function getQuery(): string
    {
        $parameter1 = $this->escape($this->parameter1);
        $parameter2 = $this->escape($this->parameter2);

        $operator = $this->operator;
        $operator = strtolower($operator);

        if (in_array($operator, self::ARRAY_OPERATORS)) {
            $parameter2 = '('.implode(',', $parameter2).')';

            return "$parameter1 $operator $parameter2";
        }

        return "$parameter1 $operator $parameter2";
    }

    /**
     * Checks if the operator is valid.
     *
     * @param string $operator The operator to check.
     *
     * @return bool True if the operator is valid, false otherwise.
     */
    private function isOperatorValid(string $operator): bool
    {
        return in_array($operator, self::VALID_OPERATORS);
    }
}
