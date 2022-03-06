@props([
    'footer' => null,
    'header' => null,
])

<table {{ \Illuminate\Support\Arr::toCssClasses([
    'w-full text-left rtl:text-right divide-y table-auto filament-tables-table',
    'dark:divide-gray-700' => config('tables.dark_mode'),
]) }}>
    @if ($header)
        <thead>
            <tr class="bg-gray-50">
                {{ $header }}
            </tr>
        </thead>
    @endif

    <tbody {{ \Illuminate\Support\Arr::toCssClasses([
        'divide-y whitespace-nowrap',
        'dark:divide-gray-700' => config('tables.dark_mode'),
    ]) }}>
        {{ $slot }}
    </tbody>

    @if ($footer)
        <tfoot>
            <tr class="bg-gray-50">
                {{ $footer }}
            </tr>
        </tfoot>
    @endif
</table>
