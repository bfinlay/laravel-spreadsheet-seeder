<?php

namespace bfinlay\SpreadsheetSeeder\Writers\Text\Yaml;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Writers\Text\TextOutputWriter;
use Illuminate\Support\Collection;

class YamlWriter
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
    public function __construct(SpreadsheetSeederSettings $settings, YamlFormatter $yamlFormatter)
    {
        $this->settings = $settings;
        $this->writer = new TextOutputWriter("yaml", $yamlFormatter);
    }

    public function boot()
    {
        if (!$this->settings->textOutput()->contains('yaml')) return;

        $this->writer->boot();
    }
}