<?php

namespace bfinlay\SpreadsheetSeeder\Tests\SequenceTest;

class DatabaseWriter extends \bfinlay\SpreadsheetSeeder\Writers\Database\DatabaseWriter
{
    public function updatePostgresSeqCounters($table)
    {
        /* do nothing */
    }
}