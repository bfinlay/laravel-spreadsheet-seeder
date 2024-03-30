<?php


namespace bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet;

use bfinlay\SpreadsheetSeeder\SeederMemoryHelper;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Writers\Database\DestinationTable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SourceSheet implements \Iterator
{
    /**
     * @var string
     */
    private $worksheetName;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var boolean
     */
    private $isSingleSheet = false;

    /**
     * @var BaseReader
     */
    private $reader;

    /**
     * @var ChunkReadFilter
     */
    private $readFilter;

    /**
     * @var Spreadsheet
     */
    private $workbook;

    /**
     * @var Worksheet
     */
    private $worksheet;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var int
     */
    private $rowOffset;

    /**
     * @var int
     */
    private $chunkStartRow;

    /**
     * @var int
     */
    private $chunkSize;

    /**
     * @var int
     */
    private $loadedChunk;

    /**
     * @var SourceHeader
     */
    private $header;

    /**
     * SourceSheet constructor.
     */
    public function __construct($fileName, $fileType, $worksheetName)
    {
        $this->fileName = $fileName;
        $this->fileType = $fileType;
        $this->worksheetName = $worksheetName;
        $this->settings = resolve(SpreadsheetSeederSettings::class);
        $this->tableName = $this->settings->tablename;
        $this->rowOffset = $this->settings->offset + 1;

        $this->createReadFilter();
        $this->createReader();
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'construct sheet');
        $this->loadHeader();
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'load header');

        $this->header = $this->constructHeaderRow();
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'construct header');
    }

    private function createReadFilter() {
        $this->readFilter = new ChunkReadFilter();
        $this->chunkSize = $this->settings->readChunkSize;
        $this->chunkStartRow = $this->rowOffset;
        $this->readFilter->setWorksheet($this->worksheetName);
    }

    private function createReader() {
        $this->reader = IOFactory::createReader($this->fileType);
        if ($this->fileType == "Csv" && !empty($this->settings->delimiter)) {
            $this->reader->setDelimiter($this->settings->delimiter);
        }
        $this->reader->setReadFilter($this->readFilter);
    }

    private function loadHeader() {
        if (!$this->settings->header) return;

        $this->loadChunk(1,1);
        $this->rowOffset++;
        $this->chunkStartRow = $this->rowOffset;
    }

    private function loadChunk($startRow = null, $chunkSize = null) {
        if (is_null($startRow)) $startRow = $this->chunkStartRow;
        if (is_null($chunkSize)) $chunkSize = $this->chunkSize;

        if ($this->loadedChunk == $startRow) return;

        if (isset($this->worksheet)) $this->worksheet->disconnectCells();
        unset($this->worksheet);
        $this->readFilter->setRows($startRow, $chunkSize);

        // reduces time from 10s to 4s on TablenameTest.test_table_name_is_worksheet_name (ClassicModelSeeder)
        $this->reader->setLoadSheetsOnly($this->worksheetName);

        $this->workbook = $this->reader->load($this->fileName);
        $this->worksheet = $this->workbook->setActiveSheetIndexByName($this->worksheetName);
        // This only returns the max row up to the chunk.  The only way to get the max row appears to be to load the entire file.
//      $this->maxRow = $this->worksheet->getHighestDataRow();
        $this->loadedChunk = $startRow;

        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'load chunk');
    }

    private function constructHeaderRow() {
        if ($this->settings->header == false) return null; // TODO adjust for mapping

        return new SourceHeader($this->worksheet->getRowIterator()->current(), $this->isCsv());
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function setFileType($fileType) {
        $this->fileType = $fileType;
    }

    public function getTableName() {
        if (isset($this->tableName)) {
            return $this->tableName;
        }
        else if (isset($this->settings->worksheetTableMapping[$this->worksheetName])) {
            $this->tableName = $this->settings->worksheetTableMapping[$this->worksheetName];
        }
        else if ($this->isSingleSheet && !$this->titleIsTable()) {
            $this->tableName = pathinfo($this->fileName)["filename"];
        }
        else {
            $this->tableName = $this->worksheetName;
        }

        return $this->tableName;
    }

    public function getHeader() {
        return $this->header;
    }

    public function isCsv() {
        return $this->fileType == "Csv";
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $this->loadChunk();
        return new SourceChunk($this->worksheet, $this->header, $this->chunkStartRow);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->chunkStartRow += $this->chunkSize;
        $this->loadChunk();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->chunkStartRow;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        $this->loadChunk();
        return $this->chunkStartRow <= $this->worksheet->getHighestDataRow();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->chunkStartRow = $this->rowOffset;
        $this->loadChunk();
    }

    public function getTitle() {
        return $this->worksheet->getTitle();
    }

    public function isUnnamed() {
        return $this->isCsv() || preg_match('/^Sheet[0-9]+$/', $this->getTitle());
    }

    public function titleIsTable() {
        return DestinationTable::tableExists($this->getTitle());
    }

    public function setSingleSheet($isSingle = true) {
        $this->isSingleSheet = $isSingle;
    }

    public function isSingleSheet() {
        return $this->isSingleSheet;
    }
}