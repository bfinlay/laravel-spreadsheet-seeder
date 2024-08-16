<?php

namespace bfinlay\SpreadsheetSeeder\Tests\SkipColumnsTest;

use bfinlay\SpreadsheetSeeder\Tests\AssertsMigrations;
use bfinlay\SpreadsheetSeeder\Tests\TestCase;

class SkipColumnsTest extends TestCase
{
    use AssertsMigrations;
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertsCustomersMigration();
    }
}
