<?php

namespace Wotz\FilamentSettings\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use UnitEnum;
use Wotz\FilamentSettings\Drivers\DriverInterface;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Widgets\RequiredFieldsWidget;

class Settings extends Page
{
    protected string $view = 'filament-settings::pages.settings';

    public string $focus = '';

    public ?array $data = [];

    protected $queryString = [
        'focus',
    ];

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return config('filament-settings.navigation.group', parent::getNavigationGroup());
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return config('filament-settings.navigation.icon', parent::getNavigationIcon());
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function submit()
    {
        /** @var DriverInterface $interface */
        $interface = app(DriverInterface::class);

        $form = $this->form;
        $state = $form->getState();

        // Store each field under its own name, the key it is loaded from.
        // Splitting the state on the first segment stored "mail.from.name" as
        // a "mail.from" blob and broke on keys without a dot.
        foreach ($form->getFlatFields() as $field) {
            $key = $field->getName();

            if (! Arr::has($state, $key)) {
                continue;
            }

            $value = Arr::get($state, $key);

            $interface->set($key, is_array($value) ? json_encode($value) : $value);
        }

        Notification::make()
            ->title(self::getNavigationLabel())
            ->body(__('filament-settings::admin.saved'))
            ->success()
            ->send();

        $this->dispatch('filament-settings::refresh-widget');
    }

    public function form(Schema $schema): Schema
    {
        /** @var SettingTabRepository $rep */
        $rep = app(SettingTabRepository::class);

        return $schema
            ->components([
                Tabs::make('Settings')
                    ->persistTabInQueryString()
                    ->tabs($rep->toTabsSchema($this->focus)),
            ])->statePath('data');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RequiredFieldsWidget::class,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-settings::admin.settings title');
    }

    public function getTitle(): string|Htmlable
    {
        return self::getNavigationLabel();
    }
}
