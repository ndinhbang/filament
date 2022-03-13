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
            function ($action) {
                /**@var \Filament\Pages\Actions\Action $action*/
                return ! $action->isHidden(); // bool
            }
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
            class="{{ \Illuminate\Support\Arr::toCssClasses([
                'flex flex-wrap items-center gap-4 filament-page-actions',
               $class,
            ]) }}"
        >
            @foreach ($actions as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
@endif
