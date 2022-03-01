@php
    $state = $getState();

    $stateIcon = $getStateIcon() ?? ($state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle');
    $stateColor = $getStateColor() ?? ($state ? 'success' : 'danger');

    switch ($stateColor) {
        case 'danger':
            $stateColor = 'text-danger-500';break;
        case 'primary':
            $stateColor = 'text-primary-500';break;
        case 'success':
            $stateColor = 'text-success-500';break;
        case 'warning':
            $stateColor = 'text-warning-500';break;
        default:
            $stateColor = 'text-gray-700';break;
    }
@endphp

<div {{ $attributes->merge($getExtraAttributes())->class(['px-4 py-3 filament-tables-boolean-column']) }}>
    @if ($state !== null)
        <x-dynamic-component
            :component="$stateIcon"
            :class="'w-6 h-6' . ' ' . $stateColor"
        />
    @endif
</div>
