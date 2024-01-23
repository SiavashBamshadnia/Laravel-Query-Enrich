<?php

use Workbench\BaseTest\BaseBasicFunctionsTest;

class PostgreSQLBasicFunctionsTest extends BaseBasicFunctionsTest
{
    protected function getDatabaseEngine(): string
    {
        return 'pgsql';
    }
}
