<?php

namespace sbamtr\LaravelQueryEnrich;

use Illuminate\Support\Str;
use sbamtr\LaravelQueryEnrich\Exception\InvalidArgumentException;

/**
 * Represents a raw SQL expression.
 *
 * @extends DBFunction
 */
class Raw extends DBFunction
{
    /**
     * @var string The raw SQL string.
     */
    private string $sql;

    /**
     * @var array The array of parameter bindings.
     */
    private array $bindings;

    /**
     * Raw constructor.
     *
     * @param string $sql      The raw SQL string.
     * @param array  $bindings The array of parameter bindings.
     *
     * @throws InvalidArgumentException If the number of placeholders in the SQL string
     *                                  does not match the number of bindings.
     */
    public function __construct(string $sql, array $bindings = [])
    {
        // Validate the number of placeholders and bindings
        $questionMarksCount = Str::substrCount($sql, '?');
        $bindingsCount = count($bindings);

        if ($questionMarksCount !== $bindingsCount) {
            throw new InvalidArgumentException('Invalid parameter number');
        }

        $this->sql = $sql;
        $this->bindings = $bindings;
    }

    /**
     * Get the raw SQL string with bindings applied.
     *
     * @return string The raw SQL string.
     */
    protected function getQuery(): string
    {
        $sql = $this->sql;
        $bindings = $this->bindings;

        // Replace placeholders with escaped bindings
        foreach ($bindings as $binding) {
            $binding = $this->escape($binding);
            $sql = Str::replaceFirst('?', $binding, $sql);
        }

        return $sql;
    }
}
