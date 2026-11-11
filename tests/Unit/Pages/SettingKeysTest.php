<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Wotz\FilamentSettings\Models\Setting;
use Wotz\FilamentSettings\Pages\Settings;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettingKeys;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(SettingTabRepository::class)->registerTab([TestSettingKeys::class]);
});

it('saves a key with one dot under the field name', function () {
    Livewire::test(Settings::class)
        ->fillForm(['site.title' => 'Brigada'])
        ->call('submit')
        ->assertHasNoFormErrors();

    expect(setting('site.title'))->toBe('Brigada');
});

it('saves a key with two dots under the field name', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'mail.from.name' => 'Brigada',
            'mail.from.address' => 'hello@brigada.be',
        ])
        ->call('submit')
        ->assertHasNoFormErrors();

    expect(setting('mail.from.name'))->toBe('Brigada')
        ->and(setting('mail.from.address'))->toBe('hello@brigada.be')
        ->and(Setting::query()->where('key', 'mail.from')->exists())->toBeFalse();
});

it('saves a key with three dots under the field name', function () {
    Livewire::test(Settings::class)
        ->fillForm(['shop.mail.from.name' => 'Brigada shop'])
        ->call('submit')
        ->assertHasNoFormErrors();

    expect(setting('shop.mail.from.name'))->toBe('Brigada shop')
        ->and(Setting::query()->whereIn('key', ['shop.mail', 'shop.mail.from'])->exists())->toBeFalse();
});

it('saves a key without a dot under the field name', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'undotted' => 'value',
            'undotted_list' => ['a', 'b'],
        ])
        ->call('submit')
        ->assertHasNoFormErrors();

    expect(setting('undotted'))->toBe('value')
        ->and(json_decode(setting('undotted_list'), true))->toBe(['a', 'b'])
        ->and(Setting::query()->where('key', 'like', 'undotted_list.%')->exists())->toBeFalse();
});

it('shows a saved value again after a reload', function () {
    Livewire::test(Settings::class)
        ->fillForm(['mail.from.name' => 'Brigada'])
        ->call('submit');

    $state = Livewire::test(Settings::class)->get('form')->getState();

    expect($state['mail']['from']['name'])->toBe('Brigada');
});
