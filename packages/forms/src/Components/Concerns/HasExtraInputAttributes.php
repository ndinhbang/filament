<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\View\ComponentAttributeBag;

trait HasExtraInputAttributes
{
    /**
     * @var mixed[]|\Closure
     */
    protected $extraInputAttributes = [];

    /**
     * @param mixed[]|\Closure $attributes
     * @return $this
     */
    public function extraInputAttributes($attributes)
    {
        $this->extraInputAttributes = $attributes;

        return $this;
    }

    public function getExtraInputAttributes(): array
    {
        return $this->evaluate($this->extraInputAttributes);
    }

    public function getExtraInputAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraInputAttributes());
    }
}
