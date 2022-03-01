<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait HasAction
{
    /**
     * @var \Closure|string|null
     */
    protected $action = null;

    /**
     * @param \Closure|string|null $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): ?Closure
    {
        $action = $this->action;

        if (is_string($action)) {
            $action = Closure::fromCallable([$this->getLivewire(), $action]);
        }

        return $action;
    }
}
