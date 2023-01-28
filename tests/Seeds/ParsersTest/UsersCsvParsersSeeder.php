<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\ParsersTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class UsersCsvParsersSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../../examples/users.csv';
        $set->textOutput = false;
        $set->parsers = ['email' => function ($value) {
            return strtolower($value);
        }];
    }
}