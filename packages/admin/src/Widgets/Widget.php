<?php

namespace Filament\Widgets;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Widget extends Component
{
    /**
     * @var int|null
     */
    protected static $sort;

    /**
     * @var string
     */
    protected static $view;

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
