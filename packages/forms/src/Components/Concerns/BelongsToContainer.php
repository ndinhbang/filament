<?php

namespace Filament\Forms\Components\Concerns;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Contracts\HasForms;

trait BelongsToContainer
{
    /**
     * @var \Filament\Forms\ComponentContainer
     */
    protected $container;

    /**
     * @return $this
     */
    public function container(ComponentContainer $container)
    {
        $this->container = $container;

        return $this;
    }

    public function getContainer(): ComponentContainer
    {
        return $this->container;
    }

    public function getLivewire(): HasForms
    {
        return $this->getContainer()->getLivewire();
    }
}
