<?php


namespace bfinlay\SpreadsheetSeeder\Readers;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RowImporter
{
    /**
     * @var string[]
     */
    private $columnNames;

    /**
     * @var array
     */
    private $rowArray;

    /**
     * @var bool $nullRow
     */
    private $nullRow = true;

    /**
     * @var boolean
     */
    private $isValid;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

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
        $this->rowArray = [];

        foreach($row as $columnIndex => $value) {
            if (isset($this->columnNames[$columnIndex])) {
                if (!is_null($value)) $this->nullRow = false;
                $columnName = $this->columnNames[$columnIndex];
                $this->rowArray[$columnName] = $this->transformValue($columnName, $value);
            }
        }

        if ($this->isValid()) {
            $this->addTimestamps();
        }

        return $this->rowArray;
    }

    public function isValid() {
        return !$this->nullRow && $this->validate();
    }

    private function transformValue($columnName, $value) {
        $value = $this->runParsers($columnName, $value);
        $value = $this->defaultValue($columnName, $value);
        $value = $this->transformEmptyValue($value);
        $value = $this->encode($value);
        $value = $this->hash($columnName, $value);

        return $value;
    }

    private function defaultValue($columnName, $value) {
        return isset($this->settings->defaults[$columnName]) ? $this->settings->defaults[$columnName] : $value;
    }

    private function transformEmptyValue($value) {
        if( strtoupper($value) == 'NULL' ) return NULL;
        if( strtoupper($value) == 'FALSE' ) return FALSE;
        if( strtoupper($value) == 'TRUE' ) return TRUE;
        return $value;
    }

    private function encode($value) {
        if( is_string($value) )
            $value = empty($this->settings->inputEncodings) ?
                mb_convert_encoding($value, $this->settings->outputEncoding) :
                mb_convert_encoding($value, $this->settings->outputEncoding, $this->settings->inputEncodings);
        return $value;
    }

    private function hash($columnName, $value) {
        return in_array($columnName, $this->settings->hashable) ? Hash::make($value) : $value;
    }

    private function runParsers($columnName, $value) {
        return array_key_exists($columnName, $this->settings->parsers) && is_callable($this->settings->parsers[$columnName]) ?
            $this->settings->parsers[$columnName]($value) :
            $value;
    }

    /**
     * Add timestamp to the processed row
     *
     * @return void
     */
    private function addTimestamps()
    {
        if( empty($this->settings->timestamps) ) return;

        $timestamp = date('Y-m-d H:i:s');

        $this->rowArray[ 'created_at' ] = $timestamp;
        $this->rowArray[ 'updated_at' ] = $timestamp;
    }

    private function validate() {
        if( empty($this->settings->validate)) return true;

        $validator = Validator::make($this->rowArray, $this->settings->validate);

        if( $validator->fails() ) return FALSE;

        return TRUE;
    }
}