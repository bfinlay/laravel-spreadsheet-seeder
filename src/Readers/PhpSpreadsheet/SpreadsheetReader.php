<?php


namespace bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet;

use bfinlay\SpreadsheetSeeder\Events\Console;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileSeed;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetStart;
use bfinlay\SpreadsheetSeeder\Readers\Rows;
use bfinlay\SpreadsheetSeeder\SeederMemoryHelper;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Illuminate\Support\Facades\Event;

class SpreadsheetReader
{
    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * @var SourceFile
     */
    private $sourceFile;

    /**
     * @var SourceSheet
     */
    private $sourceSheet;

    /**
     * @var SourceChunk
     */
    private $sourceChunk;

    /**
     * @var Rows
     */
    private $rows;

    /**
     * @var int
     */
    private $rowsProcessed = 0;

    public function __construct()
    {
        $this->settings = resolve(SpreadsheetSeederSettings::class);
    }

    /**
     * Run the class
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(FileSeed::class, [$this, 'handleFileSeed']);
    }

    /**
     * @param $fileSeed FileSeed
     */
    public function handleFileSeed($fileSeed)
    {
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'seed');

        $this->sourceFile = new SourceFile($fileSeed->file);

        foreach ($this->sourceFile as $this->sourceSheet) {
            $this->sheetStart();
            SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'sheet start');

            $this->checkColumns();
            SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'check columns');

            foreach ($this->sourceSheet as $this->sourceChunk) {
                SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'start chunk iterator');

                $this->chunkStart();
                SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'chunk start');

                $this->processRows();
                $this->chunkFinish();
                SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'chunk finish');

                if ($this->exceedsLimit()) break;

                $this->clearChunkMemory();
                SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'processed');
            }

            $this->sheetFinish();
        }
    }

    private function clearChunkMemory() {
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'start clear chunk memory');
        $this->rows = new Rows();
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'finish clear chunk memory');

    }

    private function checkColumns()
    {
        if ($this->sourceSheet->isCsv() && count($this->sourceSheet->getHeader()->toArray()) == 1)
            event(new Console('Found only one column in header.  Maybe the delimiter set for the CSV is incorrect: [' . $this->sourceFile->getDelimiter() . ']'));
    }

    private function tableName()
    {
        return isset($this->settings->tablename) ? $this->settings->tablename : $this->sourceSheet->getTableName();
    }

    private function sheetStart()
    {
        event(new SheetStart($this->sourceSheet->getTitle(), $this->tableName(), $this->sourceSheet->key(), $this->sourceSheet->getHeader()->rawColumns()));
    }

    /**
     * Process each row of the data source
     *
     * @return void
     */
    private function processRows()
    {
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'start process rows');
        foreach ($this->sourceChunk as $row) {
            $this->rowsProcessed++;
            $this->rows->countRowAsProcessed();

            if (!$row->isValid()) {
                $this->rows->countRowAsSkipped();
                continue;
            }

            $this->rows->rows[] = $row->toArray();
            $this->rows->rawRows[] = $row->rawRow();

            if ($this->exceedsLimit()) break;
        }
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'finish process rows');
    }

    private function exceedsLimit()
    {
        return isset($this->settings->limit) && $this->rowsProcessed >= $this->settings->limit;
    }

    private function chunkStart()
    {
        $this->rows = new Rows();
        $this->rows->startRow = $this->sourceChunk->key();
        event(new ChunkStart($this->rows));
    }

    private function chunkFinish()
    {
        event(new ChunkFinish($this->rows));
    }

    private function sheetFinish()
    {
        event(new SheetFinish($this->sourceSheet->getTitle(), $this->tableName()));
    }
}
