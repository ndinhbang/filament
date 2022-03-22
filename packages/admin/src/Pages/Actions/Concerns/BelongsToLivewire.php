<?php

namespace Filament\Pages\Actions\Concerns;

use Filament\Pages\Page;

trait BelongsToLivewire
{
    /**
     * @var \Filament\Pages\Page
     */
    protected $livewire;

    /**
     * @return $this
     */
    public function livewire(Page $livewire)
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): Page
    {
        return $this->livewire;
    }
}
