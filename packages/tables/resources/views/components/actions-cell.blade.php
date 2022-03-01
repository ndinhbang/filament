@props([
    'actions',
    'record',
])

@php
    switch (config('tables.layout.action_alignment')) {
        case 'center':
            $class = 'justify-center';
            break;
        case 'left':
            $class = 'justify-start';
            break;
        default:
            $class = 'justify-end';
            break;
    }
@endphp

<td {{ $attributes->class(['px-4 py-3 whitespace-nowrap filament-tables-actions-cell']) }}>
    <div {{ $attributes->class([
        'flex items-center gap-4',
        $class,
    ]) }}>
        @foreach ($actions as $action)
            @if (! $action->record($record)->isHidden())
                {{ $action }}
            @endif
        @endforeach
    </div>
</td>
