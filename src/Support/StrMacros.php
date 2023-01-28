<?php

namespace bfinlay\SpreadsheetSeeder\Support;

use Illuminate\Support\Str;

class StrMacros
{
    public static function registerBeforeLastMacro() {
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
        Str::macro('between', function($subject, $from, $to) {
            if ($from === '' || $to === '') {
                return $subject;
            }

            return static::beforeLast(static::after($subject, $from), $to);
        });
    }
}