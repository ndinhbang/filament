<?php

namespace Filament\Widgets;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Widget extends Component
{
    protected static ?int $sort = null;

    protected static string $view;

    /**
     * @var mixed[]|int|string
     */
    protected $columnSpan = 1;

    public static function canView(): bool
    {
        return true;
    }

    public static function getSort(): int
    {
        return static::$sort ?? -1;
    }

    /**
     * @return mixed[]|int|string
     */
    protected function getColumnSpan()
    {
        return $this->columnSpan;
    }

    protected function getViewData(): array
    {
        return [];
    }

    public function render(): View
    {
        return view(static::$view, $this->getViewData());
    }
}
