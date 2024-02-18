<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\UuidTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class UsersUuidSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../../examples/users.csv';
        $set->textOutput = false;
        $set->uuid = ['uuid'];
        $set->addColumns = ['uuid'];
    }
}