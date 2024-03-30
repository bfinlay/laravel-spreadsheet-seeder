<?php


namespace bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet;

use bfinlay\SpreadsheetSeeder\Readers\HeaderImporter;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SourceHeader
{
    /**
     * @var Row
     */
    private $sheetRow;

    /**
     * @var HeaderImporter
     */
    private $headerImporter;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * Array of raw column names unaliased and un-skipped from the sheet
     *
     * @var string[]
     */
    private $rawColumns;

    /**
     * Header constructor.
     * @param Row $headerRow
     * @param boolean $isCsv
     */
    public function __construct(Row $headerRow)
    {
        $this->sheetRow = $headerRow;
        $this->settings = resolve(SpreadsheetSeederSettings::class);
        $this->headerImporter = new HeaderImporter();
        $this->makeHeader();
    }

    private function makeHeader()
    {
        if (!empty($this->settings->mapping)) {
            $this->rawColumns = $this->settings->mapping;
        } else {
            $this->rawColumns = $this->readSheetHeader();
        }

        $this->headerImporter->import($this->rawColumns);
    }

    private function readSheetHeader() {
        $rawColumns = [];

        foreach ($this->sheetRow->getCellIterator() as $cell) {
            $rawColumns[] = $cell->getCalculatedValue();
        }

        return $rawColumns;
    }

    public function toArray() {
        return $this->headerImporter->toArray();
    }
    
    public function rawColumns() {
        return $this->rawColumns;
    }
}