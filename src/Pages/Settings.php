<?php

namespace Codedor\FilamentSettings\Pages;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Settings extends Page
{
    protected string $view = 'filament-settings::pages.settings';

    public string $focus = '';

    public ?array $data = [];

    protected $queryString = [
        'focus',
    ];

    public static function getNavigationGroup(): ?string
    {
        return config('filament-settings.navigation.group', parent::getNavigationGroup());
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-settings.navigation.icon', parent::getNavigationIcon());
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function submit()
    {
        /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $interface */
        $interface = app(DriverInterface::class);

        $data = [];

        foreach ($this->form->getState() as $tab => $values) {
            foreach ($values as $key => $value) {
                $data["$tab.$key"] = is_array($value) ? json_encode($value) : $value;
            }
        }

        collect($data)->each(fn ($value, $key) => $interface->set($key, $value));

        Notification::make()
            ->title(self::getNavigationLabel())
            ->body(__('filament-settings::admin.saved'))
            ->success()
            ->send();

        $this->dispatch('filament-settings::refresh-widget');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        /** @var SettingTabRepository $rep */
        $rep = app(SettingTabRepository::class);

        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('Settings')
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
