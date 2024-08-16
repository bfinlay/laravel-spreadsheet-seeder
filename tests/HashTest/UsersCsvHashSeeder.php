<?php


namespace bfinlay\SpreadsheetSeeder\Tests\HashTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class UsersCsvHashSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('../examples/users.csv');
        $set->textOutput = false;
        $set->hashable = ['password'];
    }
}