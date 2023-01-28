<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Database;


use bfinlay\SpreadsheetSeeder\Events\Console;
use bfinlay\SpreadsheetSeeder\Readers\Events\ChunkFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileStart;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\SheetStart;
use bfinlay\SpreadsheetSeeder\Readers\Rows;
use bfinlay\SpreadsheetSeeder\SeederMemoryHelper;
use Exception;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class DatabaseWriter
{
    /**
     * @var DestinationTable
     */
    protected $seedTable;

    protected $fileName;
    protected $sheetName;

    /**
     * @var string[]
     */
    public $tablesSeeded = [];

    public function boot()
    {
        // Prevent Laravel Framework memory leaks per https://github.com/laravel/framework/issues/30012
        DB::connection()->disableQueryLog();
        DB::connection()->unsetEventDispatcher();

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
        $this->fileName = $fileStart->file->getFilename();
        $this->tablesSeeded = [];
    }

    /**
     * @param $sheetStart SheetStart
     */
    public function handleSheetStart($sheetStart)
    {
        $this->seedTable = new DestinationTable($sheetStart->tableName);
        $this->sheetName = $sheetStart->sheetName;

        if (!$this->seedTable->exists()) {
            event(new Console('Table "' . $sheetStart->tableName . '" could not be found in database', 'error'));
            return;
        }
    }

    /**
     * @param $chunkFinish ChunkFinish
     */
    public function handleChunkFinish($chunkFinish)
    {
        $this->insertRows($chunkFinish->rows);
    }

    /**
     * @param $sheetFinish SheetFinish
     */
    public function handleSheetFinish($sheetFinish)
    {
        $this->tablesSeeded[] = $sheetFinish->tableName;
        $this->updatePostgresSeqCounters($sheetFinish->tableName);
        SeederMemoryHelper::memoryLog(__METHOD__ . '::' . __LINE__ . ' ' . 'processed');
    }

    /**
     * Insert rows into table
     *
     * @param $rows Rows
     *
     * @return void
     */
    private function insertRows($rows)
    {
        if ($rows->isEmpty()) return;

        try {
            $this->seedTable->insertRows($rows->rows);
        } catch (Exception $e) {
            $message = 'Rows of the file "' . $this->fileName . '" sheet "' . $this->sheetName . '" has failed to insert in table "' . $this->seedTable->getName() . '": ' . $e->getMessage();
            event(new Console($message, 'error'));

            throw(new Exception($message));
        }
    }

    /**
     * @param $table
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function updatePostgresSeqCounters($table)
    {
        if (!DB::connection()->getQueryGrammar() instanceof PostgresGrammar) {
            return;
        }

        foreach($this->getSequencesForTable($table) as $column => $sequence) {
            $result = DB::select("select setval('{$sequence}', max(\"{$column}\")) from {$table}");
        }
    }

    /**
     * @param string $table
     * @param string | string[] $columns
     * @return \Doctrine\DBAL\Schema\Sequence[]
     * @throws \Doctrine\DBAL\Exception
     */
    public static function getSequencesForTable(string $table)
    {
        $sequences = DB::table('information_schema.columns')
            ->select("table_name", "column_name", "column_default")
            ->whereRaw("table_name=? and column_default like ?", [$table, "nextval%"])
            ->get()
            ->mapWithKeys(function ($value, $key) {
                return [$value->column_name => Str::between($value->column_default, "'", "'")];
            });

        return $sequences;
    }
}