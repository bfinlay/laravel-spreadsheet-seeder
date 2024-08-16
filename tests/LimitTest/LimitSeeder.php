<?php


namespace bfinlay\SpreadsheetSeeder\Tests\LimitTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\LargeNumberOfRowsTest\FakeNamesXlsxSeeder;

class LimitSeeder extends FakeNamesXlsxSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->limit = 5000;
    }
}