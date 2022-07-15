<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Text\Yaml;


use bfinlay\SpreadsheetSeeder\Writers\Text\TextTableFormatterInterface;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;

class YamlFormatter implements TextTableFormatterInterface
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string[]
     */
    private $header;

    /**
     * @var int
     */
    private $rowCount = 0;

    public function header($header) : string
    {
        $out = "";

        $this->header = $header;
        $out .= "Header:\n";
        foreach($this->header as $header)
        {
            $out .= "  - " . $this->quote($header) . "\n";
        }
        $out .= "Rows:\n";
        return $out;
    }

    public function tableName($tableName) : string
    {
        $this->tableName = $tableName;
        return "Table: " . $this->tableName . "\n";
    }

    protected function columnName($index)
    {
        if (isset($this->header[$index])) return $this->quote($this->header[$index]);
        return $index;
    }

    public function rows($rows) : string
    {
        $out = "";
        foreach ($rows as $row) {
            $this->rowCount++;
            $out .= "  " . $this->rowCount . ":\n";
            foreach ($row as $index => $value) {
                $out .= "    " . $this->columnName($index) . ": " . $this->quote($value) . "\n";
            }
        }

        return $out;
    }

    public function footer() : string
    {
        $out = 'RowCount: ' . $this->rowCount . "\n";
        $this->rowCount = 0;
        return $out;
    }

    protected function quote($string) : string
    {
        if (is_null($string)) return "";
        $special = [':', '{', '}', '[', ']', ',', '&', '*', '#', '?', '|', '-', '<', ">", '=', '!', '%', '@', "\\"];
        if (Str::contains($string, $special)) {
            return "'" . str_replace("'", "''", $string) . "'";
        }
        if ($string == 'Yes') return "'Yes'";
        if ($string == 'No') return "'No'";
        return $string;
    }
}