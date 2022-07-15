<?php

namespace bfinlay\SpreadsheetSeeder\Writers\Text\Markdown;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Writers\Text\TextOutputWriter;
use Illuminate\Support\Collection;

class MarkdownWriter
{
    /**
     * @var TextOutputWriter
     */
    protected $writer;

    /**
     * @var SpreadsheetSeederSettings
     */
    protected $settings;

    /**
     * @param SpreadsheetSeederSettings $settings
     */
    public function __construct(SpreadsheetSeederSettings $settings, MarkdownFormatter $markdownFormatter)
    {
        $this->settings = $settings;
        $this->writer = new TextOutputWriter("md", $markdownFormatter);
    }

    public function boot()
    {
        if (!$this->settings->textOutput()->contains('markdown')) return;

        $this->writer->boot();
    }
}