<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederServiceProvider;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\LimitTest\LimitSeeder;

class LimitTest extends TestCase
{
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertEquals([
            'id',
            'Gender',
            'Title',
            'GivenName',
            'MiddleInitial',
            'Surname',
            'StreetAddress',
            'City',
            'State',
            'ZipCode',
            'created_at',
            'updated_at'
        ], \Schema::getColumnListing('fake_names'));
    }

    /**
     * @depends it_runs_the_migrations
     * @Depends LargeNumberOfRowsTest::test_15k_xlsx_rows
     */
    public function test_limit()
    {
        // limit seeder sets $limit setting to 5000 to seed only the first 5000 rows
        $this->seed(LimitSeeder::class);

        // check that exactly 5000 rows are seeded and no more
        $this->assertEquals(5000, \DB::table('fake_names')->count());

        // check row 5000 is seeded
        $fake = \DB::table('fake_names')->where('id', '=', 5000)->first();
        $this->assertEquals('Venuti', $fake->Surname);
        $this->assertEquals('Samuel', $fake->GivenName);
    }
}
