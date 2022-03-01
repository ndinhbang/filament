<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeAutocompleted
{
    /**
     * @var \Closure|string|null
     */
    protected $autocomplete = null;

    /**
     * @param \Closure|string|null $autocomplete
     * @return $this
     */
    public function autocomplete($autocomplete = 'on')
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableAutocomplete($condition = true)
    {
        $this->autocomplete(function () use ($condition): ?string {
            return $this->evaluate($condition) ? 'off' : null;
        });

        return $this;
    }

    public function getAutocomplete(): ?string
    {
        return $this->evaluate($this->autocomplete);
    }
}
