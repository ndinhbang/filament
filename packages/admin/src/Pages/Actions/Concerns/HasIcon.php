<?php

namespace Filament\Pages\Actions\Concerns;

trait HasIcon
{
    /**
     * @var string|null
     */
    protected $icon;

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
