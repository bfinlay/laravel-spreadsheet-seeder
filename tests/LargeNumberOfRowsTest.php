<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\LargeNumberOfRowsTest\FakeNames100kXlsxSeeder;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\LargeNumberOfRowsTest\FakeNamesCsvSeeder;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\LargeNumberOfRowsTest\FakeNamesXlsxSeeder;

class LargeNumberOfRowsTest extends TestCase
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
     *
     * Seed csv file with 15k rows and verify that last entry is accurate
     *
     */
    public function disabled_test_15k_rows()
    {
        $this->seed(FakeNamesCsvSeeder::class);

        $fake = \DB::table('fake_names')->where('id', '=', 15000)->first();
        $this->assertEquals('Culver', $fake->Surname);
        $this->assertEquals('Alicia', $fake->GivenName);
    }

    /**
     * @depends it_runs_the_migrations
     *
     * Seed excel file with 15k rows and verify that last entry is accurate
     *
     */
    public function disabled_test_15k_xlsx_rows()
    {
        $this->seed(FakeNamesXlsxSeeder::class);

        $fake = \DB::table('fake_names')->where('id', '=', 15000)->first();
        $this->assertEquals('Culver', $fake->Surname);
        $this->assertEquals('Alicia', $fake->GivenName);
        $this->assertEquals(15000, \DB::table('fake_names')->count());
    }

    /**
     * @depends it_runs_the_migrations
     *
     * Seed excel file with 100k rows and verify that last entry is accurate
     *
     * current status Jul 28, 2020:
     * test passes
     * disabled by default because it takes 30 min to run.  remove "disabled_" to run.
     */
    public function disabled_test_100k_xlsx_rows()
    {
        $this->seed(FakeNames100kXlsxSeeder::class);

        $count = \DB::table('fake_names')->count();
        $fake = \DB::table('fake_names')->where('id', '=', 100000)->first();
        $this->assertEquals('Riedel', $fake->Surname);
        $this->assertEquals('Robert', $fake->GivenName);
        $this->assertEquals(100000, \DB::table('fake_names')->count());
    }

}
