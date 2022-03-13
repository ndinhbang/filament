@if (filled($brand = config('filament.brand')))
    <div class="{{ \Illuminate\Support\Arr::toCssClasses([
        'text-xl font-bold tracking-tight filament-brand',
        'dark:text-white' => config('filament.dark_mode'),
    ]) }}">
        {{ $brand }}
    </div>
@endif
