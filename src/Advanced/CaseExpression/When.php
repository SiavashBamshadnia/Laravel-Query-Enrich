<?php

namespace sbamtr\LaravelQueryEnrich\Advanced\CaseExpression;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * Represents a WHEN clause within a SQL CASE expression.
 *
 * This class encapsulates the logic for a single WHEN...THEN pair in a CASE statement,
 * allowing for construction of complex conditional logic in SQL queries.
 */
class When extends DBFunction
{
    /**
     * The condition for this WHEN clause.
     *
     * @var mixed
     */
    public mixed $condition;

    /**
     * The result to return if the condition is met.
     *
     * @var mixed
     */
    public mixed $then;

    /**
     * Constructor for the When class.
     *
     * Initializes a new WHEN clause with the given condition and result.
     *
     * @param mixed $condition The condition for the WHEN clause.
     * @param mixed $then The result for the THEN clause.
     */
    public function __construct(mixed $condition, mixed $then)
    {
        $this->condition = $condition;
        $this->then = $then;
    }

    /**
     * Convert the WHEN clause to a SQL string.
     *
     * Constructs the SQL representation of this WHEN clause, including the condition
     * and the result, properly escaped to prevent SQL injection.
     *
     * @return string The SQL representation of the WHEN clause.
     */
    protected function getQuery(): string
    {
        $condition = $this->escape($this->condition);
        $then = $this->escape($this->then);

        return "when $condition then $then";
    }
}
