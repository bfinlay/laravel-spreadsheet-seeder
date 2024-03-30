<?php


namespace bfinlay\SpreadsheetSeeder\Readers\Events;


use bfinlay\SpreadsheetSeeder\Readers\Rows;
use Illuminate\Foundation\Events\Dispatchable;

class ChunkStart
{
    use Dispatchable;

    /**
     * @var Rows
     */
    public $rows;

    /**
     * @var int
     */
    public $startRow;

    /**
     * StartChunk constructor.
     * @param $rows Rows
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
        $this->startRow = $rows->startRow;
    }
}
