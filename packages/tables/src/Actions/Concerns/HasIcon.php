<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait HasIcon
{
    /**
     * @var \Closure|string|null
     */
    protected $icon = null;

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }
}
