@props([
    'chart' => null,
    'chartColor' => null,
    'color' => null,
    'description' => null,
    'descriptionColor' => null,
    'descriptionIcon' => null,
    'flat' => false,
    'label' => null,
    'value' => null,
])

@php
    switch ($descriptionColor) {
        case 'danger':
            $descriptionColorClass = 'text-danger-600';break;
        case 'primary':
            $descriptionColorClass = 'text-primary-600';break;
        case 'success':
            $descriptionColorClass = 'text-success-600';break;
        case 'warning':
            $descriptionColorClass = 'text-warning-600';break;
        default:
            $descriptionColorClass = 'text-gray-600';break;
    }
    switch ($chartColor) {
        case 'danger':
            $chartColorClass1 = \Illuminate\Support\Arr::toCssClasses(['text-danger-50', 'dark:text-danger-700' => config('filament.dark_mode')]);break;
        case 'primary':
            $chartColorClass1 = \Illuminate\Support\Arr::toCssClasses(['text-primary-50', 'dark:text-primary-700' => config('filament.dark_mode')]);break;
        case 'success':
            $chartColorClass1 = \Illuminate\Support\Arr::toCssClasses(['text-success-50', 'dark:text-success-700' => config('filament.dark_mode')]);break;
        case 'warning':
            $chartColorClass1 = \Illuminate\Support\Arr::toCssClasses(['text-warning-50', 'dark:text-warning-700' => config('filament.dark_mode')]);break;
        default:
            $chartColorClass1 = \Illuminate\Support\Arr::toCssClasses(['text-gray-50', 'dark:text-gray-700' => config('filament.dark_mode')]);break;
    }
    switch ($chartColor) {
        case 'danger':
            $chartColorClass2 = 'text-danger-400';break;
        case 'primary':
            $chartColorClass2 = 'text-primary-400';break;
        case 'success':
            $chartColorClass2 = 'text-success-400';break;
        case 'warning':
            $chartColorClass2 = 'text-warning-400';break;
        default:
            $chartColorClass2 = 'text-gray-400';break;
    }
@endphp

<div {{ $attributes->class([
    'relative p-6 rounded-2xl filament-stats-card',
    'bg-white shadow' => ! $flat,
    'dark:bg-gray-800' => (! $flat) && config('filament.dark_mode'),
    'border' => $flat,
    'dark:border-gray-700' => $flat && config('filament.dark_mode'),
]) }}>
    <div @class([
        'space-y-2',
    ])>
        <div @class([
            'text-sm font-medium text-gray-500',
            'dark:text-gray-200' => config('filament.dark_mode'),
        ])>
            {{ $label }}
        </div>

        <div class="text-3xl">
            {{ $value }}
        </div>

        @if ($description)
            <div @class([
                'flex items-center space-x-1 rtl:space-x-reverse text-sm font-medium',
                $descriptionColorClass,
            ])>
                <span>{{ $description }}</span>

                @if ($descriptionIcon)
                    <x-dynamic-component :component="$descriptionIcon" class="w-4 h-4" />
                @endif
            </div>
        @endif
    </div>

    @if ($chart)
        <div class="absolute bottom-0 inset-x-0 rounded-b-2xl overflow-hidden">
            <canvas
                x-data="{
                    chart: null,

                    init: function () {
                        chart = new Chart(
                            $el,
                            {
                                type: 'line',
                                data: {
                                    labels: {{ json_encode(array_keys($chart)) }},
                                    datasets: [{
                                        data: {{ json_encode(array_values($chart)) }},
                                        backgroundColor: getComputedStyle($refs.backgroundColorElement).color,
                                        borderColor: getComputedStyle($refs.borderColorElement).color,
                                        borderWidth: 2,
                                        fill: 'start',
                                        tension: 0.5,
                                    }],
                                },
                                options: {
                                    elements: {
                                        point: {
                                            radius: 0,
                                        },
                                    },
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false,
                                        },
                                    },
                                    scales: {
                                        x:  {
                                            display: false,
                                        },
                                        y:  {
                                            display: false,
                                        },
                                    },
                                    tooltips: {
                                        enabled: false,
                                    },
                                },
                            }
                        )
                    },
                }"
                wire:ignore
                class="h-6"
            >
                <span
                    x-ref="backgroundColorElement"
                    @class([
                        $chartColorClass1,
                    ])
                ></span>

                <span
                    x-ref="borderColorElement"
                    @class([
                        $chartColorClass2,
                    ])
                ></span>
            </canvas>
        </div>
    @endif
</div>
