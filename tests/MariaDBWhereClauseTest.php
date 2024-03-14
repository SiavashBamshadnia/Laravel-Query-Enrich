<?php

use Workbench\BaseTest\BaseWhereClauseTest;

class MariaDBWhereClauseTest extends BaseWhereClauseTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mariadb';
    }
}
