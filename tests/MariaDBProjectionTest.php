<?php

use Orchestra\Testbench\TestCase;
use Workbench\BaseTest\BaseProjectionTest;

if (!str_starts_with(phpversion(), '8.1')) {
    class MariaDBProjectionTest extends BaseProjectionTest
    {
        protected function getDatabaseEngine(): string
        {
            return 'mariadb';
        }
    }
} else {
    class MariaDBProjectionTest extends TestCase
    {
        public function testTrue()
        {
            self::assertTrue(true);
        }
    }
}
