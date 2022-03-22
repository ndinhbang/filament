<?php

namespace Filament\Forms\Concerns;

use Filament\Forms\Components\Component;

trait BelongsToParentComponent
{
    /**
     * @var \Filament\Forms\Components\Component|null
     */
    protected $parentComponent;

    /**
     * @return $this
     */
    public function parentComponent(Component $component)
    {
        $this->parentComponent = $component;

        return $this;
    }

    public function getParentComponent(): ?Component
    {
        return $this->parentComponent;
    }
}
