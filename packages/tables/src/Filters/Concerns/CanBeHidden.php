<?php

namespace Filament\Tables\Filters\Concerns;

use Closure;

trait CanBeHidden
{
    /**
     * @var bool|\Closure
     */
    protected $isHidden = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function hidden($condition = true)
    {
        $this->isHidden = $condition;

        return $this;
    }

    public function isHidden(): bool
    {
        return $this->evaluate($this->isHidden);
    }
}
