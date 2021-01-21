<div class="space-y-2">
    @if ($field->label || $field->hint)
        <div class="flex items-center justify-between space-x-2">
            @if ($field->label)
                <x-filament::label :for="$field->id">
                    {{ __($field->label) }}
                </x-filament::label>
            @endif

            @if ($field->hint)
                <x-filament::hint>
                    @markdown($field->hint)
                </x-filament::hint>
            @endif
        </div>
    @endif

    @yield('field')

    <x-filament::error :field="$field->error ?? $field->model" />

    @if ($field->help)
        <x-filament::help>
            @markdown($field->help)
        </x-filament::help>
    @endif
</div>