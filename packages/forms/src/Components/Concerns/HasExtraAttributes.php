<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasExtraAttributes
{
    /**
     * @var mixed[]|\Closure
     */
    protected $extraAttributes = [];

    /**
     * @param mixed[]|\Closure $attributes
     * @return $this
     */
    public function extraAttributes($attributes)
    {
        $this->extraAttributes = $attributes;

        return $this;
    }

    public function getExtraAttributes(): array
    {
        return $this->evaluate($this->extraAttributes);
    }
}
