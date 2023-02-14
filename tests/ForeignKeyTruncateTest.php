<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use AngelSourceLabs\LaravelExpressionGrammar\ExpressionGrammar;
use bfinlay\SpreadsheetSeeder\Writers\Database\DestinationTable;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\ForeignKeyTruncateTest\ForeignKeyTruncateSeeder;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ForeignKeyTruncateTest extends TestCase
{
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertEquals([
            'id',
            'created_at',
            'updated_at',
            'user_id',
            'favorite_number'
        ], Schema::getColumnListing('favorite_numbers'));
    }

    /**
     * Verify that truncating a table with a foreign key constraint throws a foreign key constraint exception
     *
     * @depends it_runs_the_migrations
     *
     * Postgres always truncates and cascades.  See vendor/laravel/framework/src/Illuminate/Database/Query/Grammars/PostgresGrammar.php compileTruncate(): truncate [table] restart identity cascade
     */
    public function test_integrity_constraints_prevent_truncation()
    {
        if (DB::connection()->getDriverName() == "pgsql") $this->markTestSkipped('Test skipped for Postgres because Laravel always runs "truncate [table] restart identity cascade" for Postgres');
        $this->seed(ForeignKeyTruncateSeeder::class);

        /** @var $settings SpreadsheetSeederSettings */
        $settings = app(SpreadsheetSeederSettings::class);
        $settings->truncateIgnoreForeign = false;

        $this->assertEquals(2, DB::table('users')->count());
        $this->assertEquals(2, DB::table('favorite_numbers')->count());

        $this->expectExceptionMessage(ExpressionGrammar::make()
            ->sqLite('Integrity constraint violation: 19 FOREIGN KEY constraint failed')
            ->mySql('Syntax error or access violation: 1701 Cannot truncate a table referenced in a foreign key constraint')
            ->postgres('Laravel always runs "truncate [table] restart identity cascade"')
            ->sqlServer('Cannot truncate table \'users\' because it is being referenced by a FOREIGN KEY constraint.')
        );
        DB::table('users')->truncate();

        $this->assertEquals(2, DB::table('users')->count());
        $this->assertEquals(2, DB::table('favorite_numbers')->count());
    }


    /**
     * Create a new destination table and verify that truncation disables integrity constraints
     *
     * @depends it_runs_the_migrations
     */
    public function test_destination_table_truncation_observes_integrity_constraints()
    {
        if (DB::connection()->getDriverName() == "pgsql") $this->markTestSkipped('Test skipped for Postgres because Laravel always runs "truncate [table] restart identity cascade" for Postgres');
        $this->seed(ForeignKeyTruncateSeeder::class);

        $settings = resolve(SpreadsheetSeederSettings::class);
        $settings->truncateIgnoreForeign = false;

        $this->assertEquals(2, DB::table('users')->count());
        $this->assertEquals(2, DB::table('favorite_numbers')->count());

        $this->expectExceptionMessage(ExpressionGrammar::make()
            ->sqLite('Integrity constraint violation: 19 FOREIGN KEY constraint failed')
            ->mySql('Syntax error or access violation: 1701 Cannot truncate a table referenced in a foreign key constraint')
            ->postgres('Syntax error or access violation: 1701 Cannot truncate a table referenced in a foreign key constraint')
            ->sqlServer('Cannot truncate table \'users\' because it is being referenced by a FOREIGN KEY constraint.')
        );
        $usersTable = new DestinationTable('users');

        $this->assertEquals(0, DB::table('users')->count());
        $this->assertEquals(2, DB::table('favorite_numbers')->count());
    }


    /**
     * Create a new destination table and verify that truncation disables integrity constraints
     *
     * @depends it_runs_the_migrations
     */
    public function test_destination_table_truncation_ignores_integrity_constraints()
    {
        $this->seed(ForeignKeyTruncateSeeder::class);

        $settings = resolve(SpreadsheetSeederSettings::class);
        $settings->truncateIgnoreForeign = true;

        $this->assertEquals(2, DB::table('users')->count());
        $this->assertEquals(2, DB::table('favorite_numbers')->count());

        $usersTable = new DestinationTable('users');

        $this->assertEquals(0, DB::table('users')->count());
        if (DB::connection()->getQueryGrammar() instanceof PostgresGrammar)
            // Postgres always truncates and cascades.  See vendor/laravel/framework/src/Illuminate/Database/Query/Grammars/PostgresGrammar.php compileTruncate(): truncate [table] restart identity cascade
            $this->assertEquals(0, DB::table('favorite_numbers')->count());
        else
            $this->assertEquals(2, DB::table('favorite_numbers')->count());
    }

}
