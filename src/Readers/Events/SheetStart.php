<?php


namespace bfinlay\SpreadsheetSeeder\Readers\Events;


use Illuminate\Foundation\Events\Dispatchable;

class SheetStart
{
    use Dispatchable;

    /**
     * @var string
     */
    public $sheetName;

    /**
     * @var string
     */
    public $tableName;


    /**
     * @var int
     */
    public $startRow;

    /**
     * @var string[]
     */
    public $header;


    /**
     * SheetStart constructor.
     * @param string $sheetName
     * @param string $tableName
     * @param array $header
     */
    public function __construct($sheetName, $tableName, $startRow = 0, array $header = [])
    {
        $this->sheetName = $sheetName;
        $this->tableName = $tableName;
        $this->startRow = $startRow;
        $this->header = $header;
    }
}