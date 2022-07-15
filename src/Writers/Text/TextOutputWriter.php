<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Text;


use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetStart;
use Illuminate\Support\Facades\Event;

class TextOutputWriter
{
    /**
     * @var TextOutputFileRepository
     */
    protected $repository;

    /**
     * @var TextTableFormatterInterface
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @param string|null $extension
     * @param TextTableFormatterInterface|null $textTableFormatter
     */
    public function __construct(string $extension = "txt", TextTableFormatterInterface $textTableFormatter = null)
    {
        $this->extension = $extension;
        $this->formatter = $textTableFormatter;
    }

    public function boot()
    {
        Event::listen(FileStart::class, [$this, 'handleFileStart']);
        Event::listen(SheetStart::class, [$this, 'handleSheetStart']);
        Event::listen(ChunkFinish::class, [$this, 'handleChunkFinish']);
        Event::listen(SheetFinish::class, [$this, 'handleSheetFinish']);
    }

    /**
     * @param $fileStart FileStart
     */
    public function handleFileStart($fileStart)
    {
        $this->repository = new TextOutputFileRepository($fileStart->file, $this->extension);
    }

    /**
     * @param $sheetStart SheetStart
     */
    public function handleSheetStart($sheetStart)
    {
        $this->repository->openSheet($sheetStart->tableName);
        $this->repository->write($this->formatter->tableName($sheetStart->tableName));
        $this->repository->write($this->formatter->header($sheetStart->header));
    }

    /**
     * @param $chunkFinish ChunkFinish
     */
    public function handleChunkFinish($chunkFinish)
    {
        $this->repository->write($this->formatter->rows($chunkFinish->rows->rawRows));
    }

    /**
     * @param $sheetFinish SheetFinish
     */
    public function handleSheetFinish($sheetFinish)
    {
        $this->repository->write($this->formatter->footer());
        $this->repository->closeSheet();
    }
}