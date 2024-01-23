<?php

namespace sbamtr\LaravelQueryEnrich\Condition;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\Operator\_And;

/**
 * Represents a chain of conditions for use in queries.
 * This class helps in building complex query conditions using logical operators.
 */
class ConditionChain extends DBFunction
{
    /**
     * Array of conditions that form the chain.
     * @var array
     */
    private array $conditions;

    /**
     * Constructs a new condition chain.
     *
     * @param array $conditions The conditions that form the chain.
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * Converts the condition chain to SQL.
     *
     * @return string The SQL representation of the condition chain.
     */
    protected function getQuery(): string
    {
        $conditions = $this->conditions;
        $and = new _And(...$conditions);
        return $and->getQuery();
    }
}
