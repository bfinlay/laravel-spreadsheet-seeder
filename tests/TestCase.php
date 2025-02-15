<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function relativeTestsPath($path = null)
    {
        return TestsPath::relative($path);
    }

    public function relativeTestsPathAndClassName()
    {
        $dir = $this->relativeTestsPath(class_basename($this));
        return is_dir(base_path($dir)) ? $dir : $this->relativeTestsPath();
    }

    public function absoluteTestsPath($path = null)
    {
        return TestsPath::absolute($path);
    }

    public function absoluteTestsPathAndClassName()
    {
        $dir = $this->absoluteTestsPath(class_basename($this));
        return is_dir($dir) ? $dir : $this->absoluteTestsPath();
    }


    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        // and other test setup steps you need to perform
    }

    protected function shouldSeed()
    {

    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => env('DB_CONNECTION', 'sqlite'),
            'database' => env('DB_DATABASE', ':memory:'),
            'prefix'   => '',
            'host' =>  env('DB_HOST', '127.0.0.1'),
            'port' =>  env('DB_PORT', ''),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'password'),
            'odbc_driver' => '{ODBC Driver 18 for SQL Server}',
            'trust_server_certificate' => true
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [SpreadsheetSeederServiceProvider::class];
    }
}