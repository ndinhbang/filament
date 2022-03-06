<h2 class="{{ \Illuminate\Support\Arr::toCssClasses([
    'text-xl font-bold tracking-tight filament-tables-empty-state-heading',
    'dark:text-white' => config('tables.dark_mode'),
]) }}">
    {{ $slot }}
</h2>
