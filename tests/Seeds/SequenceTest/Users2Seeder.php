<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\ParsersTest\UsersCsvParsersSeeder;

class Users2Seeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users2';
        $set->aliases = ['id' => 'account_id'];
    }
}