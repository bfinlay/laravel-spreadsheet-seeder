<?php


namespace bfinlay\SpreadsheetSeeder;


use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class SourceFileReadFilter implements IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {
        //  Only read the heading row
        return $row == 1;
    }
}