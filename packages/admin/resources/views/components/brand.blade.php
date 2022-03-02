@if (filled($brand = config('filament.brand')))
    <div {{ $attributes->class([
        'text-xl font-bold tracking-tight filament-brand',
        'dark:text-white' => config('filament.dark_mode'),
    ]) }}>
        {{ $brand }}
    </div>
@endif
