<?php


use Workbench\BaseTest\BaseProjectionTest;

class MysqlProjectionTest extends BaseProjectionTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mysql';
    }
}
