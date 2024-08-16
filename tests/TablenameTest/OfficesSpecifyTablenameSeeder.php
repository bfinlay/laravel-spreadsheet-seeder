<?php


namespace bfinlay\SpreadsheetSeeder\Tests\TablenameTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class OfficesSpecifyTablenameSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('TablenameTest/offices-tablename.xlsx');
        $set->textOutput = false;
        $set->tablename = 'offices';
    }
}