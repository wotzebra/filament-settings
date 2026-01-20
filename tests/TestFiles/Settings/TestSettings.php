<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\TextInput;

class TestSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            TextInput::make('site.name')->rules([
                new SettingMustBeFilledIn,
            ]),
            TextInput::make('site.url'),
        ];
    }
}
