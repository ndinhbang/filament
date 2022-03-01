<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasLabel
{
    /**
     * @var bool|\Closure
     */
    protected $isLabelHidden = false;

    /**
     * @var \Closure|string|null
     */
    protected $label = null;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableLabel($condition = true)
    {
        $this->isLabelHidden = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->evaluate($this->label);
    }

    public function isLabelHidden(): bool
    {
        return $this->evaluate($this->isLabelHidden);
    }
}
