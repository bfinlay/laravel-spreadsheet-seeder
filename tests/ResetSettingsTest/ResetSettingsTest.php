<?php

namespace bfinlay\SpreadsheetSeeder\Tests\ResetSettingsTest;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\TestCase;
use Illuminate\Support\Facades\App;

class ResetSettingsTest extends TestCase
{
    public function settings() : SpreadsheetSeederSettings {
        return App::make(SpreadsheetSeederSettings::class);
    }

    public function test_forget_settings() {
        $settings =  $this->settings();
        $this->assertTrue($settings->header);
        $settings->header = false;
        $this->assertFalse($settings->header);

        $settings2 = $this->settings();
        $this->assertFalse($settings2->header);

        App::forgetInstance(SpreadsheetSeederSettings::class);
        $settings3 = $this->settings();
        $this->assertTrue($settings3->header);
        $settings3->header = false;
        $this->assertFalse($settings3->header);

        $settings4 = $this->settings();
        $this->assertFalse($settings4->header);
    }
}