<?php

namespace bfinlay\SpreadsheetSeeder\Writers\Database;

use bfinlay\SpreadsheetSeeder\Readers\Events\FileFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\FileStart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

/**
 *   This class provides a workaround to prevent Laravel Framework memory leaks per https://github.com/laravel/framework/issues/30012.
 *   These leaks are caused by Laravel query logging.   Calling DB::connection()->disableQueryLog() resolves many situations.
 *   However, some packages ignore this setting, including:
 *      1. spatie/ignition, which was included with laravel versions between 6.x and 9.x
 *      2. telescope
 *
 *   One technique for disabling query logging for these packages is to disable the event dispatcher for the db connection.
 *   However, disabling the event dispatcher is not compatible with the implementation of RefreshDatabase, which itself
 *   disables the querylog before beginning a transaction, and then assumes that it can restore the previous dispatcher
 *   without checking to see if it was enabled.
 *
 *   The workaround for RefreshDatabase is to disable the event dispatcher at the start of a file, and enable it again
 *   at the end of the file.
 *
 *
 */
class QueryLogMemoryLeakWorkaroundProvider
{
    protected $dbConnectionEventDispatcher;

    public function boot() {
        DB::connection()->disableQueryLog();
        $this->dbConnectionEventDispatcher = DB::connection()->getEventDispatcher();

        Event::listen(FileStart::class, [$this, 'disableEventDispatcher']);
        Event::listen(FileFinish::class, [$this, 'enableEventDispatcher']);
    }

    public function disableEventDispatcher()
    {
        DB::connection()->unsetEventDispatcher();
    }

    public function enableEventDispatcher()
    {
        DB::connection()->setEventDispatcher($this->dbConnectionEventDispatcher);
    }

}