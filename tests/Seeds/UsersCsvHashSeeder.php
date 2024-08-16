<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class UsersCsvHashSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        $this->file = TestsPath::forSettings('../examples/users.csv');
        $this->textOutput = false;
        $this->hashable = ['password'];
        parent::run();
    }
}