<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\TablenameTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class ClassicModelsMultipleMappedNamedSheetSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../bfinlay/laravel-excel-seeder-test-data/TablenameTest/classic-models-multiple-mapped-named-sheet.xlsx';

        $set->textOutput = false;
        $set->worksheetTableMapping = [
            'employees_mapped' => 'employees',
            'orders_mapped' => 'orders'
        ];
    }
}