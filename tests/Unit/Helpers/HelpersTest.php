<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Wotz\FilamentSettings\Models\Setting;

uses(RefreshDatabase::class);

it('returns the setting', function () {
    $setting = Setting::factory([
        'key' => fake()->word . '.' . fake()->word,
        'value' => fake()->text,
    ])->create();

    expect(setting($setting->key))
        ->toBeString($setting->value);
});

it('returns the default if setting is null', function () {
    expect(setting('not-a-setting', 'default-value'))
        ->toBeString('default-value');
});
