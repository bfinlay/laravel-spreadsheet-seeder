<?php


namespace bfinlay\SpreadsheetSeeder\Tests\EmptyValueTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class EmptyValueSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('EmptyValueTest/EmptyValueTest.xlsx');
        $set->textOutput = false;
    }
}