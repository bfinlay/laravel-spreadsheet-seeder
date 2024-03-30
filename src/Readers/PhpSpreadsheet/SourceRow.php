<?php


namespace bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet;


use bfinlay\SpreadsheetSeeder\Readers\RowImporter;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SourceRow
{
    /**
     * @var Row
     */
    private $sheetRow;

    /**
     * @var RowImporter
     */
    private $rowImporter;

    /**
     * @var string[]
     */
    private $columnNames;

    /**
     * @var array
     */
    private $rowArray;

    /**
     * @var array
     */
    private $rawRowArray;

    /**
     * @var boolean
     */
    private $isValid = false;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * SourceRow constructor.
     * @param Row $row
     * @param string[] $columnNames A sparse array mapping column index => column name
     */
    public function __construct(Row $row, $columnNames)
    {
        $this->sheetRow = $row;
        $this->columnNames = $columnNames;
        $this->rowImporter = new RowImporter($columnNames);
        $this->settings = resolve(SpreadsheetSeederSettings::class);
        $this->makeRow();
    }

    public function toArray() {
        return $this->rowArray;
    }
    
    public function rawRow() {
        return $this->rawRowArray;
    }

    public function isValid() {
        return $this->rowImporter->isValid();
    }

    private function makeRow() {
        $nullRow = true;
        $cellIterator = $this->sheetRow->getCellIterator();
        $colIndex = 0;

        $row = [];

        /** @var Cell $cell */
        foreach($cellIterator as $cell) {
            if (isset($this->columnNames[$colIndex])) {
                $row[$colIndex] = $cell->getCalculatedValue();
            }
            $this->rawRowArray[$colIndex] = $cell->getValue();

            $colIndex++;
        }

        $this->rowArray = $this->rowImporter->import($row);
    }
}