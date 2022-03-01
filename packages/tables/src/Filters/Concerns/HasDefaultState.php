<?php

namespace Filament\Tables\Filters\Concerns;

trait HasDefaultState
{
    protected $defaultState = null;

    /**
     * @return $this
     */
    public function default($state = true)
    {
        $this->defaultState = $state;

        return $this;
    }

    public function getDefaultState()
    {
        return $this->evaluate($this->defaultState);
    }
}
