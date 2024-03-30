<?php


namespace bfinlay\SpreadsheetSeeder\Readers\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Symfony\Component\Finder\SplFileInfo;

class FileStart
{
    use Dispatchable;

    /**
     * @var SplFileInfo
     */
    public $file;

    /**
     * FileStart constructor.
     * @param $file SplFileInfo
     */
    public function __construct($file)
    {
        $this->file = $file;
    }
}