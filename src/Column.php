<?php

namespace sbamtr\LaravelQueryEnrich;
/**
 * Retrieves a reference to a specific database column.
 */
class Column extends DBFunction
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    protected function getQuery(): string
    {
        $firstSurroundingCharacter = $this->getFirstSurroundingCharacter();
        $lastSurroundingCharacter = $this->getLastSurroundingCharacter();
        return $firstSurroundingCharacter . str_replace(
                '.',
                "$lastSurroundingCharacter.$firstSurroundingCharacter",
                $this->name
            ) . $lastSurroundingCharacter;
    }
}
