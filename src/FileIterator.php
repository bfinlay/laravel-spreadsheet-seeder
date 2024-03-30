<?php


namespace bfinlay\SpreadsheetSeeder;

/**
 * Consider enhancing with FlySystem integration
 */
class FileIterator extends \AppendIterator
{
    private $count;

    /**
     * @var SpreadsheetSeederSettings
     */
    private $settings;

    /**
     * FileIterator constructor.
     * @param SpreadsheetSeederSettings $settings
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings = resolve(SpreadsheetSeederSettings::class);

        $flags = \FilesystemIterator::KEY_AS_FILENAME;

        $globs = $this->settings->file;
        if (! is_array($globs)) {
            $globs = [$globs];
        }

        foreach ($globs as $glob) {
            if (is_dir($glob)) {
                $glob = dirname($glob) . "/*." . $this->settings->extension;
            }

            $it = new \GlobIterator(base_path() . $glob, $flags);
            $this->append($it);
            $this->count += $it->count();
        }
    }

    public function valid()
    {
        while (
            parent::valid() &&
            $this->shouldSkip()
        )
        {
            $this->next();
        }

        return parent::valid();
    }

    /**
     * Returns true if the file should be skipped.   Currently this only checks for a leading "~" character in the
     * filename, which indicates that the file is an Excel temporary file.
     *
     * @return bool
     */
    public function shouldSkip() {
        if (substr(parent::current()->getFilename(), 0, 1) === "~" ) return true;

        return false;
    }

    public function count()
    {
        return $this->count;
    }

    public function hasResults()
    {
        return $this->count() > 0;
    }
}
