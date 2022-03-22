<?php

namespace Filament\Tables\Actions\Modal\Actions\Concerns;

trait CanCancelAction
{
    /**
     * @var bool
     */
    protected $canCancelAction = false;

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
