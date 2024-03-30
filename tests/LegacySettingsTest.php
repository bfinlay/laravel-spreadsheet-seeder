<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\LegacySettingsTest\LegacyDateTimeSeeder;

class LegacySettingsTest extends DateTimeTest
{
    protected $dateTimeSeeder = LegacyDateTimeSeeder::class;
}