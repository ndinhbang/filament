<h2 class="{{ \Illuminate\Support\Arr::toCssClasses([
    'text-xl font-bold tracking-tight filament-tables-modal-heading',
    'dark:text-white' => config('tables.dark_mode'),
]) }}">
    {{ $slot }}
</h2>
