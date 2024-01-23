<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;
use sbamtr\LaravelQueryEnrich\QE;

/**
 * Retrieves the current date.
 */
class CurrentDate extends DBFunction
{
    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return $this->getFunctionCallSql('current_date');
            case EDatabaseEngine::PostgreSQL:
                return 'current_date';
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('date');
            case EDatabaseEngine::SQLServer:
                return QE::date(
                    QE::raw($this->getFunctionCallSql('getdate'))
                )->getQuery();
        }
    }
}
