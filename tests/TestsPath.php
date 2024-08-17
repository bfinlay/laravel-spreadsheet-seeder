<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

class TestsPath
{
    public static function relative($path = null)
    {
        // /.../laravel-excel-seeder/vendor/orchestra/testbench-core/laravel
        $up = '../../../../tests';
        return $up . ($path ? '/' . $path : '');
    }

    public static function forSettings($path = null)
    {
        return self::relative($path);
    }

    public static function largeRowsForSettings($path = null)
    {
        $p = ($path ? '/' . $path : '');
        return self::forSettings('../vendor/bfinlay/laravel-excel-seeder-test-data/LargeNumberOfRowsTest' . $p);
    }

    public static function absolute($path = null)
    {
        return $path ? __DIR__ . '/' . $path : __DIR__;
    }

    public static function forFinder($path = null)
    {
        return self::absolute($path);
    }
}