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
        self::registerUnwrapMacro();
    }

    public static function registerBeforeLastMacro() {
        if (method_exists(Str::class, "beforeLast")) return;

        /**
         * Get the portion of a string before the last occurrence of a given value.
         *
         * @param  string  $subject
         * @param  string  $search
         * @return string
         */
        Str::macro('beforeLast', function($subject, $search) {
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

    public static function registerBetweenMacro() {
        if (method_exists(Str::class, "between")) return;

        /**
         * Get the portion of a string between two given values.
         *
         * @param  string  $subject
         * @param  string  $from
         * @param  string  $to
         * @return string
         */
        Str::macro('between', function($subject, $from, $to) {
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
         * @param  mixed  $value
         * @return bool
         */
        Str::macro('isUuid', function($value) {
            if (!is_string($value)) {
                return false;
            }

            return preg_match('/^[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}$/D', $value) > 0;
        });
    }

    public static function registerUnwrapMacro()
    {
        if (method_exists(Str::class, "unwrap")) return;

        /**
         * Unwrap the string with the given strings.
         *
         * @param  string  $value
         * @param  string  $before
         * @param  string|null  $after
         * @return string
         */
        Str::macro('unwrap', function($value, $before, $after = null) {
            if (static::startsWith($value, $before)) {
                $value = static::substr($value, static::length($before));
            }

            if (static::endsWith($value, $after ??= $before)) {
                $value = static::substr($value, 0, -static::length($after));
            }

            return $value;
        });
    }
}