<?php


namespace bfinlay\SpreadsheetSeeder\Readers;


class Rows
{
    /**
     * @var string[][]
     */
    public $rows = [];

    /**
     * @var string[][]
     */
    public $rawRows = [];

    /**
     * @var int
     */
    public $skippedRows = 0;

    /**
     * @var int
     */
    public $processedRows = 0;

    /**
     * @var int
     */
    public $startRow = 0;

    public function isEmpty()
    {
        return empty($this->rows) && empty($this->rawRows);
    }

    public function count()
    {
        return count($this->rows);
    }

    public function countRowAsProcessed()
    {
        $this->processedRows++;
    }

    public function countRowAsSkipped()
    {
        $this->skippedRows++;
    }
}