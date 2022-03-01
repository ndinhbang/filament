<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait CanRequireConfirmation
{
    /**
     * @var bool|\Closure
     */
    protected $isConfirmationRequired = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function requiresConfirmation($condition = true)
    {
        $this->isConfirmationRequired = $condition;

        return $this;
    }

    public function isConfirmationRequired(): bool
    {
        return $this->evaluate($this->isConfirmationRequired);
    }
}
