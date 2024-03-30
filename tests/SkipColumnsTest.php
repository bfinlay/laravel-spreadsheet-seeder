<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

class SkipColumnsTest extends TestCase
{
    use AssertsMigrations;
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertsCustomersMigration();
    }
}
