<?php


namespace bfinlay\SpreadsheetSeeder\Tests\UuidTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class UsersUuidSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('../examples/users.csv');
        $set->textOutput = false;
        $set->uuid = ['uuid'];
        $set->addColumns = ['uuid'];
    }
}