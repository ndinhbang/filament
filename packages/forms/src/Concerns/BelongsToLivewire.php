<?php

namespace Filament\Forms\Concerns;

use Filament\Forms\Contracts\HasForms;

trait BelongsToLivewire
{
    protected HasForms $livewire;

    /**
     * @return $this
     */
    public function livewire(HasForms $livewire)
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): HasForms
    {
        return $this->livewire;
    }
}
