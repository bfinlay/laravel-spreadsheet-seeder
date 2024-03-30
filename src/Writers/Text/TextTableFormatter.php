<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Text;


use Exception;

class TextTableFormatter implements TextTableFormatterInterface
{
    /**
     * @var TextOutputFileRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string[]
     */
    protected $header;

    /**
     * @var array
     */
    protected $rows;

    /**
     * @var int
     */
    protected $rowCount = 0;

    /**
     * @var boolean
     */
    protected $isHeaderWritten = false;

    /**
     * @var int[]
     */
    protected $columnWidths;

    /**
     * @var int
     */
    protected $columnPadding = 2;

    /*
     * borders - terminology
     *
     *  | Column 1 | Column 2 | Column 3 | <- header
     *  |----------|----------|----------| <- header underline
     *  |  Cell 1  |  Cell 2  |  Cell 3  | <- row
     *   <- outside left column separator
     *               <- column separator
     *                         <- column separator
     *                                     <- outside right column separator
     */


    /*
     * examples:
     *
     * alternate - outside left, right = '|', column = '|'
     *  | Column 1 | Column 2 | Column 3 |
     *
     * alternates - outside left and right are '', column = '|':
     *    Column 1 | Column 2 | Column 3
     */
    protected $headerOutsideLeftColumnSeparator = '';
    protected $headerColumnSeparator = '|';
    protected $headerOutsideRightColumnSeparator = '';

    /*
     * examples:
     *
     * alternate - underline = '-', column = '|', outside left and right = '|'
     *  |-----------|----------|----------| <- underline character (characters between column separators)
     *
     * alternate - outside left and right = '', underline = '-', column = '|'
     *   -----------|----------|----------
     *
     * alternate - column = '+', outside left and right = '', underline = '-'
     *   -----------+----------+----------
     *
     * alternate - underline = '=', column = '|', outside left and right = ''
     *   ===========|==========|==========
     */
    protected $headerUnderlineCharacter = '-';
    protected $headerUnderlineOutsideLeftColumnSeparator = '';
    protected $headerUnderlineColumnSeparator = '+';
    protected $headerUnderlineOutsideRightColumnSeparator = '';

    /*
     * examples:
     *
     * alternate - outside left, right = '|', column = '|'
     *  |  Cell 1  |  Cell 2  |  Cell 3  |
     *
     * alternates - outside left and right are '', column = '|':
     *     Cell 1  |  Cell 2  |  Cell 3
     */
    protected $rowOutsideLeftColumnSeparator = '';
    protected $rowColumnSeparator = '|';
    protected $rowOutsideRightColumnSeparator = '';

    public function header($header) : string
    {
        $this->header = $header;
        $this->isHeaderWritten = false;
        return "";
    }
    public function tableHeader() : string
    {
        if ($this->isHeaderWritten) return "";
        $this->columnWidths();
        $this->isHeaderWritten = true;
        return $this->headerColumns();
    }

    public function rows($rows) :string
    {
        $out = "";
        $this->rows = $rows;
        $this->rowCount += count($rows);
        $out .= $this->tableHeader();
        $this->columnWidthsFromRows();
        $out .= $this->formatRowsAsTable();
        unset($this->rows);
        return $out;
    }

    public function footer() : string
    {
        return'(' . $this->rowCount . " rows)\n\n";
    }

    public function tableName($tableName) : string
    {
        $out = "";
        $this->tableName = $tableName;
        $out .= $this->tableName . "\n";
        $border = str_repeat('=', strlen($this->tableName));
        $out .= $border . "\n\n";
        return $out;
    }

    protected function headerColumns() : string
    {
        $out = "";

        foreach ($this->header as $index => $columnName) {
            $columnHeader = str_pad($columnName, $this->columnWidths[$index] + $this->columnPadding, " ", STR_PAD_BOTH);
            $columnSeparator = ($index > 0) ? $columnSeparator = $this->headerColumnSeparator : $this->headerOutsideLeftColumnSeparator;
            $out .= $columnSeparator . $columnHeader;
        }
        $out .= $this->headerOutsideRightColumnSeparator . "\n";

        foreach ($this->header as $index => $columnName) {
            $columnHeader = str_repeat($this->headerUnderlineCharacter, $this->columnWidths[$index] + $this->columnPadding);
            $columnSeparator = ($index > 0) ? $columnSeparator = $this->headerUnderlineColumnSeparator : $columnSeparator = $this->headerUnderlineOutsideLeftColumnSeparator;
            $out .= $columnSeparator . $columnHeader;
        }
        $out .= $this->headerUnderlineOutsideRightColumnSeparator . "\n";
        return $out;
    }

    protected function formatRowsAsTable() {
        $out = "";
        foreach ($this->rows as $row) {
            foreach ($row as $index => $value) {
                $valueCell = str_pad($value, $this->columnWidths[$index]);
                $columnSeparator = ($index > 0) ? $columnSeparator = $this->rowColumnSeparator : $columnSeparator = $this->rowOutsideLeftColumnSeparator;
                $out .= $columnSeparator . ' ' . $valueCell . ' ';
            }
            $out .= $this->rowOutsideRightColumnSeparator . "\n";
        }

        return $out;
    }

    protected function columnWidths() {
        foreach ($this->header as $index => $columnName) {
            $this->columnWidths[$index] = max(strlen($columnName),1);
        }

        $this->columnWidthsFromRows();
    }

    protected function columnWidthsFromRows() {
        if(is_null($this->rows)) return;
        foreach ($this->rows as $row) {
            foreach ($row as $index => $value) {
                if (!isset($this->columnWidths[$index]) || strlen($value) > $this->columnWidths[$index])
                    $this->columnWidths[$index] = strlen($value);
            }
        }
    }
}