<x-filament::card>
    <x-filament::form :fields="$this->getForm()->fields" class="space-y-6">
        <x-filament::button
            type="submit"
            class="btn-primary"
            wire:loading.attr="disabled"
        >
            {{ __('filament::edit-account.update') }}
        </x-filament::button>
    </x-filament::form>
</x-filament::card>