<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait HasIcon
{
    protected ?string $icon = null;

    /**
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
