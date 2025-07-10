<x-filament::page :header-widgets-columns="4">

    <form wire:submit.prevent="submit" class="fi-sc-form">
        {{ $this->form }}

        <div class="fi-sc  fi-sc-has-gap fi-grid">
            <div class="fi-sc-actions">
                <div class="fi-ac fi-align-start">
                    <x-filament::button type="submit">
                        {{ __('filament-settings::widget.save') }}
                    </x-filament::button>
                </div>
            </div>
        </div>
    </form>

</x-filament::page>
