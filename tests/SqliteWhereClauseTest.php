<?php

use Workbench\BaseTest\BaseWhereClauseTest;

class SqliteWhereClauseTest extends BaseWhereClauseTest
{
    protected function getDatabaseEngine(): string
    {
        return 'sqlite';
    }
}
