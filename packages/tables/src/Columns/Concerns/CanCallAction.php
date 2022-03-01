<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;

trait CanCallAction
{
    protected ?Closure $action = null;

    /**
     * @param \Closure|string|null $action
     * @return $this
     */
    public function action($action)
    {
        if (is_string($action)) {
            $action = function (HasTable $livewire, ?Model $record) use ($action) {
                if ($record) {
                    return $livewire->{$action}($record);
                }

                return $livewire->{$action}();
            };
        }

        $this->action = $action;

        return $this;
    }

    public function callAction()
    {
        return $this->evaluate($this->getAction());
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }
}
