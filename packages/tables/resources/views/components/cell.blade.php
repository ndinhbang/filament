@props([
    'action' => null,
    'alignment' => null,
    'name',
    'record',
    'recordAction' => null,
    'recordUrl' => null,
    'shouldOpenUrlInNewTab' => false,
    'url' => null,
])

@php
    switch ($alignment) {
        case 'left':
            $class = 'text-left';break;
        case 'center':
            $class = 'text-center';break;
        case 'right':
            $class = 'text-right';break;
        default:
            $class = null;break;
    }
@endphp

<td
    {{ $attributes->class([
        'filament-tables-cell',
        'dark:text-white' => config('tables.dark_mode'),
        $class,
    ]) }}
>
    @if ($action || ((! $url) && $recordAction))
        <button
            wire:click="{{ $action ? "callTableColumnAction('{$name}', " : "{$recordAction}(" }}'{{ $record->getKey() }}')"
            wire:target="{{ $action ? "callTableColumnAction('{$name}', " : "{$recordAction}(" }}'{{ $record->getKey() }}')"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-70 cursor-wait"
            type="button"
            class="block text-left"
        >
            {{ $slot }}
        </button>
    @elseif ($url || $recordUrl)
        <a
            href="{{ $url ?: $recordUrl }}"
            {{ $shouldOpenUrlInNewTab ? 'target="_blank"' : null }}
            class="block"
        >
            {{ $slot }}
        </a>
    @else
        {{ $slot }}
    @endif
</td>
