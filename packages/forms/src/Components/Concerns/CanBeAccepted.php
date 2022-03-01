<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeAccepted
{
    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function accepted($condition = true)
    {
        $this->rule('accepted', $condition);

        return $this;
    }
}
