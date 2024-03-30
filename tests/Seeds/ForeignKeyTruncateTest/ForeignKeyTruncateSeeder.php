<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\ForeignKeyTruncateTest;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class ForeignKeyTruncateSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../bfinlay/laravel-excel-seeder-test-data/ForeignKeyTruncateTest/ForeignKeyTruncate.xlsx';
    }
}
