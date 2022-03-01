<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\Support\Arr;

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
    public function when($condition = true)
    {
        $this->visible($condition);

        return $this;
    }

    /**
     * @param mixed[]|string $paths
     * @return $this
     */
    public function whenTruthy($paths)
    {
        $paths = Arr::wrap($paths);

        $this->hidden(function (Closure $get) use ($paths): bool {
            foreach ($paths as $path) {
                if (! $get($path)) {
                    return true;
                }
            }

            return false;
        });

        return $this;
    }

    /**
     * @param mixed[]|string $paths
     * @return $this
     */
    public function whenFalsy($paths)
    {
        $paths = Arr::wrap($paths);

        $this->hidden(function (Closure $get) use ($paths): bool {
            foreach ($paths as $path) {
                if (! ! $get($path)) {
                    return true;
                }
            }

            return false;
        });

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
