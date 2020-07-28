<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class UsersCsvHashSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $this->file = '/../../../../examples/users.csv';
        $this->textOutput = false;
        $this->hashable = ['password'];
        parent::run();
    }
}