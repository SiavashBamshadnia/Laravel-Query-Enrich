<?php

namespace sbamtr\LaravelQueryEnrich;

/**
 * Create a reference to a database column.
 *
 * @param string $name The name of the database column.
 * @return Column A Column instance representing the specified column.
 */
function c(string $name): Column
{
    return new Column($name);
}
