<?php

use Workbench\BaseTest\BaseProjectionTest;

class SqliteProjectionTest extends BaseProjectionTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlite';
    }
}
