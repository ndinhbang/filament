@php
    switch ($this->getColumnSpan()) {
        case 2:
            $class = 'col-span-2';break;
        case 3:
            $class = 'col-span-3';break;
        case 4:
            $class = 'col-span-4';break;
        case 5:
            $class = 'col-span-5';break;
        case 6:
            $class = 'col-span-6';break;
        case 7:
            $class = 'col-span-7';break;
        case 8:
            $class = 'col-span-8';break;
        case 9:
            $class = 'col-span-9';break;
        case 10:
            $class = 'col-span-10';break;
        case 11:
            $class = 'col-span-11';break;
        case 12:
            $class = 'col-span-12';break;
        case 'full':
            $class = 'col-span-full';break;
        case 1:
            $class = 'col-span-1';break;
    }
@endphp
<div {{ $attributes->class([
    'filament-widget',
    $class,
]) }}>
    {{ $slot }}
</div>
