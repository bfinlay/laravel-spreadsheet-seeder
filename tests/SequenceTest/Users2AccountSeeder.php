<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\ParsersTest\UsersCsvParsersSeeder;

class Users2AccountSeeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users2_account';
        $set->aliases = []; // settings are global singleton for request so need to be reset.
    }
}