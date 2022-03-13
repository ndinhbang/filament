@props([
    'id',
    'label' => null,
    'labelPrefix' => null,
    'labelSrOnly' => false,
    'labelSuffix' => null,
    'helperText' => null,
    'hint' => null,
    'hintIcon' => null,
    'required' => false,
    'statePath',
])

<div class="{{ \Illuminate\Support\Arr::toCssClasses(['filament-forms-field-wrapper']) }}">
    @if ($label && $labelSrOnly)
        <label for="{{ $id }}" class="sr-only">
            {{ $label }}
        </label>
    @endif

    <div class="space-y-2">
        @if (($label && ! $labelSrOnly) || $hint)
            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                @if ($label && ! $labelSrOnly)
                    <x-forms::field-wrapper.label
                        :for="$id"
                        :error="$errors->has($statePath)"
                        :prefix="$labelPrefix"
                        :required="$required"
                        :suffix="$labelSuffix"
                    >
                        {{ $label }}
                    </x-forms::field-wrapper.label>
                @endif

                @if ($hint || $hintIcon)
                    <x-forms::field-wrapper.hint :icon="$hintIcon">
                        {!! filled($hint) ? \Illuminate\Support\Str::markdown($hint) : null !!}
                    </x-forms::field-wrapper.hint>
                @endif
            </div>
        @endif

        {{ $slot }}

        @if ($errors->has($statePath))
            <x-forms::field-wrapper.error-message>
                {{ $errors->first($statePath) }}
            </x-forms::field-wrapper.error-message>
        @endif

        @if ($helperText)
            <x-forms::field-wrapper.helper-text>
                {!! \Illuminate\Support\Str::markdown($helperText) !!}
            </x-forms::field-wrapper.helper-text>
        @endif
    </div>
</div>
