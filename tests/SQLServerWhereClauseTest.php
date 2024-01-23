<?php

use Workbench\BaseTest\BaseWhereClauseTest;

class SQLServerWhereClauseTest extends BaseWhereClauseTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlsrv';
    }
}
