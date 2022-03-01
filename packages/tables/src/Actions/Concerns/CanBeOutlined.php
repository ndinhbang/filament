<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait CanBeOutlined
{
    /**
     * @var bool|\Closure
     */
    protected $isOutlined = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function outlined($condition = true)
    {
        $this->isOutlined = $condition;

        return $this;
    }

    public function isOutlined(): bool
    {
        return $this->evaluate($this->isOutlined);
    }
}
