<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait HasAlignment
{
    /**
     * @var \Closure|string|null
     */
    protected $alignment = null;

    /**
     * @param \Closure|string|null $alignment
     * @return $this
     */
    public function alignment($alignment)
    {
        $this->alignment = $alignment;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function alignLeft($condition = true)
    {
        return $this->alignment(fn (): ?string => $condition ? 'left' : null);
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function alignCenter($condition = true)
    {
        return $this->alignment(fn (): ?string => $condition ? 'center' : null);
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function alignRight($condition = true)
    {
        return $this->alignment(fn (): ?string => $condition ? 'right' : null);
    }

    public function getAlignment(): ?string
    {
        return $this->evaluate($this->alignment);
    }
}
