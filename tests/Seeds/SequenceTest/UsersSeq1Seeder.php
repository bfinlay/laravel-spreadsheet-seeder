<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\SequenceTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\ParsersTest\UsersCsvParsersSeeder;

class UsersSeq1Seeder extends UsersCsvParsersSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        parent::settings($set);
        $set->tablename = 'users_seq1';
        $set->aliases = ['id' => 'users_seq1_id'];
    }
}