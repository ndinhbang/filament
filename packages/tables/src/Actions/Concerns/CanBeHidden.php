<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait CanBeHidden
{
    /**
     * @var bool|\Closure
     */
    protected $isHidden = false;

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

    public function isHidden(): bool
    {
        if ($this->evaluate($this->isHidden)) {
            return true;
        }

        return ! $this->evaluate($this->isVisible);
    }
}
