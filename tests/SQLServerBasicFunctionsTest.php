<?php

use Workbench\BaseTest\BaseBasicFunctionsTest;

class SQLServerBasicFunctionsTest extends BaseBasicFunctionsTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlsrv';
    }
}
