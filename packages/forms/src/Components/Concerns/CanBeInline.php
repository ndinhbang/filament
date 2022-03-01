<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeInline
{
    /**
     * @var bool|\Closure
     */
    protected $isInline = true;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function inline($condition = true)
    {
        $this->isInline = $condition;

        return $this;
    }

    public function isInline(): bool
    {
        return (bool) $this->evaluate($this->isInline);
    }
}
