<?php


namespace bfinlay\SpreadsheetSeeder\Tests\ParsersTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class UsersCsvParsersSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('../examples/users.csv');
        $set->textOutput = false;
        $set->parsers = ['email' => function ($value) {
            return strtolower($value);
        }];
    }
}