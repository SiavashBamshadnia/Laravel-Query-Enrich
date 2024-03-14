<?php

use Workbench\BaseTest\BaseBasicFunctionsTest;

class MariaDBlBasicFunctionsTest extends BaseBasicFunctionsTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mariadb';
    }
}
