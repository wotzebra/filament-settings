<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Filament\Forms\Components\TextInput;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Settings\SettingsInterface;

class TestSettingsWithPriority implements SettingsInterface
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

    public static function priority(): int
    {
        return 40;
    }
}
