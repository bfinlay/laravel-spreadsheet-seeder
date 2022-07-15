<?php

namespace bfinlay\SpreadsheetSeeder;

use bfinlay\SpreadsheetSeeder\Events\Console;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileSeed;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetStart;
use bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet\SpreadsheetReader;
use bfinlay\SpreadsheetSeeder\Writers\Console\ConsoleWriter;
use bfinlay\SpreadsheetSeeder\Writers\Database\DatabaseWriter;
use bfinlay\SpreadsheetSeeder\Writers\Text\Markdown\MarkdownWriter;
use bfinlay\SpreadsheetSeeder\Writers\Text\TextOutputWriter;
use bfinlay\SpreadsheetSeeder\Writers\Text\Yaml\YamlWriter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Finder\Finder;


class SpreadsheetSeeder extends Seeder
{
    /**
     * Settings
     *
     * @var SpreadsheetSeederSettings
     */
    protected $settings;

    /**
     * @var ConsoleWriter
     */
    protected $consoleWriter;

    /**
     * @var DatabaseWriter
     */
    protected $databaseWriter;

    /**
     * @var MarkdownWriter
     */
    protected $markdownWriter;

    /**
     * @var YamlWriter
     */
    protected $yamlWriter;

    /**
     * @var SpreadsheetReader
     */
    protected $spreadsheetReader;

    /**
     * @var string[]
     */
    public $tablesSeeded;

    public function __construct(SpreadsheetSeederSettings $settings,
                                ConsoleWriter $consoleWriter,
                                DatabaseWriter $databaseWriter,
                                MarkdownWriter $markdownWriter,
                                YamlWriter $yamlWriter,
                                SpreadsheetReader $spreadsheetReader)
    {
        $this->settings = $settings;
        $this->consoleWriter = $consoleWriter;
        $this->databaseWriter = $databaseWriter;
        $this->markdownWriter = $markdownWriter;
        $this->yamlWriter = $yamlWriter;
        $this->spreadsheetReader = $spreadsheetReader;

    }

    public function boot()
    {
        $this->consoleWriter->boot($this->command);
        $this->databaseWriter->boot();
        $this->markdownWriter->boot();
        $this->yamlWriter->boot();
        $this->spreadsheetReader->boot();
    }

    public function settings(SpreadsheetSeederSettings $set)
    {
        /* do nothing unless overridden by subclass */
    }

    /**
     * Run the class
     *
     * @return void
     */
    public function run()
    {
        $this->settings($this->settings);

        $this->boot();

        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'start');

        $finder = $this->finder();

        if (!$finder->hasResults()) {
            event(new Console('No spreadsheet file given', 'error'));
            return;
        }

        foreach ($finder as $file) {
            SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'file');
            event(new FileStart($file));
            event(new FileSeed($file));
            event(new FileFinish($file));
        }

        $this->tablesSeeded = $this->databaseWriter->tablesSeeded;

        $this->cleanup();
    }

    public function __set($name, $value) {
        $this->settings->$name = $value;
    }

    public function command() {
        return $this->command;
    }

    public function finder()
    {
        if ($this->settings->file instanceof Finder) return $this->settings->file;

        return new FileIterator();
    }

    public function cleanup()
    {
        $events = [
            Console::class,
            FileStart::class,
            FileSeed::class,
            FileFinish::class,
            SheetStart::class,
            SheetFinish::class,
            ChunkStart::class,
            ChunkFinish::class,
        ];

        foreach ($events as $event) Event::forget($event);
    }
}
