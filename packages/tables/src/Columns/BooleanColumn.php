<?php

namespace Filament\Tables\Columns;

use Closure;

class BooleanColumn extends Column
{
    /**
     * @var string
     */
    protected $view = 'tables::columns.boolean-column';

    /**
     * @var \Closure|string|null
     */
    protected $falseColor = null;

    /**
     * @var \Closure|string|null
     */
    protected $falseIcon = null;

    /**
     * @var \Closure|string|null
     */
    protected $trueColor = null;

    /**
     * @var \Closure|string|null
     */
    protected $trueIcon = null;

    /**
     * @param \Closure|string|null $icon
     * @param \Closure|string|null $color
     * @return $this
     */
    public function false($icon = null, $color = null)
    {
        $this->falseIcon($icon);
        $this->falseColor($color);

        return $this;
    }

    /**
     * @param \Closure|string|null $color
     * @return $this
     */
    public function falseColor($color)
    {
        $this->falseColor = $color;

        return $this;
    }

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function falseIcon($icon)
    {
        $this->falseIcon = $icon;

        return $this;
    }

    /**
     * @param \Closure|string|null $icon
     * @param \Closure|string|null $color
     * @return $this
     */
    public function true($icon = null, $color = null)
    {
        $this->trueIcon($icon);
        $this->trueColor($color);

        return $this;
    }

    /**
     * @param \Closure|string|null $color
     * @return $this
     */
    public function trueColor($color)
    {
        $this->trueColor = $color;

        return $this;
    }

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function trueIcon($icon)
    {
        $this->trueIcon = $icon;

        return $this;
    }

    public function getFalseColor(): ?string
    {
        return $this->evaluate($this->falseColor);
    }

    public function getFalseIcon(): ?string
    {
        return $this->evaluate($this->falseIcon);
    }

    public function getStateColor(): ?string
    {
        $state = $this->getState();

        if ($state === null) {
            return null;
        }

        return $state ? $this->getTrueColor() : $this->getFalseColor();
    }

    public function getStateIcon(): ?string
    {
        $state = $this->getState();

        if ($state === null) {
            return null;
        }

        return $state ? $this->getTrueIcon() : $this->getFalseIcon();
    }

    public function getTrueColor(): ?string
    {
        return $this->evaluate($this->trueColor);
    }

    public function getTrueIcon(): ?string
    {
        return $this->evaluate($this->trueIcon);
    }
}
