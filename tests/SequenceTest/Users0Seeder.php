<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\ParsersTest\UsersCsvParsersSeeder;

class Users0Seeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users0';
    }
}