<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\LegacySettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class LegacyDateTimeSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $this->file = '/../../../bfinlay/laravel-excel-seeder-test-data/DateTimeTest/DateTimeTest.xlsx';
        $this->textOutput = false;

        $this->unixTimestamps = ['unix_format'];
        $this->dateFormats = ['string_format_1' => 'Y-m-d H:i:s.u+'];

        parent::run();
    }
}