<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\DateTimeTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class DateTimeSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../bfinlay/laravel-excel-seeder-test-data/DateTimeTest/DateTimeTest.xlsx';
        $set->textOutput = false;

        $set->unixTimestamps = ['unix_format'];
        $set->dateFormats = ['string_format_1' => 'Y-m-d H:i:s.u+'];
    }
}