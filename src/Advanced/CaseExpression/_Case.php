<?php

namespace sbamtr\LaravelQueryEnrich\Advanced\CaseExpression;

use sbamtr\LaravelQueryEnrich\DBFunction;

/**
 * This class allows for the construction of a CASE expression in SQL, which is used to create conditional statements
 * within an SQL query. It can define multiple WHEN conditions and an ELSE result.
 */
class _Case extends DBFunction
{
    /**
     * An array of WHEN clauses.
     *
     * @var When[]
     */
    public array $whens;

    /**
     * The result to return if no WHEN condition is matched.
     *
     * @var mixed
     */
    public mixed $else;

    /**
     * Constructor for the CASE expression class.
     *
     * @param array $whens An array of WHEN conditions for the CASE expression.
     * @param mixed $else The result to return if no WHEN condition is matched (optional).
     */
    public function __construct(array $whens = [], mixed $else = null)
    {
        $this->whens = $whens;
        $this->else = $else;
    }

    /**
     * Convert the CASE expression to a SQL string.
     *
     * This method constructs the SQL string for the CASE expression by iterating over the WHEN clauses and
     * optionally adding an ELSE clause.
     *
     * @return string The SQL representation of the CASE expression.
     */
    protected function getQuery(): string
    {
        $whens = $this->whens;
        $sql = "case ";
        foreach ($whens as $when) {
            $when = $this->escape($when);
            $sql .= $when . ' ';
        }
        if (isset($this->else)) {
            $sql .= 'else ' . $this->escape($this->else) . ' ';
        }
        $sql .= 'end';
        return $sql;
    }
}
