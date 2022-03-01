<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait HasColor
{
    protected ?string $color = null;

    /**
     * @return $this
     */
    public function color(?string $color)
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
