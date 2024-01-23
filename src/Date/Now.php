<?php

namespace sbamtr\LaravelQueryEnrich\Date;

use sbamtr\LaravelQueryEnrich\DBFunction;
use sbamtr\LaravelQueryEnrich\EDatabaseEngine;

/**
 * Returns the current date and time.
 */
class Now extends DBFunction
{
    protected function getQuery(): string
    {
        switch ($this->getDatabaseEngine()) {
            case EDatabaseEngine::MySQL:
                return $this->getFunctionCallSql('now');
            case EDatabaseEngine::SQLServer:
                return $this->getFunctionCallSql('getdate');
            case EDatabaseEngine::SQLite:
                return $this->getFunctionCallSql('datetime');
            case EDatabaseEngine::PostgreSQL:
                return 'localtimestamp::timestamp(0)';
        }
    }
}
