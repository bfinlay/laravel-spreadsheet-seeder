<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use PHPUnit\Framework\TestCase;

class SkipColumnsTest extends TestCase
{
    use AssertsMigrations;
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertsCustomersMigration();
    }
}
