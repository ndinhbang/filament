@props([
    'details' => [],
    'title',
    'url',
])

<li class="{{ \Illuminate\Support\Arr::toCssClasses(['filament-global-search-result']) }}">
    <a href="{{ $url }}" class="relative block px-6 py-4 focus:bg-gray-500/5 hover:bg-gray-500/5 focus:ring-1 focus:ring-gray-300">
        <p class="{{ \Illuminate\Support\Arr::toCssClasses([
            'font-medium',
            'dark:text-gray-200' => config('filament.dark_mode'),
        ]) }}">
            {{ $title }}
        </p>

        <p class="{{ \Illuminate\Support\Arr::toCssClasses([
            'text-sm space-x-2 rtl:space-x-reverse font-medium text-gray-500',
            'dark:text-gray-400' => config('filament.dark_mode'),
        ]) }}">
            @foreach ($details as $label => $value)
                <span>
                    <span class="{{ \Illuminate\Support\Arr::toCssClasses([
                        'font-medium text-gray-700',
                        'dark:text-gray-200' => config('filament.dark_mode'),
                    ]) }}">
                        {{ $label }}:
                    </span>

                    <span>
                        {{ $value }}
                    </span>
                </span>
            @endforeach
        </p>
    </a>
</li>
