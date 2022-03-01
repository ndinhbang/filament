<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait HasColor
{
    /**
     * @var \Closure|string|null
     */
    protected $color = null;

    /**
     * @param \Closure|string|null $color
     * @return $this
     */
    public function color($color)
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->evaluate($this->color);
    }
}
