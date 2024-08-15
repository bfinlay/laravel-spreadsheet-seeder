<?php


namespace bfinlay\SpreadsheetSeeder;


use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Class SourceChunkReadFilter
 * @package bfinlay\SpreadsheetSeeder
 *
 * Implement IReadFilter interface of PhpOffice to limit which rows are read from the file
 * Follows the example in the documentation at https://phpspreadsheet.readthedocs.io/en/latest/topics/reading-files/
 * (search for "chunk")
 */
class ChunkReadFilter implements IReadFilter
{
    private $worksheetName = '';
    private $startRow = 0;
    private $endRow   = 0;

    public function setWorksheet($worksheetName) {
        $this->worksheetName = $worksheetName;
    }

    /**  Set the list of rows that we want to read  */
    public function setRows($startRow, $chunkSize) {
        $this->startRow = $startRow;
        $this->endRow   = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '') {
        //  Only read the heading row, and the configured rows
        if (
            $row >= $this->startRow &&
            $row < $this->endRow &&
            ($worksheetName == $this->worksheetName ||
             $worksheetName == '')
            ) {
            return true;
        }
        return false;
    }
}