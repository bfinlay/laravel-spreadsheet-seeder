<?php


namespace bfinlay\SpreadsheetSeeder\Tests\ForeignKeyTruncateTest;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class ForeignKeyTruncateSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('ForeignKeyTruncateTest/ForeignKeyTruncate.xlsx');
    }
}
