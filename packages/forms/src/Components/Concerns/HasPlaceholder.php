<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasPlaceholder
{
    /**
     * @var \Closure|string|null
     */
    protected $placeholder = null;

    /**
     * @param \Closure|string|null $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }
}
