<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\UuidTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class UsersUuidNoColumnSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = '/../../../../examples/users.csv';
        $set->textOutput = false;
        $set->uuid = ['uuid'];

        // Column is not added, so uuid is not seeded into table because it is not present in the source file.
        // $set->addColumns = ['uuid'];
    }
}