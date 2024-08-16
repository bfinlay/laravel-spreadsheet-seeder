<?php


namespace bfinlay\SpreadsheetSeeder\Tests\LargeNumberOfRowsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;
use PHPUnit\Util\Test;

class FakeNamesCsvSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('LargeNumberOfRowsTest/fake_names_15k.csv');
        $set->tablename = 'fake_names';
        $set->aliases = ['Number' => 'id'];
        $set->textOutput = false;
    }
}