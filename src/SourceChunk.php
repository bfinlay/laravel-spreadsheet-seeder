<?php


namespace bfinlay\SpreadsheetSeeder;

use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SourceChunk implements \Iterator
{
    /**
     * @var Worksheet
     */
    private $worksheet;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var \Iterator
     */
    private $rowIterator;

    /**
     * @var int
     */
    private $startRow;

    /**
     * @var SourceHeader
     */
    private $header;

    /**
     * SourceSheet constructor.
     */
    public function __construct(Worksheet $worksheet, SourceHeader $header, $startRow)
    {
        $this->worksheet = $worksheet;
        $this->settings = resolve(SpreadsheetSeederSettings::class);
        $this->tableName = $this->settings->tablename;
        $this->startRow = $startRow;
        $this->rowIterator = $this->worksheet->getRowIterator($this->startRow);
        $this->header = $header;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return new SourceRow($this->rowIterator->current(), $this->header->toArray());
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->rowIterator->next();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->rowIterator->key();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->rowIterator->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->rowIterator = $this->worksheet->getRowIterator($this->startRow);
    }

}