@props([
    'form',
    'width' => 'sm',
])

@php
    switch ($width) {
        case 'xs':
            $class = 'max-w-xs';break;
        case 'md':
            $class = 'max-w-md';break;
        case 'lg':
            $class = 'max-w-lg';break;
        case 'xl':
            $class = 'max-w-xl';break;
        case '2xl':
            $class = 'max-w-2xl';break;
        case '3xl':
            $class = 'max-w-3xl';break;
        case '4xl':
            $class = 'max-w-4xl';break;
        case '5xl':
            $class = 'max-w-5xl';break;
        case '6xl':
            $class = 'max-w-6xl';break;
        case '7xl':
            $class = 'max-w-7xl';break;
        default:
            $class = 'max-w-sm';break;
    }
@endphp

<div
    x-data="{ isOpen: false }"
    {{ $attributes->class(['relative inline-block filament-tables-filters']) }}
>
    <x-tables::filters.trigger />

    <div
        x-show="isOpen"
        x-cloak
        x-on:click.away="isOpen = false"
        x-transition:enter="ease duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        @class([
            'absolute right-0 rtl:right-auto rtl:left-0 z-10 w-screen pl-12 rtl:pr-12 mt-2 top-full transition',
            $class,
        ])
    >
        <div @class([
            'px-6 py-4 bg-white space-y-6 shadow-xl rounded-xl',
            'dark:bg-gray-700' => config('tables.dark_mode'),
        ])>
            {{ $form }}

            <div class="text-right">
                <x-tables::link
                    wire:click="resetTableFiltersForm"
                    color="danger"
                    tag="button"
                    class="text-sm font-medium"
                >
                    {{ __('tables::table.filters.buttons.reset.label') }}
                </x-tables::link>
            </div>
        </div>
    </div>
</div>
