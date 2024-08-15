<?php


namespace bfinlay\SpreadsheetSeeder;


class SeederHelper
{
    public static $memoryLogEnabled = false;

    public static function getHumanReadableSize(int $sizeInBytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($sizeInBytes == 0) {
            return '0 '.$units[1];
        }

        for ($i = 0; $sizeInBytes > 1024; $i++) {
            $sizeInBytes /= 1024;
        }

        return round($sizeInBytes, 2).' '.$units[$i];
    }

    public static function getHumanReadableTime(float $timeInSeconds): string
    {
        $units = ['s', 'm', 'h', 'days'];
        $divisor = [60, 60, 24];

        if ($timeInSeconds == 0) {
            return '0 '.$units[0];
        }

        for ($i = 0; $timeInSeconds > $divisor[$i]; $i++) {
            $timeInSeconds /= $divisor[$i];
        }

        return round($timeInSeconds, 2).' '.$units[$i];
    }

    public static function memoryLog($message = '') {
        if (!self::$memoryLogEnabled) return;

        static $timer = null;

        $elapsed_time = isset($timer) ? microtime(true) - $timer : 0;
        error_log($message . ' ' . self::getHumanReadableSize(memory_get_usage()) . ' ' . self::getHumanReadableTime($elapsed_time));
        $timer = microtime(true);
    }
}