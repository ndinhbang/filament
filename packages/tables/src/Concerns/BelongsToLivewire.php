<?php

namespace Filament\Tables\Concerns;

use Filament\Tables\Contracts\HasTable;

trait BelongsToLivewire
{
    /**
     * @var \Filament\Tables\Contracts\HasTable
     */
    protected $livewire;

    /**
     * @return $this
     */
    public function livewire(HasTable $livewire)
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): HasTable
    {
        return $this->livewire;
    }
}
