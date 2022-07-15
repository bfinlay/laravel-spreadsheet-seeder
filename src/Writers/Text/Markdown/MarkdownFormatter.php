<?php

namespace bfinlay\SpreadsheetSeeder\Writers\Text\Markdown;

use bfinlay\SpreadsheetSeeder\Writers\Text\TextTableFormatter;

class MarkdownFormatter extends TextTableFormatter
{
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
     * example:
     * alternate - outside left, right = '|', column = '|'
     *  | Column 1 | Column 2 | Column 3 |
     */
    protected $headerOutsideLeftColumnSeparator = '|';
    protected $headerColumnSeparator = '|';
    protected $headerOutsideRightColumnSeparator = '|';

    /*
     * example:
     * alternate - underline = '-', column = '|', outside left and right = '|'
     *  |-----------|----------|----------| <- underline character (characters between column separators)
     */
    protected $headerUnderlineCharacter = '-';
    protected $headerUnderlineOutsideLeftColumnSeparator = '|';
    protected $headerUnderlineColumnSeparator = '|';
    protected $headerUnderlineOutsideRightColumnSeparator = '|';

    /*
     * example:
     *  |  Cell 1  |  Cell 2  |  Cell 3  |
     */
    protected $rowOutsideLeftColumnSeparator = '|';
    protected $rowColumnSeparator = '|';
    protected $rowOutsideRightColumnSeparator = '|';
}