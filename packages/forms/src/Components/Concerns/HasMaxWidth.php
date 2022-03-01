<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasMaxWidth
{
    /**
     * @var \Closure|string|null
     */
    protected $maxWidth = null;

    /**
     * @param \Closure|string|null $width
     * @return $this
     */
    public function maxWidth($width)
    {
        $this->maxWidth = $width;

        return $this;
    }

    public function getMaxWidth(): ?string
    {
        return $this->evaluate($this->maxWidth);
    }
}
