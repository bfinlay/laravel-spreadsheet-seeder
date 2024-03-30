<?php


namespace bfinlay\SpreadsheetSeeder\Events;


use Illuminate\Foundation\Events\Dispatchable;

class Console
{
    use Dispatchable;

    /**
     * @var string $message
     */
    public $message;

    /**
     * @var false|string $level
     */
    public $level;

    /**
     * Console constructor.
     * @param string $message
     * @param false|string $level
     */
    public function __construct($message, $level = FALSE)
    {
        $this->message = $message;
        $this->level = $level;
    }
}