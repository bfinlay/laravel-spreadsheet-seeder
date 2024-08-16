<?php


namespace bfinlay\SpreadsheetSeeder\Tests\DateTimeTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class DateTimeSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('DateTimeTest/DateTimeTest.xlsx');
        $set->textOutput = false;

        $set->unixTimestamps = ['unix_format'];
        $set->dateFormats = ['string_format_1' => 'Y-m-d H:i:s.u+'];
    }
}