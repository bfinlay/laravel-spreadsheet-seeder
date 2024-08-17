<?php

namespace bfinlay\SpreadsheetSeeder\Support;

use Illuminate\Support\Str;

class StrMacros
{
    public static function registerSupportMacros()
    {
        self::registerBeforeLastMacro();
        self::registerBetweenMacro();
        self::registerIsUuidMacro();
        self::registerTrimMacro();
    }

    public static function registerBeforeLastMacro()
    {
        if (method_exists(Str::class, "beforeLast")) return;

        /**
         * Get the portion of a string before the last occurrence of a given value.
         *
         * @param string $subject
         * @param string $search
         * @return string
         */
        Str::macro('beforeLast', function ($subject, $search) {
            if ($search === '') {
                return $subject;
            }

            $pos = mb_strrpos($subject, $search);

            if ($pos === false) {
                return $subject;
            }

            return static::substr($subject, 0, $pos);
        });
    }

    public static function registerBetweenMacro()
    {
        if (method_exists(Str::class, "between")) return;

        /**
         * Get the portion of a string between two given values.
         *
         * @param string $subject
         * @param string $from
         * @param string $to
         * @return string
         */
        Str::macro('between', function ($subject, $from, $to) {
            if ($from === '' || $to === '') {
                return $subject;
            }

            return static::beforeLast(static::after($subject, $from), $to);
        });
    }


    public static function registerIsUuidMacro()
    {
        if (method_exists(Str::class, "isUuid")) return;

        /**
         * Determine if a given value is a valid UUID.
         *
         * @param mixed $value
         * @return bool
         */
        Str::macro('isUuid', function ($value) {
            if (!is_string($value)) {
                return false;
            }

            return preg_match('/^[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}$/D', $value) > 0;
        });
    }

    public static function registerTrimMacro()
    {
        if (method_exists(Str::class, "trim")) return;

        /**
         * Remove all whitespace from both ends of a string.
         *
         * @param string $value
         * @param string|null $charlist
         * @return string
         */
        Str::macro('trim', function ($value, $charlist = null) {
            if ($charlist === null) {
                return preg_replace('~^[\s\x{FEFF}\x{200B}\x{200E}]+|[\s\x{FEFF}\x{200B}\x{200E}]+$~u', '', $value) ?? trim($value);
            }

            return trim($value, $charlist);
        });
    }
}