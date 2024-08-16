<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class ClassicModelsSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->file = TestsPath::forSettings('../examples/classicmodels.xlsx');
//        $this->textOutput = false;
        $this->batchInsertSize = 5000;
        $this->readChunkSize = 5000;
        parent::run();
    }
}