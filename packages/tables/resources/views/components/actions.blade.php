@props([
    'actions',
])

<div class="{{ \Illuminate\Support\Arr::toCssClasses(['flex flex-wrap items-center gap-4 filament-tables-actions-container']) }}">
    @foreach ($actions as $action)
        {{ $action }}
    @endforeach
</div>
