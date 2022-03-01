<?php

namespace Filament\Pages\Actions\Concerns;

trait CanRequireConfirmation
{
    protected bool $isConfirmationRequired = false;

    /**
     * @return $this
     */
    public function requiresConfirmation(bool $condition = true)
    {
        $this->isConfirmationRequired = $condition;

        return $this;
    }

    public function isConfirmationRequired(): bool
    {
        return $this->isConfirmationRequired;
    }
}
