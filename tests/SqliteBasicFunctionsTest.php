<?php

use Workbench\BaseTest\BaseBasicFunctionsTest;

class SqliteBasicFunctionsTest extends BaseBasicFunctionsTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlite';
    }
}
