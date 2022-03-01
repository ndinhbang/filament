@props([
    'actions',
    'align' => 'left',
])

@if ($actions instanceof \Illuminate\Contracts\View\View)
    {{ $actions }}
@elseif (is_array($actions))
    @php
        $actions = array_filter(
            $actions,
            fn (\Filament\Pages\Actions\Action $action): bool => ! $action->isHidden()
        );
        switch ($align) {
            case 'center':
                $class = 'justify-center';break;
            case 'right':
                $class = 'justify-end';break;
            default:
                $class = 'justify-start';break;
        }
    @endphp

    @if (count($actions))
        <div
            {{ $attributes->class([
                'flex flex-wrap items-center gap-4 filament-page-actions',
               $class,
            ]) }}
        >
            @foreach ($actions as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
@endif
