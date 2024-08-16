<?php


namespace bfinlay\SpreadsheetSeeder\Tests\UuidTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class UsersUuidNoColumnSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('../examples/users.csv');
        $set->textOutput = false;
        $set->uuid = ['uuid'];

        // Column is not added, so uuid is not seeded into table because it is not present in the source file.
        // $set->addColumns = ['uuid'];
    }
}