<?php

namespace Filament\Forms\Components;

class View extends Component
{
    final public function __construct(string $view)
    {
        $this->view($view);
    }

    /**
     * @return $this
     */
    public static function make(string $view)
    {
        return app(static::class, ['view' => $view]);
    }
}
