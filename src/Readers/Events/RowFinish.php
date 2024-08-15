<?php


namespace bfinlay\SpreadsheetSeeder\Readers\Events;


use bfinlay\SpreadsheetSeeder\Readers\Rows;
use Illuminate\Foundation\Events\Dispatchable;

class RowFinish
{
    use Dispatchable;

    /**
     * @var string[] $row
     */
    public $row;

    /**
     * @var string[] $columns
     */
    public $columns;

    /**
     * StartChunk constructor.
     * @param $rows Rows
     */
    public function __construct($row, $columns = [])
    {
        $this->row = $row;
        $this->columns = $columns;
    }
}
