<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait HasColors
{
    /**
     * @var mixed[]|\Closure
     */
    protected $colors = [];

    /**
     * @param mixed[]|\Closure $colors
     * @return $this
     */
    public function colors($colors)
    {
        $this->colors = $colors;

        return $this;
    }

    public function getStateColor(): ?string
    {
        $state = $this->getState();
        $stateColor = null;

        foreach ($this->getColors() as $color => $condition) {
            if (is_numeric($color)) {
                $stateColor = $condition;
            } elseif ($condition instanceof Closure && $condition($state)) {
                $stateColor = $color;
            } elseif ($condition === $state) {
                $stateColor = $color;
            }
        }

        return $stateColor;
    }

    public function getColors(): array
    {
        return $this->evaluate($this->colors);
    }
}
