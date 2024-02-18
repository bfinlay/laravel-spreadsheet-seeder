<?php


namespace bfinlay\SpreadsheetSeeder\Tests\Seeds\SettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;

class ClassicModelsFileNameTestSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = [
            '/../../../../examples/classicmodels.xlsx',
            '/../../../../examples/users.csv',
        ];
//        $this->textOutput = false;

        if ($this->fileName == "users.csv")
        {
            $set->parsers = [
                "id" => function($value) {
                    return $value + 5000;
                }
            ];
        }
    }
}