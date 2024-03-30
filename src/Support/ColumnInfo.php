<?php

namespace bfinlay\SpreadsheetSeeder\Support;

use Doctrine\DBAL\Schema\Column;

class ColumnAdapter
{
    protected $name;
    protected $type_name;
    protected $type;
    protected $collation;
    protected $nullable;
    protected $default;
    protected $autoIncrement;
    protected $comment;
    protected $generation;
    
    public function __construct($column)
    {
        if ($column instanceof Column) $this->doctrineColumn = $column;
        if (is_array($column)) $this->laravelColumn = $column;
    }

    public static function fromDoctrine(Column $column)
    {

    }

    public function getDefault()
    {
        return
            $this->doctrineColumn?->getDefault() ??
            $this->laravelColumn["default"];
    }
}