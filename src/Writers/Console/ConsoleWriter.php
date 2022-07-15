<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Console;


use bfinlay\SpreadsheetSeeder\Events\Console;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetStart;
use bfinlay\SpreadsheetSeeder\SeederMemoryHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Finder\SplFileInfo;

class ConsoleWriter
{
    /**
     * @var Command
     */
    public $command;

    /**
     * @var SplFileInfo
     */
    public $file;

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
    public $currentRow = 0;

    /**
     * @var int
     */
    public $chunkCount = 0;

    /**
     * @var int
     */
    public $rowsInserted = 0;

    /**
     * @var int
     */
    public $rowsProcessed = 0;

    /**
     * ConsoleWriter constructor.
     * @param $command Command
     */
    public function __construct(Command $command)
    {
        $this->setCommand($command);
    }

    public function setCommand(Command $command)
    {
        $this->command = $command;
    }

    public function boot(Command $command = null)
    {
        if (isset($command)) $this->setCommand($command);
        Event::listen(Console::class, [$this, 'handleConsole']);
        Event::listen(FileStart::class, [$this, 'handleFileStart']);
        Event::listen(SheetStart::class, [$this, 'handleSheetStart']);
        Event::listen(ChunkStart::class, [$this, 'handleChunkStart']);
        Event::listen(ChunkFinish::class, [$this, 'handleChunkFinish']);
        Event::listen(SheetFinish::class, [$this, 'handleSheetFinish']);
    }

    /**
     * Logging
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    public function console( $message, $level = FALSE )
    {
        if( $level ) $message = '<'.$level.'>'.$message.'</'.$level.'>';

        $this->command->line( '<comment>SpreadsheetSeeder: </comment>'.$message );
    }

    /**
     * Logging
     *
     * @param Console $consoleEvent
     * @return void
     */
    public function handleConsole( $consoleEvent )
    {
        $this->console($consoleEvent->message, $consoleEvent->level);
    }

    public function handleFileStart(FileStart $fileStart)
    {
        $this->file = $fileStart->file;
    }

    public function currentRowMessage()
    {
        $m = SeederMemoryHelper::measurements();
        $message = "File: " . $this->file->getFilename() . " Sheet: " . $this->sheetName . " Row: " . $this->currentRow . " " . $m["memory"] . " " . $m["time"];
        $this->console($message, "info");
        SeederMemoryHelper::memoryLog($message);
    }

    public function handleSheetStart(SheetStart $sheetStart)
    {
        $this->sheetName = $sheetStart->sheetName;
        $this->tableName = $sheetStart->tableName;
        $this->currentRow = $sheetStart->startRow;

        $this->currentRowMessage();
    }

    public function handleChunkStart(ChunkStart $chunkStart)
    {
        $this->currentRow = $chunkStart->startRow;

        $this->chunkCount++;
        if ($this->chunkCount > 1) {
            $this->currentRowMessage();
        }
    }

    public function handleChunkFinish(ChunkFinish $chunkFinish)
    {
        $this->rowsInserted += $chunkFinish->rows->count();
        $this->rowsProcessed += $chunkFinish->rows->processedRows;
    }

    public function handleSheetFinish(SheetFinish $sheetFinish)
    {
        $this->console($this->rowsInserted . ' of ' . $this->rowsProcessed . ' rows has been seeded in table "' . $this->tableName . '"');

        $this->rowsProcessed = 0;
        $this->rowsInserted = 0;
        $this->chunkCount = 0;
    }

}