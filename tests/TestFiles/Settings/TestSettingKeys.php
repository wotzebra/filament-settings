<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Wotz\FilamentSettings\Settings\SettingsInterface;

class TestSettingKeys implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            TextInput::make('site.title'),

            TextInput::make('mail.from.name'),

            TextInput::make('mail.from.address'),

            TextInput::make('shop.mail.from.name'),

            TextInput::make('undotted'),

            CheckboxList::make('undotted_list')
                ->options([
                    'a' => 'A',
                    'b' => 'B',
                ]),
        ];
    }
}
