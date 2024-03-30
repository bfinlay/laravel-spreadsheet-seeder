<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\LimitTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\LargeNumberOfRowsTest\FakeNamesXlsxSeeder;

class LimitSeeder extends FakeNamesXlsxSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->limit = 5000;
    }
}