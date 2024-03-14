<?php

use Workbench\BaseTest\BaseProjectionTest;

class MariaDBProjectionTest extends BaseProjectionTest
{
    protected function getDatabaseEngine(): string
    {
        return 'mariadb';
    }
}
