<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\FinderTest;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Symfony\Component\Finder\Finder;

class FinderSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->textOutput = false;
        $set->batchInsertSize = 5000;
        $set->readChunkSize = 5000;
    }
}