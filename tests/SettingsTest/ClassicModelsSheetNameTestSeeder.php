<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class ClassicModelsSheetNameTestSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        $set->file = TestsPath::forSettings('../examples/classicmodels.xlsx');
//        $this->textOutput = false;

        if ($this->sheetName == "employees")
        {
            $set->parsers = [
                "id" => function($value) {
                    return $value + 5000;
                },
                "superior_id" => function($value) {
                    return is_int($value) ? $value + 5000 : "null";
                }
            ];
        }

        if ($this->sheetName == "customers")
        {
            $set->parsers = [
                "sales_rep_id" => function($value) {
                    return is_int($value) ? $value + 5000 : "null";
                },
            ];
        }


//        static $productId = 10000;
//        if ($this->sheetName == "products")
//        {
//            $set->defaults = ["id" => $productId++];
//        }
    }
}