<?php

namespace Filament\Forms\Concerns;

use Filament\Forms\Components\Component;

trait HasStateBindingModifiers
{
    protected $stateBindingModifiers = null;

    /**
     * @return $this
     */
    public function reactive()
    {
        $this->stateBindingModifiers([]);

        return $this;
    }

    /**
     * @return $this
     */
    public function lazy()
    {
        $this->stateBindingModifiers(['lazy']);

        return $this;
    }

    /**
     * @return $this
     */
    public function stateBindingModifiers(array $modifiers)
    {
        $this->stateBindingModifiers = $modifiers;

        return $this;
    }

    public function applyStateBindingModifiers($expression): string
    {
        $modifiers = $this->getStateBindingModifiers();

        return implode('.', array_merge([$expression], $modifiers));
    }

    public function getStateBindingModifiers(): array
    {
        if ($this->stateBindingModifiers !== null) {
            return $this->stateBindingModifiers;
        }

        if ($this instanceof Component) {
            return $this->getContainer()->getStateBindingModifiers();
        }

        if ($this->getParentComponent()) {
            return $this->getParentComponent()->getStateBindingModifiers();
        }

        return ['defer'];
    }
}
