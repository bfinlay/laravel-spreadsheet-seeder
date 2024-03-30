<?php


namespace bfinlay\SpreadsheetSeeder\Readers;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class HeaderImporter
{
    /**
     * @var array
     */
    private $headerRow;

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
     * Map of post-processed column names to column numbers
     *
     * @var int[]
     */
    public $columnNumbersByNameMap;

    /**
     * Sparse array; map of column numbers to post-processed column names
     *
     * @ var string[]
     */
    public $columnNamesByNumberMap;

    /**
     * Header constructor.
     * @param array $headerRow
     */
    public function __construct()
    {
        $this->settings = resolve(SpreadsheetSeederSettings::class);
    }

    public function import(array $headerRow)
    {
        $this->headerRow = $headerRow;
        $this->makeHeader();

        return $this->toArray();
    }

    private function makeHeader()
    {
        if (!empty($this->settings->mapping)) {
            $this->makeMappingHeader();
        } else {
            $this->makeSheetHeader();
        }
    }

    private function makeMappingHeader() {
            $this->rawColumns = $this->settings->mapping;
            foreach($this->rawColumns as $key => $value) {
                $this->columnNumbersByNameMap[$value] = $key;
                $this->columnNamesByNumberMap[$key] = $value;
            }
    }

    private function makeSheetHeader() {
        foreach ($this->headerRow as $columnName) {
            $this->rawColumns[] = $columnName;
            if (!$this->skipColumn($columnName)) {
                $columnName = $this->columnAlias($columnName);
                $this->columnNumbersByNameMap[$columnName] = count($this->rawColumns) - 1;
                $this->columnNamesByNumberMap[count($this->rawColumns) - 1] = $columnName;
            }
        }
    }

    private function columnAlias($columnName) {
        $columnName = isset($this->settings->aliases[$columnName]) ? $this->settings->aliases[$columnName] : $columnName;
        return $columnName;
    }

    private function skipColumn($columnName) {
        return $this->settings->skipper == substr($columnName, 0, strlen($this->settings->skipper));
    }

    public function toArray() {
        return $this->columnNamesByNumberMap;
    }
    
    public function rawColumns() {
        return $this->rawColumns;
    }
}