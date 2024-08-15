<?php


namespace bfinlay\SpreadsheetSeeder\Readers;


use bfinlay\SpreadsheetSeeder\Readers\Events\RowFinish;
use bfinlay\SpreadsheetSeeder\Readers\Events\RowStart;
use bfinlay\SpreadsheetSeeder\Readers\Types\EmptyCell;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RowImporter
{
    /**
     * @var string[]
     */
    protected $columnNames;

    /**
     * @var array
     */
    protected $rowArray;

    /**
     * @var bool $nullRow
     */
    protected $nullRow = true;

    /**
     * @var boolean
     */
    protected $isValid;

    /**
     * @var SpreadsheetSeederSettings
     */
    protected $settings;

    /**
     * @param string[] $columnNames A sparse array mapping column index => column name
     */
    public function __construct($columnNames)
    {
        $this->columnNames = $columnNames;
        $this->settings = resolve(SpreadsheetSeederSettings::class);
    }

    public function import(array $row)
    {
        event(new RowStart($row, $this->columnNames));
        $this->rowArray = [];

        foreach($row as $columnIndex => $value) {
            if (isset($this->columnNames[$columnIndex])) {
                if (!$this->isEmptyCell($value)) $this->nullRow = false;
                $columnName = $this->columnNames[$columnIndex];
                $this->rowArray[$columnName] = $this->transformValue($columnName, $value);
            }
        }

        if ($this->isValid()) {
            $this->addTimestamps();
            $this->addColumns();
        }

        event(new RowFinish($this->rowArray, $this->columnNames));
        return $this->rowArray;
    }

    /**
     * Returns true if all cells meet one of these conditions:
     *  1. Cell is primary key column
     *  2. Cell is an empty cell
     *  3. Cell is an empty string
     *
     * @param $row
     * @return void
     */
//    public function shouldSkipEmptyRow($row)
//    {
//        $skipRow = false;
//        foreach($row as $columnIndex => $value) {
//            $skipRow = $skipRow &&
//                $this->isEmptyCell($value) ||
//                $columnIndex = // there is no way for the reader to know if a column is primary key
//        }
//    }

    public function isEmptyCell($value)
    {
        return is_null($value) ||
            $value instanceof EmptyCell ||
            ($this->settings->emptyStringIsEmptyCell && $value == "");
    }

    public function isValid()
    {
        return !$this->nullRow && $this->validate();
    }

    protected function transformValue($columnName, $value)
    {
        $value = $this->runParsers($columnName, $value);
        $value = $this->defaultValue($columnName, $value);
        $value = $this->transformEmptyValue($value);
        $value = $this->encode($value);
        $value = $this->hash($columnName, $value);
        $value = $this->uuid($columnName, $value);

        return $value;
    }

    protected function defaultValue($columnName, $value)
    {
        return isset($this->settings->defaults[$columnName]) ? $this->settings->defaults[$columnName] : $value;
    }

    protected function transformEmptyValue($value)
    {
        if($value instanceof EmptyCell) return $value;
        if(is_null($value)) return new EmptyCell();

        if (!is_string($value)) return $value;

        if($this->settings->emptyStringIsEmptyCell && $value == "") return new EmptyCell();

        if( strtoupper($value) == 'NULL' ) return NULL;
        if( strtoupper($value) == 'FALSE' ) return FALSE;
        if( strtoupper($value) == 'TRUE' ) return TRUE;

        return $value;
    }

    protected function encode($value)
    {
        if( is_string($value) )
            $value = empty($this->settings->inputEncodings) ?
                mb_convert_encoding($value, $this->settings->outputEncoding) :
                mb_convert_encoding($value, $this->settings->outputEncoding, $this->settings->inputEncodings);
        return $value;
    }

    protected function hash($columnName, $value)
    {
        return in_array($columnName, $this->settings->hashable) ? Hash::make($value) : $value;
    }

    protected function uuid($columnName, $value)
    {
        if (!in_array($columnName, $this->settings->uuid)) return $value;

        return Str::isUuid($value) ? $value : Str::uuid();
    }

    protected function runParsers($columnName, $value)
    {
        return array_key_exists($columnName, $this->settings->parsers) && is_callable($this->settings->parsers[$columnName]) ?
            $this->settings->parsers[$columnName]($value) :
            $value;
    }

    /**
     * Add timestamp to the processed row
     *
     * @return void
     */
    protected function addTimestamps()
    {
        if( empty($this->settings->timestamps) ) return;

        $timestamp = date('Y-m-d H:i:s');

        $this->rowArray[ 'created_at' ] = $timestamp;
        $this->rowArray[ 'updated_at' ] = $timestamp;
    }

    protected function addColumns()
    {
        foreach ($this->settings->addColumns as $column) {
            if (!isset($this->rowArray[$column])) $this->rowArray[$column] = $this->transformValue($column, null);
        }
    }

    protected function validate()
    {
        if( empty($this->settings->validate)) return true;

        $validator = Validator::make($this->rowArray, $this->settings->validate);

        if( $validator->fails() ) return FALSE;

        return TRUE;
    }
}