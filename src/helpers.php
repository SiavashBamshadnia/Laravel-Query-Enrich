<?php

namespace sbamtr\LaravelQueryEnrich;

/**
 * Create a reference to a database column.
 *
 * @param string $name The name of the database column.
 *
 * @return Column A Column instance representing the specified column.
 */
function c(string $name): Column
{
    return new Column($name);
}

/**
 * @template TValue
 *
 * @param TValue $value
 *
 * @return ($value is \BackedEnum ? int|string : ($value is \UnitEnum ? string : TValue))
 */
function enum_value($value)
{
    return match (true) {
        $value instanceof \BackedEnum => $value->value,
        $value instanceof \UnitEnum   => $value->name,

        default => $value,
    };
}
