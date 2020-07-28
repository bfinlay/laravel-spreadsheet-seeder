<?php


namespace bfinlay\SpreadsheetSeeder;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SourceRow
{
    /**
     * @var Row
     */
    private $sheetRow;

    /**
     * @var string[]
     */
    private $columnNames;

    /**
     * @var array
     */
    private $rowArray;

    /**
     * @var array
     */
    private $rawRowArray;

    /**
     * @var boolean
     */
    private $isValid = false;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * SourceRow constructor.
     * @param Row $row
     * @param string[] $columnNames A sparse array mapping column index => column name
     */
    public function __construct(Row $row, $columnNames)
    {
        $this->sheetRow = $row;
        $this->columnNames = $columnNames;
        $this->settings = resolve(SpreadsheetSeederSettings::class);
        $this->makeRow();
    }

    public function toArray() {
        return $this->rowArray;
    }
    
    public function rawRow() {
        return $this->rawRowArray;
    }

    public function isValid() {
        return $this->isValid;
    }

    private function makeRow() {
        $nullRow = true;
        $cellIterator = $this->sheetRow->getCellIterator();
        $colIndex = 0;
        foreach($cellIterator as $cell) {
            if (isset($this->columnNames[$colIndex])) {
                $value = $cell->getCalculatedValue();
                if (!is_null($value)) $nullRow = false;
                $columnName = $this->columnNames[$colIndex];
                $this->rawRowArray[$colIndex] = $value;
                $this->rowArray[$columnName] = $this->transformValue($columnName, $value);
            }
            else {
                $this->rawRowArray[$colIndex] = "";
            }
            $colIndex++;
        }
        if ($nullRow) {
            $this->isValid = false;
        }
        else {
            $this->addTimestamps();
            $this->isValid = $this->validate();
        }
    }

    private function transformValue($columnName, $value) {
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
        if( is_string($value) ) $value = mb_convert_encoding($value, $this->settings->outputEncoding, $this->settings->inputEncodings);
        return $value;
    }

    private function hash($columnName, $value) {
        return in_array($columnName, $this->settings->hashable) ? Hash::make($value) : $value;
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