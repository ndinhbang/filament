<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait HasExtraHeaderAttributes
{
    /**
     * @var mixed[]|\Closure
     */
    protected $extraHeaderAttributes = [];

    /**
     * @param mixed[]|\Closure $attributes
     * @return $this
     */
    public function extraHeaderAttributes($attributes)
    {
        $this->extraHeaderAttributes = $attributes;

        return $this;
    }

    public function getExtraHeaderAttributes(): array
    {
        return $this->evaluate($this->extraHeaderAttributes);
    }
}
