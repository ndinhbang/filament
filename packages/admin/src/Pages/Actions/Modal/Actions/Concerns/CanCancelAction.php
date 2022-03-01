<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait CanCancelAction
{
    protected bool $canCancelAction = false;

    /**
     * @return $this
     */
    public function cancel(bool $condition = true)
    {
        $this->canCancelAction = $condition;

        return $this;
    }

    public function canCancelAction(): bool
    {
        return $this->canCancelAction;
    }
}
