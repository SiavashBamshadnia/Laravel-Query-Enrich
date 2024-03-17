<?php

use Orchestra\Testbench\TestCase;
use Workbench\BaseTest\BaseBasicFunctionsTest;

if (!str_starts_with(phpversion(), '8.1')) {
	class MariaDBlBasicFunctionsTest extends BaseBasicFunctionsTest
	{
		protected function getDatabaseEngine(): string
		{
			return 'mariadb';
		}
	}
} else {
	class MariaDBlBasicFunctionsTest extends TestCase
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