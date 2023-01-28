<?php

namespace bfinlay\SpreadsheetSeeder;

class MySqlGrammar extends \Illuminate\Database\Schema\Grammars\MySqlGrammar
{
    /**
     * Compile the query to determine the list of columns.
     *
     * @return string
     */
    public function compileColumnListing()
    {
        return 'select column_name as `column_name` from information_schema.columns where table_schema = ? and table_name = ? ORDER BY ORDINAL_POSITION';
    }
}