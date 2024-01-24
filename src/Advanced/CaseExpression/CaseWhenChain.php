<?php

namespace sbamtr\LaravelQueryEnrich\Advanced\CaseExpression;

use Illuminate\Support\Arr;
use sbamtr\LaravelQueryEnrich\Condition\ConditionParser;
use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * This class allows chaining of WHEN...THEN clauses and an optional ELSE clause
 * to create complex conditional logic within SQL queries.
 */
class CaseWhenChain extends DBFunction
{
    /**
     * The internal CASE expression being built.
     *
     * @var _Case
     */
    private _Case $case;

    /**
     * Constructor for the CaseWhenChain class.
     *
     * Initializes a new internal CASE expression.
     */
    public function __construct()
    {
        $this->case = new _Case();
    }

    /**
     * Add a WHEN clause to the CASE expression.
     *
     * @param mixed ...$condition The condition for the WHEN clause.
     *
     * @return CaseWhenChain Returns the current instance for method chaining.
     */
    public function when(mixed ...$condition): CaseWhenChain
    {
        $condition = ConditionParser::parse($condition);
        $when = new When($condition, null);

        $this->case->whens[] = $when;

        return $this;
    }

    /**
     * Add a THEN result to the last WHEN clause of the CASE expression.
     *
     * @param mixed $result The result for the THEN clause.
     *
     * @return CaseWhenChain Returns the current instance for method chaining.
     */
    public function then(mixed $result): CaseWhenChain
    {
        $lastWhen = Arr::last($this->case->whens);
        $lastWhen->then = $result;

        return $this;
    }

    /**
     * Add an ELSE result to the CASE expression.
     *
     * @param mixed $result The result for the ELSE clause.
     *
     * @return CaseWhenChain Returns the current instance for method chaining.
     */
    public function else(mixed $result): CaseWhenChain
    {
        $this->case->else = $result;

        return $this;
    }

    /**
     * Convert the CASE expression to a SQL string.
     *
     * @return string The SQL representation of the CASE expression.
     */
    protected function getQuery(): string
    {
        return $this->case->getQuery();
    }
}
