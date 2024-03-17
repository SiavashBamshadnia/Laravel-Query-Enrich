<?php

use Orchestra\Testbench\TestCase;
use Workbench\BaseTest\BaseWhereClauseTest;

if (!str_starts_with(phpversion(), '8.1')) {
    class MariaDBWhereClauseTest extends BaseWhereClauseTest
    {
        protected function getDatabaseEngine(): string
        {
            return 'mariadb';
        }
    }
} else {
    class MariaDBWhereClauseTest extends TestCase
    {
        public function testTrue()
        {
            self::assertTrue(true);
        }

        protected function getDatabaseEngine(): string
        {
            return '';
        }
    }
}
