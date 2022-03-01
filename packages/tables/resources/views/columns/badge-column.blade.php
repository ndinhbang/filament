@php
    $state = $getFormattedState();

    $stateColor = $getStateColor();
    switch ($getStateColor()) {
        case 'danger':
            $stateColor = \Illuminate\Support\Arr::toCssClasses(['text-danger-700 bg-danger-500/10', 'dark:text-danger-500' => config('tables.dark_mode')]);break;
        case 'primary':
            $stateColor = \Illuminate\Support\Arr::toCssClasses(['text-primary-700 bg-primary-500/10', 'dark:text-primary-500' => config('tables.dark_mode')]);break;
        case 'success':
            $stateColor = \Illuminate\Support\Arr::toCssClasses(['text-success-700 bg-success-500/10', 'dark:text-success-500' => config('tables.dark_mode')]);break;
        case 'warning':
            $stateColor = \Illuminate\Support\Arr::toCssClasses(['text-warning-700 bg-warning-500/10', 'dark:text-warning-500' => config('tables.dark_mode')]);break;
        case null:
            $stateColor = \Illuminate\Support\Arr::toCssClasses(['text-gray-700 bg-gray-500/10', 'dark:text-gray-300 dark:bg-gray-500/20' => config('tables.dark_mode')]);break;
        default:
            $stateColor = $getStateColor();break;
    }
    switch ($getAlignment()) {
        case 'left':
            $class = 'justify-start';break;
        case 'center':
            $class = 'justify-center';break;
        case 'right':
            $class = 'justify-end';break;
        default:
            $class = null;break;
    }
@endphp

<div {{ $attributes->merge($getExtraAttributes())->class([
    'px-4 py-3 flex filament-tables-badge-column',
    $class,
]) }}>
    @if (filled($state))
        <span @class([
            'inline-flex items-center justify-center min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-normal',
            $stateColor => $stateColor,
        ])>
            {{ $state }}
        </span>
    @endif
</div>
