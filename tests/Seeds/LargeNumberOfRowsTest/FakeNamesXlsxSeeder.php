<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\LargeNumberOfRowsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class FakeNamesXlsxSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../bfinlay/laravel-excel-seeder-test-data/LargeNumberOfRowsTest/fake_names_15k.xlsx';

        $set->tablename = 'fake_names';
        $set->aliases = ['Number' => 'id'];
        $set->textOutput = false;
    }
}