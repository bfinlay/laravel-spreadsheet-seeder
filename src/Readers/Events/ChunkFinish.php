<?php


namespace bfinlay\SpreadsheetSeeder\Readers\Events;


use bfinlay\SpreadsheetSeeder\Readers\Rows;
use Illuminate\Foundation\Events\Dispatchable;

class ChunkFinish extends ChunkStart
{
    use Dispatchable;
}