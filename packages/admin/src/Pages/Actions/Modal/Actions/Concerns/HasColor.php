<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait HasColor
{
    /**
     * @var string|null
     */
    protected $color;

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
