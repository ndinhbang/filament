@php
    switch (config('filament.layout.max_content_width')) {
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
        case 'full':
            $class = 'max-w-full';break;
        default:
            $class = 'max-w-7xl';break;
    }
@endphp
<x-filament::layouts.base :title="$title">
    <div class="flex min-h-screen w-full filament-app-layout">
        <div
            x-data="{}"
            x-cloak
            x-show="$store.sidebar.isOpen"
            x-transition.opacity.500ms
            x-on:click="$store.sidebar.close()"
            class="fixed inset-0 z-20 w-full h-full bg-gray-900/50 lg:hidden filament-sidebar-close-overlay"
        ></div>

        <x-filament::layouts.app.sidebar />

        <div class="w-screen space-y-6 flex-1 flex flex-col lg:pl-80 rtl:lg:pl-0 rtl:lg:pr-80 filament-main">
            <header @class([
                'h-[4rem] shrink-0 w-full border-b flex items-center filament-main-topbar',
                'dark:bg-gray-800 dark:border-gray-700' => config('filament.dark_mode'),
            ])>
                <div @class([
                    'flex items-center w-full px-2 mx-auto sm:px-4 md:px-6 lg:px-8',
                    $class,
                ])>
                    <button x-data="{}" x-on:click="$store.sidebar.open()" class="shrink-0 flex items-center justify-center w-10 h-10 text-primary-500 rounded-full filament-sidebar-open-button hover:bg-gray-500/5 focus:bg-primary-500/10 focus:outline-none lg:hidden">
                        <x-heroicon-o-menu class="w-6 h-6" />
                    </button>

                    <div class="flex-1 flex gap-4 items-center justify-between">
                        <x-filament::layouts.app.topbar.breadcrumbs :breadcrumbs="$breadcrumbs" />

                        @livewire('filament.core.global-search')

                        <x-filament::layouts.app.topbar.user-menu />
                    </div>
                </div>
            </header>

            <div @class([
                'flex-1 w-full px-4 mx-auto md:px-6 lg:px-8 filament-main-content',
                $class,
            ])>
                {{ $slot }}
            </div>

            <div class="py-4 shrink-0 filament-main-footer">
                <x-filament::footer />
            </div>
        </div>
    </div>
</x-filament::layouts.base>
