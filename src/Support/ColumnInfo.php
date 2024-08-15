<?php

namespace bfinlay\SpreadsheetSeeder\Support;

use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Str;

class ColumnInfo
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
        if ($column instanceof Column) $this->fromDoctrine($column);
        if (is_array($column)) $this->fromLaravel($column);
    }

    public function fromDoctrine(Column $column)
    {
        $this->name = $column->getName();
        $this->type_name = $this->type = $column->getType()->getName();
        $this->nullable = ! $column->getNotnull();
        $this->default = $column->getDefault();
        $this->autoIncrement = $column->getAutoincrement();
        $this->comment = $column->getComment();
    }

    public function fromLaravel($column)
    {
        $this->name = $column["name"];
        $this->type_name = $column["type_name"] ?? null;
        $this->type = $column["type"] ?? null;
        $this->collation = $column["collation"] ?? null;
        $this->nullable = $column["nullable"] ?? null;
        $this->default =  $column["default"] ?? null;
        $this->autoIncrement = $column["auto_increment"] ?? null;
        $this->comment = $column["comment"] ?? null;
        $this->generation = $column["generation"] ?? null;

        if (is_string($this->default)) {
            $this->default = Str::between($this->default, "'", "'");
//            $this->default = Str::replaceLast('::bpchar', '', $this->default);
//            $this->default = Str::unwrap($this->default, "'");
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type_name;
    }

    public function getNullable()
    {
        return $this->nullable;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    public function getComment()
    {
        return $this->comment;
    }
}