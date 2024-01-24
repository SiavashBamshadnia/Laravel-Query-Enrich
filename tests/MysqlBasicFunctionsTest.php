<?php

use Workbench\BaseTest\BaseBasicFunctionsTest;

class MysqlBasicFunctionsTest extends BaseBasicFunctionsTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mysql';
    }
}
