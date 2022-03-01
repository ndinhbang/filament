<?php

namespace Filament\Forms\Components\Concerns;

trait Cloneable
{
    /**
     * @return $this
     */
    public function getClone()
    {
        return clone $this;
    }
}
