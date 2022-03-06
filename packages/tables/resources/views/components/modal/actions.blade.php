@props([
    'align' => 'left',
    'fullWidth' => false,
])

<div class="{{ \Illuminate\Support\Arr::toCssClasses([
    'filament-tables-modal-actions',
    'flex flex-wrap items-center gap-4' => ! $fullWidth,
    'justify-end' => (! $fullWidth) && ($align === 'right'),
    'justify-center' => (! $fullWidth) && ($align === 'center'),
    'grid gap-2 grid-cols-[repeat(auto-fit,minmax(0,1fr))]' => $fullWidth,
]) }}">
    {{ $slot }}
</div>
