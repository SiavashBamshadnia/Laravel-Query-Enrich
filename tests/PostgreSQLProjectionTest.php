<?php

use Workbench\BaseTest\BaseProjectionTest;

class PostgreSQLProjectionTest extends BaseProjectionTest
{
    protected function getDatabaseEngine(): string
    {
        return 'pgsql';
    }
}
