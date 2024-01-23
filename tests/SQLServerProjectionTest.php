<?php

use Workbench\BaseTest\BaseProjectionTest;

class SQLServerProjectionTest extends BaseProjectionTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlsrv';
    }
}
