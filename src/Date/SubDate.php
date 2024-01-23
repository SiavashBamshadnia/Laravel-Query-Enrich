<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Subtracts a time/date interval from a date and then returns the date.
 */
class SubDate extends DBFunction
{
    private mixed $subject;
    private mixed $value;
    private Unit $interval;

    public function __construct(mixed $subject, mixed $value, Unit $interval = Unit::DAY)
    {
        $this->subject = $subject;
        $this->value = $value;
        $this->interval = $interval;
    }

    protected function getQuery(): string
    {
        $subject = $this->subject;
        $value = $this->value;
        $interval = $this->interval;

        if ($this->getDatabaseEngine() == EDatabaseEngine::SQLite
            ||
            $this->getDatabaseEngine() == EDatabaseEngine::PostgreSQL
        ) {
            if ($interval == Unit::WEEK) {
                $interval = Unit::DAY;
                $value *= 7;
            } elseif ($interval == Unit::QUARTER) {
                $interval = Unit::MONTH;
                $value *= 3;
            }
        }

        $subject = $this->escape($subject);
        $value = $this->escape($value);
        $interval = $this->escape($interval);

        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return "subdate($subject, INTERVAL $value $interval)";
            case EDatabaseEngine::PostgreSQL:
                return "($subject - INTERVAL '$value $interval')";
            case EDatabaseEngine::SQLite:
            case EDatabaseEngine::SQLServer:
                return QE::addDate($this->subject, -1 * $this->value, $this->interval);
        }
    }
}
