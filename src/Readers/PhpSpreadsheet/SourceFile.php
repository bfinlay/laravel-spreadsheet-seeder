<?php


namespace bfinlay\SpreadsheetSeeder\Readers\PhpSpreadsheet;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use SplFileInfo;

class SourceFile implements \Iterator
{
    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var BaseReader
     */
    private $reader;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * @var string[]
     */
    private $sheetNames;

    /**
     * @var int
     */
    private $sheetIndex = 0;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
        $this->settings = resolve(SpreadsheetSeederSettings::class);

        if (!$this->shouldSkip()) $this->getSheetNames();
    }

    /**
     * Returns true if the file should be skipped.   Currently this only checks for a leading "~" character in the
     * filename, which indicates that the file is an Excel temporary file.
     *
     * @return bool
     */
    public function shouldSkip() {
        if (substr($this->file->getFilename(), 0, 1) === "~" ) return true;

        return false;
    }

    public function getSheetNames() {
        if (!isset($this->sheetNames)) {
            $filename = $this->file->getPathname();
            $this->fileType = IOFactory::identify($filename);
            $this->reader = IOFactory::createReader($this->fileType);
            if ($this->fileType == "Csv" && !empty($this->settings->delimiter)) {
                $this->reader->setDelimiter($this->settings->delimiter);
            }

            // fastest
            if (method_exists($this->reader, "listWorksheetNames")) {
                $this->sheetNames = $this->reader->listWorksheetNames($filename);
            }
            // slower
            else if (method_exists($this->reader, "listWorksheetInfo")) {
                /**
                 * worksheet info array:
                 * fake_names_100k.xlsx
                 * - worksheetName = "fake_names_100k"
                 * - lastColumnLetter = "AS"
                 * - lastCoumnIndex = 44
                 * - totalRows = "100001"
                 * - totalColumns = 45
                 */
                $this->sheetNames = [];
                $worksheetInfo = $this->reader->listWorksheetInfo($filename);
                foreach ($worksheetInfo as $info) {
                    $this->sheetNames[] = $info['worksheetName'];
                }
            }
            // slowest
            else {
                $this->reader->setReadFilter(new SourceFileReadFilter());
                $workbook = $this->reader->load($filename);
                $this->sheetNames = $workbook->getSheetNames();
            }
        }
        return $this->sheetNames;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $sheetName = $this->sheetNames[$this->sheetIndex];

        $sourceSheet = new SourceSheet($this->file->getPathname(), $this->fileType, $sheetName);
        if (count($this->sheetNames) == 1) {
            $sourceSheet->setSingleSheet();
        }
        return $sourceSheet;
    }

    private function shouldSkipSheet($sheetName) {
        return
            $this->settings->skipper == substr($sheetName, 0, strlen($this->settings->skipper)) ||
            (
                is_array($this->settings->worksheets) &&
                count($this->settings->worksheets) > 0 &&
                ! in_array($sheetName, $this->settings->worksheets)
            );
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->sheetIndex++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->sheetIndex;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        while (
            $this->sheetIndex < count($this->sheetNames) &&
            $this->shouldSkipSheet($this->sheetNames[$this->sheetIndex])
        )
        {
            $this->sheetIndex++;
        }

        return $this->sheetIndex < count($this->sheetNames);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        return $this->sheetIndex = 0;
    }

    public function getFilename() {
        return $this->file->getFilename();
    }
    
    public function getPathname() {
        return $this->file->getPathname();
    }

    public function getDelimiter() {
        return $this->reader->getDelimiter();
    }
}