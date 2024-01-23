<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Returns the number of days between two date values.
 */
class DateDiff extends DBFunction
{
    private mixed $date1;
    private mixed $date2;

    public function __construct(mixed $date1, mixed $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return $this->getFunctionCallSql('datediff', [$this->date1, $this->date2]);
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('datediff', [QE::raw('day'), $this->date2, $this->date1]);
            case EDatabaseEngine::PostgreSQL:
                $date1 = $this->escape($this->date1);
                $date2 = $this->escape($this->date2);

                return "DATE_PART('day', $date1::timestamp - $date2::timestamp)";
            case EDatabaseEngine::SQLite:
                $date1 = $this->escape($this->date1);
                $date2 = $this->escape($this->date2);

                return "julianday($date1) - julianday($date2)";
        }
    }
}
