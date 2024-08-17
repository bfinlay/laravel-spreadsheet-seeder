<?php


namespace bfinlay\SpreadsheetSeeder\Tests\LargeNumberOfRowsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class FakeNamesXlsxSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::largeRowsForSettings('fake_names_15k.xlsx');
        $set->tablename = 'fake_names';
        $set->aliases = ['Number' => 'id'];
        $set->textOutput = false;
    }
}