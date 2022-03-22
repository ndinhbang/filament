<?php

namespace Filament\Forms\Concerns;

use Filament\Forms\Contracts\HasForms;

trait BelongsToLivewire
{
    /**
     * @var \Filament\Forms\Contracts\HasForms
     */
    protected $livewire;

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
