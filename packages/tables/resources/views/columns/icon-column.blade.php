@php
    $color = $stateColor = $getStateColor();
    if (is_null($color)) {
        $stateColor = \Illuminate\Support\Arr::toCssClasses([
            'text-gray-700',
            'dark:text-gray-200' => config('tables.dark_mode'),
        ]);
    } elseif ($color === 'danger') {
        $stateColor = 'text-danger-500';
    } elseif ($color === 'primary') {
        $stateColor = 'text-primary-500';
    } elseif ($color === 'success') {
        $stateColor = 'text-success-500';
    } elseif ($color === 'warning') {
        $stateColor = 'text-warning-500';
    }
@endphp

<div {{ $attributes->merge($getExtraAttributes())->class(['px-4 py-3 filament-tables-icon-column']) }}>
    @if ($getStateIcon())
        <x-dynamic-component
            :component="$getStateIcon()"
            :class="'w-6 h-6 ' . $stateColor"
        />
    @endif
</div>
