<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\ParsersTest\UsersCsvParsersSeeder;

class Users3Seeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users3';
        $set->aliases = []; // settings are global singleton for request so need to be reset.
    }
}