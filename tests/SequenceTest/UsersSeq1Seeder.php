<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\ParsersTest\UsersCsvParsersSeeder;

class UsersSeq1Seeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users_seq1';
        $set->aliases = ['id' => 'users_seq1_id'];
    }
}