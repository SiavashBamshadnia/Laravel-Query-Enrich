<?php

namespace Workbench\BaseTest;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;

use function Orchestra\Testbench\workbench_path;

abstract class BaseTest extends TestCase
{
    use WithFaker;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->setUpFaker();
    }

    protected function getPackageProviders($app)
    {
        return ['sbamtr\\LaravelQueryEnrich\\QueryEnrichServiceProvider'];
    }

    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], function (Repository $config) {
            $config->set('database.connections.sqlite', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'version'  => '3.43.0',
            ]);
            $config->set('database.connections.mysql', [
                'driver'   => 'mysql',
                'host'     => '127.0.0.1',
                'database' => 'test',
                'username' => 'root',
                'password' => 'mysql',
            ]);
            $config->set('database.connections.mariadb', [
                'driver'   => 'mariadb',
                'host'     => '127.0.0.1',
                'database' => 'test',
                'username' => 'root',
                'password' => 'mysql',
            ]);
            $config->set('database.connections.pgsql', [
                'driver'   => 'pgsql',
                'host'     => '127.0.0.1',
                'database' => 'test',
                'username' => 'postgres',
                'password' => 'my_password',
            ]);
            $config->set('database.connections.sqlsrv', [
                'driver'   => 'sqlsrv',
                'host'     => '127.0.0.1',
                'database' => 'tempdb',
                'username' => 'sa',
                'password' => 'yourStrong(!)Password',
            ]);

            $config->set('database.default', $this->getDatabaseEngine());
        });
    }

    abstract protected function getDatabaseEngine(): string;

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('books')->delete();
        DB::table('authors')->delete();
    }
}
