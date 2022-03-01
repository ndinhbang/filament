<?php

namespace Filament\Forms\Concerns;

trait Cloneable
{
    /**
     * @return $this
     */
    public function cloneComponents()
    {
        $components = [];

        foreach ($this->getComponents(withHidden: true) as $component) {
            $components[] = $component->getClone();
        }

        return $this->components($components);
    }

    /**
     * @return $this
     */
    public function getClone()
    {
        $clone = clone $this;
        $clone->cloneComponents();

        return $clone;
    }
}
