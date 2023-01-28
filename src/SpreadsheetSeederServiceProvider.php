<?php

namespace bfinlay\SpreadsheetSeeder;

use bfinlay\SpreadsheetSeeder\Console\SeedCommand;
use bfinlay\SpreadsheetSeeder\Support\StrMacros;
use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SpreadsheetSeederServiceProvider extends ServiceProvider
{
    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
    protected $defer = false;

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        SpreadsheetSeederSettings::class => SpreadsheetSeederSettings::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SeedCommand::class, function ($app) {
            return new SeedCommand($app['db']);
        });

        if (!method_exists(Str::class, "beforeLast")) StrMacros::registerBeforeLastMacro();
        if (!method_exists(Str::class, "between")) StrMacros::registerBetweenMacro();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindGrammarClasses();
        $this->commands([
            SeedCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    protected function bindGrammarClasses()
    {
        $connections = [
            'mysql' => [
                'connection' => MySqlConnection::class,
                'schemaGrammar' => MySqlGrammar::class,
            ],
        ];

        foreach($connections as $driver => $class) {
            Connection::resolverFor($driver, function($pdo, $database = '', $tablePrefix = '', array $config = []) use ($driver, $class) {
                $connection = new $class['connection']($pdo, $database, $tablePrefix, $config);
                $connection->setSchemaGrammar(new $class['schemaGrammar']);

                return $connection;
            });
        }
    }
}
