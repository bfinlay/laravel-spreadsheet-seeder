<?php


namespace bfinlay\SpreadsheetSeeder\Writers\Text;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Writers\Text\Yaml\YamlFormatter;
use Symfony\Component\Finder\SplFileInfo;

class TextOutputFileRepository
{
    /**
     * @var string
     */
    protected $sourcePathname;

    /**
     * @var String
     */
    protected $sheet;

    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * @var string
     */
    protected $_pathName;

    /**
     * @var string
     */
    protected $extension;


    /**
     * OldTextWriter constructor.
     * @param string $sourcePathname Path including filename of source input file, used to create path with same name as file
     */
    public function __construct(string $sourcePathname, string $outputExtension)
    {
        $this->sourcePathname = $sourcePathname;
        $this->extension = ltrim($outputExtension, ".*");
        $this->createPath();
    }

    public function openSheet($sheetName)
    {
        if (! $this->isSheetActive( $sheetName )) {
            $this->closeSheet();
            $this->sheet = $sheetName;
        }
    }

    public function write($string)
    {
        return $this->file()->fwrite($string);
    }


    public function closeSheet()
    {
        unset($this->sheet);
        if (isset($this->file)) $this->file->fflush();
        $this->file = null;
    }

    protected function isSheetActive($name)
    {
        return isset($this->sheet) && $this->sheet == $name;
    }

    protected function pathName()
    {
        if (isset($this->_pathName)) return $this->_pathName;

        $this->_pathName = '';
        $path_parts = pathinfo($this->sourcePathname);
        if (strlen($path_parts['dirname']) > 0) $this->_pathName = $path_parts['dirname'] . '/';
        $this->_pathName = $this->_pathName . $path_parts['filename'];
        return $this->_pathName;
    }

    protected function createPath()
    {
        $mkdirResult = false;
        if (!(is_dir($this->pathName()))) {
            $mkdirResult = mkdir($this->pathName(), 0777, true);
        }

        $glob = $this->pathName() . "/*.$this->extension";
        array_map('unlink', glob($glob));
    }

    protected function filename()
    {
        return $this->pathName() . '/' . $this->sheet . '.' . $this->extension;
    }

    protected function file()
    {
        return $this->file ?? $this->file = new \SplFileObject($this->filename(), 'w');
    }
}