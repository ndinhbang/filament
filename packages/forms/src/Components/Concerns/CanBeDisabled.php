<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeDisabled
{
    /**
     * @var bool|\Closure
     */
    protected $isDisabled = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disabled($condition = true)
    {
        $this->isDisabled = $condition;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->evaluate($this->isDisabled) || $this->getContainer()->isDisabled();
    }
}
