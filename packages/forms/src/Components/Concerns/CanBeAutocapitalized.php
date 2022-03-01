<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeAutocapitalized
{
    /**
     * @var \Closure|string|null
     */
    protected $autocapitalize = null;

    /**
     * @param \Closure|string|null $autocapitalize
     * @return $this
     */
    public function autocapitalize($autocapitalize = 'on')
    {
        $this->autocapitalize = $autocapitalize;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableAutocapitalize($condition = true)
    {
        $this->autocapitalize(function () use ($condition): ?string {
            return $this->evaluate($condition) ? 'off' : null;
        });

        return $this;
    }

    public function getAutocapitalize(): ?string
    {
        return $this->evaluate($this->autocapitalize);
    }
}
