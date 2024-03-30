<?php

namespace bfinlay\SpreadsheetSeeder\Writers\Text;

interface TextTableFormatterInterface
{
    public function tableName($tableName) : string;
    public function header($header) : string;
    public function rows($rows) : string;
    public function footer() : string;
}