<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\UuidTest\UsersCsvParsersSeeder;

use Illuminate\Support\Str;

class UuidTest extends TestCase
{
    use AssertsMigrations;
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertsUsersMigration();
    }

    /**
     * Seed a CSV file and verify that the CSV filename is used as the table name
     */
    public function test_table_name_is_csv_filename()
    {
        $this->seed(UsersCsvParsersSeeder::class);

        $user = \DB::table('users')->where('name', 'John')->first();
        $this->assertNotNull($user->uuid);
        $this->assertTrue(Str::isUuid($user->uuid));
        $this->assertEquals(2, \DB::table('users')->count());
    }
}