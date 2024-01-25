<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Adds a specific value to a date/datetime.
 */
class AddDate extends DBFunction
{
    private mixed $subject;
    private mixed $_value;
    private Unit $interval;

    public function __construct(mixed $subject, mixed $value, Unit $interval = Unit::DAY)
    {
        $this->subject = $subject;
        $this->_value = $value;
        $this->interval = $interval;
    }

    protected function getQuery(): string
    {
        $subject = $this->subject;
        $value = $this->_value;
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
                return "adddate($subject, INTERVAL $value $interval)";
            case EDatabaseEngine::PostgreSQL:
                return "($subject + INTERVAL '$value $interval')";
            case EDatabaseEngine::SQLite:
                return "datetime($subject,'$value $interval')";
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql(
                    'convert',
                    [
                        QE::raw($this->getFunctionCallSql('datetime2', [0])),
                        QE::raw($this->getFunctionCallSql('dateadd', [$this->interval, $this->_value, $this->subject])),
                    ]
                );
        }
    }
}
