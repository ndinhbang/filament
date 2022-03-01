<?php

namespace Filament\Forms\Concerns;

trait CanBeDisabled
{
    protected bool $isDisabled = false;

    /**
     * @return $this
     */
    public function disabled(bool $condition = true)
    {
        $this->isDisabled = $condition;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->isDisabled || (($getParentComponent = $this->getParentComponent()) ? $getParentComponent->isDisabled() : null);
    }
}
