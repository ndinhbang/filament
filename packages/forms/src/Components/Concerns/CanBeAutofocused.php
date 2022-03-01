<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeAutofocused
{
    /**
     * @var bool|\Closure
     */
    protected $isAutofocused = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function autofocus($condition = true)
    {
        $this->isAutofocused = $condition;

        return $this;
    }

    public function isAutofocused(): bool
    {
        return (bool) $this->evaluate($this->isAutofocused);
    }
}
