<?php

use Workbench\BaseTest\BaseWhereClauseTest;

class MysqlWhereClauseTest extends BaseWhereClauseTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mysql';
    }
}
