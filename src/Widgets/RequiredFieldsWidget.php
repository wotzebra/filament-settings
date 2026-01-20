<?php

namespace Wotz\FilamentSettings\Widgets;

use Filament\Widgets\Widget;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;

class RequiredFieldsWidget extends Widget
{
    protected string $view = 'filament-settings::widgets.required_fields_widget';

    protected int|string|array $columnSpan = 'full';

    protected $listeners = [
        'filament-settings::refresh-widget' => '$refresh',
    ];

    protected function getViewData(): array
    {
        return [
            'requiredKeys' => app(SettingTabRepository::class)->getRequiredKeys(),
        ];
    }
}
