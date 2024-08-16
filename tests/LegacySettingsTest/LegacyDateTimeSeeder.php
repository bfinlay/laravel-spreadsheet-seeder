<?php


namespace bfinlay\SpreadsheetSeeder\Tests\LegacySettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class LegacyDateTimeSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->file = TestsPath::forSettings('DateTimeTest/DateTimeTest.xlsx');
        $this->textOutput = false;

        $this->unixTimestamps = ['unix_format'];
        $this->dateFormats = ['string_format_1' => 'Y-m-d H:i:s.u+'];

        parent::run();
    }
}