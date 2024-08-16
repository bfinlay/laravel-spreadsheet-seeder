<?php


namespace bfinlay\SpreadsheetSeeder\Tests\TablenameTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class ClassicModelsMultipleMappedNamedSheetSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('TablenameTest/classic-models-multiple-mapped-named-sheet.xlsx');
        $set->textOutput = false;
        $set->worksheetTableMapping = [
            'employees_mapped' => 'employees',
            'orders_mapped' => 'orders'
        ];
    }
}