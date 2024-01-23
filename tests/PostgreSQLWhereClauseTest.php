<?php

use Workbench\BaseTest\BaseWhereClauseTest;

class PostgreSQLWhereClauseTest extends BaseWhereClauseTest
{
    protected function getDatabaseEngine(): string
    {
        return 'pgsql';
    }
}
