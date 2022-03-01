<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait CanBeHidden
{
    /**
     * @var \Closure|string|null
     */
    protected $hiddenFrom = null;

    /**
     * @var bool|\Closure
     */
    protected $isHidden = false;

    /**
     * @var \Closure|string|null
     */
    protected $visibleFrom = null;

    /**
     * @var bool|\Closure
     */
    protected $isVisible = true;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function hidden($condition = true)
    {
        $this->isHidden = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function visible($condition = true)
    {
        $this->isVisible = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $breakpoint
     * @return $this
     */
    public function hiddenFrom($breakpoint)
    {
        $this->hiddenFrom = $breakpoint;

        return $this;
    }

    /**
     * @param \Closure|string|null $breakpoint
     * @return $this
     */
    public function visibleFrom($breakpoint)
    {
        $this->visibleFrom = $breakpoint;

        return $this;
    }

    public function getHiddenFrom(): ?string
    {
        return $this->evaluate($this->hiddenFrom);
    }

    public function getVisibleFrom(): ?string
    {
        return $this->evaluate($this->visibleFrom);
    }

    public function isHidden(): bool
    {
        if ($this->evaluate($this->isHidden)) {
            return true;
        }

        return ! $this->evaluate($this->isVisible);
    }
}
