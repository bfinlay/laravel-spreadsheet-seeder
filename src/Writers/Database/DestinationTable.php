<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Database;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DestinationTable
{
    private $name;

    /**
     * @var bool
     */
    private $exists;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * @var string[]
     */
    private $columns;

    /**
     * @var array
     */
    private $rows;

    /**
     *
     * See methods in vendor/doctrine/dbal/lib/Doctrine/DBAL/Schema/Column.php
     *
     * @var Column
     */
    private $doctrineColumns;

    public function __construct($name)
    {
        $this->name = $name;
        $this->settings = resolve(SpreadsheetSeederSettings::class);

        if ($this->exists() && $this->settings->truncate) $this->truncate();
    }

    public function getName() {
        return $this->name;
    }

    public function exists() {
        if (isset($this->exists)) return $this->exists;
        $this->exists =  self::tableExists( $this->name );

        return $this->exists;
    }

    public static function tableExists($name)
    {
        return DB::getSchemaBuilder()->hasTable( $name );
    }

    public function truncate() {
        $ignoreForeign = $this->settings->truncateIgnoreForeign;

        if( $ignoreForeign ) Schema::disableForeignKeyConstraints();

        DB::table( $this->name )->truncate();

        if( $ignoreForeign ) Schema::enableForeignKeyConstraints();
    }

    private function loadColumns() {
        if (! isset($this->columns)) {
            $this->columns = DB::getSchemaBuilder()->getColumnListing( $this->name );
            $doctrineColumns = DB::getSchemaBuilder()->getConnection()->getDoctrineSchemaManager()->listTableColumns($this->name);

            /*
             * Doctrine DBAL 2.11.x-dev does not return the column name as an index in the case of mixed case (or uppercase?) column names
             * In sqlite in-memory database, DBAL->listTableColumns() uses the lowercase version of the column name as a column index
             * In postgres, it uses the lowercase version of the mixed-case column name and places '"' around the name (for the mixed-case name only)
             * The solution here is to iterate through the columns to retrieve the column name and use that to build a new index.
             */
            $this->doctrineColumns = [];
            foreach ($doctrineColumns as $column) {
                $this->doctrineColumns[$column->getName()] = $column;
            }
        }
    }

    public function getColumns() {
        $this->loadColumns();

        return $this->columns;
    }

    public function getColumnType($name) {
        $this->loadColumns();

        return $this->doctrineColumns[$name]->getType()->getName();
    }

    public function columnExists($columnName) {
        $this->loadColumns();

        return in_array($columnName, $this->columns);
    }

    private function transformNullCellValue($columnName, $value) {
        if (is_null($value)) {
            $value = $this->defaultValue($columnName);
        }
        return $value;
    }

    private function transformDateCellValue($columnName, $value) {
        if (is_null($value)) {
            return null;
        }

        if ($this->isDateColumn($columnName)) {

            if (is_numeric($value)) {
                if (in_array($columnName, $this->settings->unixTimestamps))
                        $date = Carbon::parse($value);
                else
                        $date = Carbon::parse(Date::excelToDateTimeObject($value));
            }
            else {
                if (isset($this->settings->dateFormats[$columnName])) {
                    $date = Carbon::createFromFormat($this->settings->dateFormats[$columnName], $value);
                }
                else {
                    $date = Carbon::parse($value);
                }
            }

            return $date->format('Y-m-d H:i:s.u');
        }
        return $value;
    }

    private function checkRows($rows) {
        foreach ($rows as $row) {
            $tableRow = [];
            foreach ($row as $column => $value) {
                if ($this->columnExists($column)) {
                    $tableRow[$column] = $this->transformNullCellValue($column, $value);
                    $tableRow[$column] = $this->transformDateCellValue($column, $tableRow[$column]);
                }
            }
            $this->rows[] = $tableRow;
        }
    }

    public function insertRows($rows) {
        if( empty($rows) ) return;

        $this->checkRows($rows);

        $offset = 0;
        while ($offset < count($this->rows)) {
            $batchRows = array_slice($this->rows, $offset, $this->settings->batchInsertSize);

            DB::table($this->name)->insert($batchRows);

            $offset += $this->settings->batchInsertSize;
        }
        $this->rows = [];
    }

    private function isDateColumn($column) {
        $this->loadColumns();

        $c = $this->doctrineColumns[$column];

        // if column is date or time type return
        $doctrineDateValues = ['date', 'date_immutable', 'datetime', 'datetime_immutable', 'datetimez', 'datetimez_immutable', 'time', 'time_immutable', 'dateinterval'];
        return in_array($c->getType()->getName(), $doctrineDateValues);
    }

    public function defaultValue($column) {
        $this->loadColumns();

        $c = $this->doctrineColumns[$column];

        // return default value for column if set
        if (! is_null($c->getDefault())) return $c->getDefault();

        // if column is auto-incrementing return null and let database set the value
        if ($c->getAutoincrement()) return null;

        // if column accepts null values, return null
        if (! $c->getNotnull()) return null;

        // if column is numeric, return 0
        $doctrineNumericValues = ['smallint', 'integer', 'bigint', 'decimal', 'float'];
        if (in_array($c->getType()->getName(), $doctrineNumericValues)) return 0;

        // if column is date or time type return
        if ($this->isDateColumn($column)) {
            if ($this->settings->timestamps) return date('Y-m-d H:i:s.u');
            else return 0;
        }

        // if column is boolean return false
        if ($c->getType()->getName() == "boolean") return false;

        // else return empty string
        return "";
    }
}
