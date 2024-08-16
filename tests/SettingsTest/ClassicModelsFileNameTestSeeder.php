<?php


namespace bfinlay\SpreadsheetSeeder\Tests\SettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestsPath;

class ClassicModelsFileNameTestSeeder extends SpreadsheetSeeder
{
    public function settings(SpreadsheetSeederSettings $set)
    {
        // path is relative to base_path which is laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $set->file = [
            TestsPath::forSettings('../examples/classicmodels.xlsx'),
            TestsPath::forSettings('../examples/users.csv'),
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