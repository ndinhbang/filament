<?php

namespace Filament\Forms\Concerns;

trait CanBeHidden
{
    public function isHidden(): bool
    {
        return (bool) (($getParentComponent = $this->getParentComponent()) ? $getParentComponent->isHidden() : null);
    }
}
