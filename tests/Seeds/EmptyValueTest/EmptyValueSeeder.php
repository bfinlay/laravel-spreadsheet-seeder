<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\EmptyValueTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class EmptyValueSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../bfinlay/laravel-excel-seeder-test-data/EmptyValueTest/EmptyValueTest.xlsx';
        $set->textOutput = false;
    }
}